<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function index()
    {
        $services = Service::all();
        return view('admin.service.index', compact('services'));
    }

    public function create()
    {
        return view('admin.service.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'icon' => 'required|string',
        ]);

        Service::create($request->only(['title', 'description', 'icon']));

        return redirect()->route('admin.service.index')->with('success', 'Service created successfully.');
    }

    public function edit(Service $service)
    {
        return view('admin.service.edit', compact('service'));
    }

    public function update(Request $request, Service $service)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'icon' => 'required|string',
        ]);

        $service->update($request->only(['title', 'description', 'icon']));

        return redirect()->route('admin.service.index')->with('success', 'Service updated successfully.');
    }

    public function destroy(Service $service)
    {
        $service->delete();
        return redirect()->route('admin.service.index')->with('success', 'Service deleted successfully.');
    }
}
