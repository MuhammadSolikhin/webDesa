<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Portfolio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PortfolioController extends Controller
{
    public function index()
    {
        $portfolios = Portfolio::all();
        return view('admin.portfolio.index', compact('portfolios'));
    }

    public function create()
    {
        return view('admin.portfolio.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'required|array',
            'image.*' => 'required|image|max:2048',
            'category' => 'required|string|max:255',
            'map_file' => 'nullable|file|mimes:zip,json,geojson,kml,xml|max:51200',
        ]);

        $imagePaths = [];
        if ($request->hasFile('image')) {
            foreach ($request->file('image') as $file) {
                $imagePaths[] = $file->store('portfolios', 'public');
            }
        }

        $mapFilePath = $this->processMapFile($request);

        Portfolio::create([
            'title' => $request->title,
            'description' => $request->description,
            'image' => $imagePaths,
            'category' => $request->category,
            'map_file' => $mapFilePath,
        ]);

        return redirect()->route('admin.portfolio.index')->with('success', 'Portfolio created successfully.');
    }

    public function edit(Portfolio $portfolio)
    {
        return view('admin.portfolio.edit', compact('portfolio'));
    }

    public function update(Request $request, Portfolio $portfolio)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|array',
            'image.*' => 'nullable|image|max:2048',
            'category' => 'required|string|max:255',
            'map_file' => 'nullable|file|mimes:zip,json,geojson,kml,xml|max:51200',
        ]);

        $data = $request->only(['title', 'description', 'category']);

        if ($request->hasFile('image')) {
            if (!empty($portfolio->image)) {
                $oldImages = is_array($portfolio->image) ? $portfolio->image : [$portfolio->image];
                foreach ($oldImages as $oldImage) {
                    if (Storage::disk('public')->exists($oldImage)) {
                        Storage::disk('public')->delete($oldImage);
                    }
                }
            }
            $imagePaths = [];
            foreach ($request->file('image') as $file) {
                $imagePaths[] = $file->store('portfolios', 'public');
            }
            $data['image'] = $imagePaths;
        }

        if ($request->hasFile('map_file')) {
            $data['map_file'] = $this->processMapFile($request, $portfolio->map_file);
        }

        $portfolio->update($data);

        return redirect()->route('admin.portfolio.index')->with('success', 'Portfolio updated successfully.');
    }

    public function destroy(Portfolio $portfolio)
    {
        if (!empty($portfolio->image)) {
            $oldImages = is_array($portfolio->image) ? $portfolio->image : [$portfolio->image];
            foreach ($oldImages as $oldImage) {
                if (Storage::disk('public')->exists($oldImage)) {
                    Storage::disk('public')->delete($oldImage);
                }
            }
        }
        
        if ($portfolio->map_file) {
            $this->deleteMapFile($portfolio->map_file);
        }
        
        $portfolio->delete();
        
        return redirect()->route('admin.portfolio.index')->with('success', 'Portfolio deleted successfully.');
    }

    private function processMapFile($request, $currentMapFile = null)
    {
        if (!$request->hasFile('map_file')) {
            return $currentMapFile;
        }

        $file = $request->file('map_file');
        $extension = $file->getClientOriginalExtension();

        if ($currentMapFile) {
            $this->deleteMapFile($currentMapFile);
        }

        if ($extension === 'zip') {
            $zipPath = $file->store('map_files/temp', 'public');
            $extractDir = 'map_files/html_maps/' . uniqid();
            $fullExtractPath = storage_path('app/public/' . $extractDir);

            $zip = new \ZipArchive;
            if ($zip->open(storage_path('app/public/' . $zipPath)) === TRUE) {
                $zip->extractTo($fullExtractPath);
                $zip->close();
                Storage::disk('public')->delete($zipPath);

                $indexPath = $this->findIndexHtml($fullExtractPath);
                if ($indexPath) {
                    $publicPath = storage_path('app/public/');
                    $relativePath = str_replace($publicPath, '', $indexPath);
                    return str_replace('\\', '/', $relativePath);
                }
            }
            return null;
        }

        return $file->store('map_files', 'public');
    }

    private function findIndexHtml($dir)
    {
        $iterator = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($dir));
        foreach ($iterator as $file) {
            if ($file->isFile() && $file->getFilename() === 'index.html') {
                return $file->getPathname();
            }
        }
        return null;
    }

    private function deleteMapFile($path)
    {
        if (!$path) return;

        if (str_ends_with(strtolower($path), 'index.html')) {
            $parts = explode('/', str_replace('\\', '/', $path));
            if (isset($parts[0], $parts[1], $parts[2]) && $parts[0] === 'map_files' && $parts[1] === 'html_maps') {
                $dirToDelete = $parts[0] . '/' . $parts[1] . '/' . $parts[2];
                Storage::disk('public')->deleteDirectory($dirToDelete);
            }
        } else {
            Storage::disk('public')->delete($path);
        }
    }
}
