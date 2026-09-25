<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Webgis;
use App\Models\LandingSetting;

class WebgisController extends Controller
{
    public function index()
    {
        $layers = Webgis::latest()->get();
        
        $settingsRaw = LandingSetting::pluck('value', 'key')->toArray();
        $settings = [];
        foreach ($settingsRaw as $key => $value) {
            $settings[$key] = json_decode($value) ?? $value;
        }

        return view('webgis', compact('layers', 'settings'));
    }
}
