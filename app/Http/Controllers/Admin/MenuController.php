<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    public function index()
    {
        $menus = Menu::with('parent')->orderBy('order')->get();
        return view('admin.menu.index', compact('menus'));
    }

    public function create()
    {
        $parentMenus = Menu::whereNull('parent_id')->get();
        return view('admin.menu.create', compact('parentMenus'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'url' => 'nullable|string|max:255',
            'parent_id' => 'nullable|exists:menus,id',
            'order' => 'required|integer',
            'is_active' => 'boolean',
        ]);

        Menu::create([
            'name' => $request->name,
            'url' => $request->url,
            'parent_id' => $request->parent_id,
            'order' => $request->order,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.menu.index')->with('success', 'Menu created successfully.');
    }

    public function edit(Menu $menu)
    {
        $parentMenus = Menu::whereNull('parent_id')->where('id', '!=', $menu->id)->get();
        return view('admin.menu.edit', compact('menu', 'parentMenus'));
    }

    public function update(Request $request, Menu $menu)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'url' => 'nullable|string|max:255',
            'parent_id' => 'nullable|exists:menus,id',
            'order' => 'required|integer',
            'is_active' => 'boolean',
        ]);

        $menu->update([
            'name' => $request->name,
            'url' => $request->url,
            'parent_id' => $request->parent_id,
            'order' => $request->order,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.menu.index')->with('success', 'Menu updated successfully.');
    }

    public function destroy(Menu $menu)
    {
        $menu->delete();
        return redirect()->route('admin.menu.index')->with('success', 'Menu deleted successfully.');
    }
}
