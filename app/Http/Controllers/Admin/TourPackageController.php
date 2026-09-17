<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TourPackage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TourPackageController extends Controller
{
    public function index()
    {
        $tourPackages = TourPackage::all();
        return view('admin.tour_package.index', compact('tourPackages'));
    }

    public function create()
    {
        return view('admin.tour_package.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'nullable|numeric|min:0',
            'image' => 'nullable|array',
            'image.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:10240',
            'kml_file' => 'nullable|file|mimetypes:application/vnd.google-earth.kml+xml,text/xml|max:10240',
        ]);

        $imagePaths = [];
        if ($request->hasFile('image')) {
            foreach ($request->file('image') as $file) {
                $imagePaths[] = $file->store('tour_packages', 'public');
            }
        }

        $kmlFilePath = null;
        if ($request->hasFile('kml_file')) {
            $kmlFilePath = $request->file('kml_file')->store('kml_files', 'public');
        }

        TourPackage::create([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'image' => $imagePaths,
            'kml_file' => $kmlFilePath,
        ]);

        return redirect()->route('admin.tour-package.index')->with('success', 'Paket wisata berhasil ditambahkan.');
    }

    public function show(TourPackage $tourPackage)
    {
        // Not used
    }

    public function edit(TourPackage $tourPackage)
    {
        return view('admin.tour_package.edit', compact('tourPackage'));
    }

    public function update(Request $request, TourPackage $tourPackage)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'nullable|numeric|min:0',
            'image' => 'nullable|array',
            'image.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:10240',
            'kml_file' => 'nullable|file|mimetypes:application/vnd.google-earth.kml+xml,text/xml|max:10240',
        ]);

        if ($request->hasFile('image')) {
            if (!empty($tourPackage->image)) {
                $oldImages = is_array($tourPackage->image) ? $tourPackage->image : [$tourPackage->image];
                foreach ($oldImages as $oldImage) {
                    if (Storage::disk('public')->exists($oldImage)) {
                        Storage::disk('public')->delete($oldImage);
                    }
                }
            }
            $imagePaths = [];
            foreach ($request->file('image') as $file) {
                $imagePaths[] = $file->store('tour_packages', 'public');
            }
            $tourPackage->image = $imagePaths;
        }

        if ($request->hasFile('kml_file')) {
            if ($tourPackage->kml_file && Storage::disk('public')->exists($tourPackage->kml_file)) {
                Storage::disk('public')->delete($tourPackage->kml_file);
            }
            $kmlFilePath = $request->file('kml_file')->store('kml_files', 'public');
            $tourPackage->kml_file = $kmlFilePath;
        }

        $tourPackage->name = $request->name;
        $tourPackage->description = $request->description;
        $tourPackage->price = $request->price;
        $tourPackage->save();

        return redirect()->route('admin.tour-package.index')->with('success', 'Paket wisata berhasil diperbarui.');
    }

    public function destroy(TourPackage $tourPackage)
    {
        if (!empty($tourPackage->image)) {
            $oldImages = is_array($tourPackage->image) ? $tourPackage->image : [$tourPackage->image];
            foreach ($oldImages as $oldImage) {
                if (Storage::disk('public')->exists($oldImage)) {
                    Storage::disk('public')->delete($oldImage);
                }
            }
        }
        if ($tourPackage->kml_file && Storage::disk('public')->exists($tourPackage->kml_file)) {
            Storage::disk('public')->delete($tourPackage->kml_file);
        }
        $tourPackage->delete();

        return redirect()->route('admin.tour-package.index')->with('success', 'Paket wisata berhasil dihapus.');
    }

    public function editKml(TourPackage $tourPackage)
    {
        if (!$tourPackage->kml_file || !Storage::disk('public')->exists($tourPackage->kml_file)) {
            return redirect()->back()->with('error', 'File KML tidak ditemukan.');
        }

        $path = storage_path('app/public/' . $tourPackage->kml_file);
        $doc = new \DOMDocument();
        @$doc->load($path); // suppress warnings for malformed XML
        
        $placemarks = $doc->getElementsByTagName('Placemark');
        $items = [];
        
        $i = 0;
        foreach ($placemarks as $node) {
            $nameNode = $node->getElementsByTagName('name');
            $name = $nameNode->length > 0 ? $nameNode->item(0)->nodeValue : 'Tanpa Nama';
            
            $descNode = $node->getElementsByTagName('description');
            $description = $descNode->length > 0 ? $descNode->item(0)->nodeValue : '';
            
            $items[] = [
                'index' => $i,
                'name' => $name,
                'description' => $description
            ];
            $i++;
        }

        return view('admin.tour_package.kml_editor', compact('tourPackage', 'items'));
    }

    public function updateKml(Request $request, TourPackage $tourPackage)
    {
        if (!$tourPackage->kml_file || !Storage::disk('public')->exists($tourPackage->kml_file)) {
            return redirect()->back()->with('error', 'File KML tidak ditemukan.');
        }

        $order = $request->input('order', []);
        $names = $request->input('names', []);
        $descriptions = $request->input('descriptions', []);

        $path = storage_path('app/public/' . $tourPackage->kml_file);
        $doc = new \DOMDocument();
        $doc->preserveWhiteSpace = false;
        $doc->formatOutput = true;
        @$doc->load($path);

        $placemarks = $doc->getElementsByTagName('Placemark');
        $nodes = [];
        foreach ($placemarks as $node) {
            $nodes[] = $node;
        }

        if (count($nodes) > 0 && count($order) === count($nodes)) {
            $parent = $nodes[0]->parentNode;

            // Remove all from parent
            foreach ($nodes as $node) {
                $parent->removeChild($node);
            }

            // Re-append in new order and update values
            foreach ($order as $i => $oldIndex) {
                $node = $nodes[$oldIndex];
                
                // Update Name
                $nameList = $node->getElementsByTagName('name');
                if ($nameList->length > 0) {
                    $nameList->item(0)->nodeValue = ''; // clear
                    $nameList->item(0)->appendChild($doc->createTextNode($names[$i] ?? ''));
                } else {
                    $nameNode = $doc->createElement('name');
                    $nameNode->appendChild($doc->createTextNode($names[$i] ?? ''));
                    $node->insertBefore($nameNode, $node->firstChild);
                }

                // Update Description
                $descList = $node->getElementsByTagName('description');
                if ($descList->length > 0) {
                    $descNode = $descList->item(0);
                    while ($descNode->hasChildNodes()) {
                        $descNode->removeChild($descNode->firstChild);
                    }
                    $cdata = $doc->createCDATASection($descriptions[$i] ?? '');
                    $descNode->appendChild($cdata);
                } else {
                    $descNode = $doc->createElement('description');
                    $cdata = $doc->createCDATASection($descriptions[$i] ?? '');
                    $descNode->appendChild($cdata);
                    
                    // Insert after name if exists, otherwise at beginning
                    $nameList = $node->getElementsByTagName('name');
                    if ($nameList->length > 0 && $nameList->item(0)->nextSibling) {
                        $node->insertBefore($descNode, $nameList->item(0)->nextSibling);
                    } else {
                        $node->appendChild($descNode);
                    }
                }

                $parent->appendChild($node);
            }
            
            $doc->save($path);
        }

        return redirect()->route('admin.tour-package.edit', $tourPackage)->with('success', 'Konten KML berhasil diperbarui.');
    }
}
