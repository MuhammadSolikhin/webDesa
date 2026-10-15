<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Webgis;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class WebgisController extends Controller
{
    public function index()
    {
        $webgis = Webgis::latest()->get();
        return view('admin.webgis.index', compact('webgis'));
    }

    public function create()
    {
        return view('admin.webgis.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'file' => 'required|file|max:102400', // max 100MB
            'description' => 'nullable|string',
        ], [
            'name.required' => 'Nama layer wajib diisi.',
            'file.required' => 'File WebGIS wajib diunggah.',
            'file.file' => 'File yang diunggah tidak valid.',
            'file.max' => 'Ukuran file maksimal adalah 100MB.',
        ]);

        $file = $request->file('file');
        $extension = strtolower($file->getClientOriginalExtension());

        // Jika file berupa arsip RAR atau ZIP, ekstrak dan cari file peta di dalamnya
        if (in_array($extension, ['rar', 'zip'])) {
            // 1. Simpan arsip asli
            $archiveName = Str::random(30) . '.' . $extension;
            $archiveRelativePath = $file->storeAs('webgis/archives', $archiveName, 'public');
            $archiveFullPath = storage_path('app/public/' . $archiveRelativePath);

            // 2. Buat direktori ekstraksi
            $uniqueId = time() . '_' . Str::random(8);
            $extractRelativeDir = 'webgis/extracted/' . $uniqueId;
            $extractFullDir = storage_path('app/public/' . $extractRelativeDir);

            if (!is_dir($extractFullDir)) {
                mkdir($extractFullDir, 0755, true);
            }

            // 3. Ekstrak arsip
            $extracted = $this->extractArchive($archiveFullPath, $extractFullDir, $extension);
            if (!$extracted) {
                return back()->withInput()->withErrors([
                    'file' => 'Gagal mengekstrak file arsip ' . strtoupper($extension) . '. Pastikan file tidak rusak atau tidak memiliki password.'
                ]);
            }

            // 4. Cari file data spasial yang diekstrak (.geojson, .kml, .shp)
            $spatialFiles = $this->findSpatialFiles($extractFullDir);

            if (empty($spatialFiles['geojson']) && empty($spatialFiles['kml']) && empty($spatialFiles['shp'])) {
                return back()->withInput()->withErrors([
                    'file' => 'File arsip ' . strtoupper($extension) . ' berhasil diekstrak, namun tidak ditemukan file data peta (.geojson, .kml, atau Shapefile .shp) di dalamnya.'
                ]);
            }

            // 5. Tentukan file utama untuk ditampilkan pada peta:
            // Prioritas 1: GeoJSON
            if (!empty($spatialFiles['geojson'])) {
                $targetFile = $spatialFiles['geojson'][0];
                $validated['file'] = $this->getRelativeStoragePath($targetFile);
            }
            // Prioritas 2: KML
            elseif (!empty($spatialFiles['kml'])) {
                $targetFile = $spatialFiles['kml'][0];
                $validated['file'] = $this->getRelativeStoragePath($targetFile);
            }
            // Prioritas 3: Shapefile (.shp)
            elseif (!empty($spatialFiles['shp'])) {
                $shpFile = $spatialFiles['shp'][0];
                $shapefileZip = $this->packageShapefileToZip($shpFile, $extractFullDir);
                $validated['file'] = $this->getRelativeStoragePath($shapefileZip);
            }
        } else {
            // File langsung (GeoJSON, KML, JSON)
            $filename = Str::random(40) . '.' . $extension;
            $validated['file'] = $file->storeAs('webgis', $filename, 'public');
        }

        Webgis::create($validated);
        return redirect()->route('admin.webgis.index')->with('success', 'File WebGIS berhasil diproses dan siap ditampilkan di peta interaktif.');
    }

    public function destroy(Webgis $webgi)
    {
        if ($webgi->file) {
            Storage::disk('public')->delete($webgi->file);
            
            // Hapus folder ekstraksi jika ada
            $dir = dirname($webgi->file);
            if (str_starts_with($dir, 'webgis/extracted/')) {
                Storage::disk('public')->deleteDirectory($dir);
            }
        }
        $webgi->delete();
        return redirect()->route('admin.webgis.index')->with('success', 'File WebGIS berhasil dihapus.');
    }

    /**
     * Ekstrak file ZIP atau RAR
     */
    private function extractArchive(string $archiveFilePath, string $destinationDir, string $extension): bool
    {
        if ($extension === 'zip') {
            if (class_exists('\ZipArchive')) {
                $zip = new \ZipArchive();
                if ($zip->open($archiveFilePath) === true) {
                    $zip->extractTo($destinationDir);
                    $zip->close();
                    return true;
                }
            }
            return false;
        }

        if ($extension === 'rar') {
            // Metode 1: UnRAR.exe (WinRAR bawaan di Windows)
            $unrarCandidates = [
                'C:\\Program Files\\WinRAR\\UnRAR.exe',
                'C:\\Program Files (x86)\\WinRAR\\UnRAR.exe',
                'unrar',
            ];
            foreach ($unrarCandidates as $unrar) {
                if (file_exists($unrar) || $unrar === 'unrar') {
                    $cmd = escapeshellarg($unrar) . ' x -y -inul ' . escapeshellarg($archiveFilePath) . ' ' . escapeshellarg($destinationDir . DIRECTORY_SEPARATOR);
                    $output = [];
                    $ret = -1;
                    exec($cmd, $output, $ret);
                    if ($ret === 0) {
                        return true;
                    }
                }
            }

            // Metode 2: bsdtar bawaan Windows System32
            $tarCmd = 'tar -xf ' . escapeshellarg($archiveFilePath) . ' -C ' . escapeshellarg($destinationDir);
            $output = [];
            $ret = -1;
            exec($tarCmd, $output, $ret);
            if ($ret === 0) {
                return true;
            }
        }

        return false;
    }

    /**
     * Cari semua file spasial (.geojson, .kml, .shp) di dalam direktori hasil ekstraksi secara rekursif
     */
    private function findSpatialFiles(string $dir): array
    {
        $result = [
            'geojson' => [],
            'kml' => [],
            'shp' => [],
        ];

        try {
            $iterator = new \RecursiveIteratorIterator(
                new \RecursiveDirectoryIterator($dir, \RecursiveDirectoryIterator::SKIP_DOTS),
                \RecursiveIteratorIterator::SELF_FIRST
            );

            foreach ($iterator as $item) {
                if ($item->isFile()) {
                    $ext = strtolower($item->getExtension());
                    if ($ext === 'geojson') {
                        $result['geojson'][] = $item->getPathname();
                    } elseif ($ext === 'json') {
                        // Verifikasi jika file JSON berisi data spasial
                        $content = @file_get_contents($item->getPathname(), false, null, 0, 1024);
                        if ($content && (str_contains($content, 'Feature') || str_contains($content, 'geometry') || str_contains($content, 'coordinates'))) {
                            $result['geojson'][] = $item->getPathname();
                        }
                    } elseif ($ext === 'kml') {
                        $result['kml'][] = $item->getPathname();
                    } elseif ($ext === 'shp') {
                        $result['shp'][] = $item->getPathname();
                    }
                }
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Error scanning extracted files: ' . $e->getMessage());
        }

        return $result;
    }

    /**
     * Kemas komponen Shapefile (.shp, .dbf, .shx, .prj) menjadi berkas ZIP yang kompatibel dengan shpjs
     */
    private function packageShapefileToZip(string $shpFilePath, string $outputDir): string
    {
        $baseName = pathinfo($shpFilePath, PATHINFO_FILENAME);
        $shpDir = dirname($shpFilePath);
        $zipPath = $outputDir . DIRECTORY_SEPARATOR . $baseName . '.zip';

        if (class_exists('\ZipArchive')) {
            $zip = new \ZipArchive();
            if ($zip->open($zipPath, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) === true) {
                $extensions = ['shp', 'shx', 'dbf', 'prj', 'cpg', 'sbn', 'sbx'];
                foreach ($extensions as $ext) {
                    $candidate = $shpDir . DIRECTORY_SEPARATOR . $baseName . '.' . $ext;
                    if (file_exists($candidate)) {
                        $zip->addFile($candidate, $baseName . '.' . $ext);
                    }
                    // Handle lowercase / uppercase extension variations
                    $candidateUpper = $shpDir . DIRECTORY_SEPARATOR . $baseName . '.' . strtoupper($ext);
                    if ($ext !== strtoupper($ext) && file_exists($candidateUpper)) {
                        $zip->addFile($candidateUpper, $baseName . '.' . $ext);
                    }
                }
                $zip->close();
                return $zipPath;
            }
        }

        return $shpFilePath;
    }

    /**
     * Konversi path absolut ke path storage relatif
     */
    private function getRelativeStoragePath(string $fullPath): string
    {
        $storagePublic = str_replace('\\', '/', storage_path('app/public/'));
        $normalizedFullPath = str_replace('\\', '/', $fullPath);
        return str_replace($storagePublic, '', $normalizedFullPath);
    }
}
