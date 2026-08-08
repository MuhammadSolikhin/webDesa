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
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('tour_packages', 'public');
        }

        TourPackage::create([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'image' => $imagePath,
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
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('image')) {
            if ($tourPackage->image && Storage::disk('public')->exists($tourPackage->image)) {
                Storage::disk('public')->delete($tourPackage->image);
            }
            $imagePath = $request->file('image')->store('tour_packages', 'public');
            $tourPackage->image = $imagePath;
        }

        $tourPackage->name = $request->name;
        $tourPackage->description = $request->description;
        $tourPackage->price = $request->price;
        $tourPackage->save();

        return redirect()->route('admin.tour-package.index')->with('success', 'Paket wisata berhasil diperbarui.');
    }

    public function destroy(TourPackage $tourPackage)
    {
        if ($tourPackage->image && Storage::disk('public')->exists($tourPackage->image)) {
            Storage::disk('public')->delete($tourPackage->image);
        }
        $tourPackage->delete();

        return redirect()->route('admin.tour-package.index')->with('success', 'Paket wisata berhasil dihapus.');
    }
}
