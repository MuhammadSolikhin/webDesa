<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Webgis;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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
            'file' => 'required|file', // Accepting any file but meant for geojson
            'description' => 'nullable|string',
        ]);

        if ($request->hasFile('file')) {
            $validated['file'] = $request->file('file')->store('webgis', 'public');
        }

        Webgis::create($validated);
        return redirect()->route('admin.webgis.index')->with('success', 'File WebGIS berhasil diunggah.');
    }

    public function destroy(Webgis $webgi)
    {
        if ($webgi->file) {
            Storage::disk('public')->delete($webgi->file);
        }
        $webgi->delete();
        return redirect()->route('admin.webgis.index')->with('success', 'File WebGIS berhasil dihapus.');
    }
}
