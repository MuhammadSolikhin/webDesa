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
        ]);

        $imagePaths = [];
        if ($request->hasFile('image')) {
            foreach ($request->file('image') as $file) {
                $imagePaths[] = $file->store('portfolios', 'public');
            }
        }

        Portfolio::create([
            'title' => $request->title,
            'description' => $request->description,
            'image' => $imagePaths,
            'category' => $request->category,
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
        $portfolio->delete();
        
        return redirect()->route('admin.portfolio.index')->with('success', 'Portfolio deleted successfully.');
    }
}
