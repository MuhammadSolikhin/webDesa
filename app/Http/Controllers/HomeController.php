<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Service;
use App\Models\LandingSetting;

class HomeController extends Controller
{
    public function index()
    {
        $heroes = \App\Models\Hero::all();
        $services = Service::all();
        $portfolios = \App\Models\Portfolio::all();
        $tourPackages = \App\Models\TourPackage::all();
        $galleries = \App\Models\Gallery::latest()->get();
        $articles = \App\Models\Article::latest()->take(3)->get();
        $teams = \App\Models\TeamMember::all();
        
        $settingsRaw = LandingSetting::pluck('value', 'key')->toArray();
        $settings = [];
        foreach ($settingsRaw as $key => $value) {
            $settings[$key] = json_decode($value) ?? $value;
        }

        return view('welcome', compact('heroes', 'services', 'settings', 'portfolios', 'tourPackages', 'galleries', 'articles', 'teams'));
    }

    public function portfolio(\App\Models\Portfolio $portfolio)
    {
        return view('portfolio.show', compact('portfolio'));
    }

    public function tourPackage(\App\Models\TourPackage $tourPackage)
    {
        return view('tour-package.show', compact('tourPackage'));
    }

    public function service(\App\Models\Service $service)
    {
        $galleries = \App\Models\Gallery::latest()->get();
        return view('service.show', compact('service', 'galleries'));
    }
}
