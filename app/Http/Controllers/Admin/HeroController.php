<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Hero;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class HeroController extends Controller
{
    public function index()
    {
        $heroes = Hero::all();
        return view('admin.hero.index', compact('heroes'));
    }

    public function create()
    {
        return view('admin.hero.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'required|image|mimes:jpeg,png,jpg,webp,gif|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('heroes', 'public');
            $validated['image'] = $imagePath;
        }

        Hero::create($validated);

        return redirect()->route('admin.hero.index')->with('success', 'Hero berhasil ditambahkan.');
    }

    public function edit(Hero $hero)
    {
        return view('admin.hero.edit', compact('hero'));
    }

    public function update(Request $request, Hero $hero)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp,gif|max:2048',
        ]);

        if ($request->hasFile('image')) {
            if ($hero->image) {
                Storage::disk('public')->delete($hero->image);
            }
            $imagePath = $request->file('image')->store('heroes', 'public');
            $validated['image'] = $imagePath;
        }

        $hero->update($validated);

        return redirect()->route('admin.hero.index')->with('success', 'Hero berhasil diperbarui.');
    }

    public function destroy(Hero $hero)
    {
        if ($hero->image) {
            Storage::disk('public')->delete($hero->image);
        }
        $hero->delete();

        return redirect()->route('admin.hero.index')->with('success', 'Hero berhasil dihapus.');
    }
}
