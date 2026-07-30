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
            'image' => 'required|image|max:2048',
            'category' => 'required|string|max:255',
        ]);

        $imagePath = $request->file('image')->store('portfolios', 'public');

        Portfolio::create([
            'title' => $request->title,
            'description' => $request->description,
            'image' => $imagePath,
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
            'image' => 'nullable|image|max:2048',
            'category' => 'required|string|max:255',
        ]);

        $data = $request->only(['title', 'description', 'category']);

        if ($request->hasFile('image')) {
            if ($portfolio->image) {
                Storage::disk('public')->delete($portfolio->image);
            }
            $data['image'] = $request->file('image')->store('portfolios', 'public');
        }

        $portfolio->update($data);

        return redirect()->route('admin.portfolio.index')->with('success', 'Portfolio updated successfully.');
    }

    public function destroy(Portfolio $portfolio)
    {
        if ($portfolio->image) {
            Storage::disk('public')->delete($portfolio->image);
        }
        $portfolio->delete();
        
        return redirect()->route('admin.portfolio.index')->with('success', 'Portfolio deleted successfully.');
    }
}
