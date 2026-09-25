<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\LandingSetting;

class LandingPageController extends Controller
{
    public function edit()
    {
        $settings = LandingSetting::pluck('value', 'key')->toArray();
        return view('admin.landing.edit', compact('settings'));
    }

    public function update(Request $request)
    {
        $data = $request->except(['_token', '_method']);
        
        if ($request->hasFile('about_image')) {
            $imagePath = $request->file('about_image')->store('about', 'public');
            $data['about_image'] = $imagePath;
            
            $oldImage = LandingSetting::where('key', 'about_image')->value('value');
            if ($oldImage && !str_starts_with($oldImage, 'landingPage/')) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($oldImage);
            }
        }

        if ($request->hasFile('parallax_bg_1')) {
            $imagePath = $request->file('parallax_bg_1')->store('landing', 'public');
            $data['parallax_bg_1'] = $imagePath;
            
            $oldImage = LandingSetting::where('key', 'parallax_bg_1')->value('value');
            if ($oldImage && !str_starts_with($oldImage, 'landingPage/')) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($oldImage);
            }
        }

        if ($request->hasFile('parallax_bg_2')) {
            $imagePath = $request->file('parallax_bg_2')->store('landing', 'public');
            $data['parallax_bg_2'] = $imagePath;
            
            $oldImage = LandingSetting::where('key', 'parallax_bg_2')->value('value');
            if ($oldImage && !str_starts_with($oldImage, 'landingPage/')) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($oldImage);
            }
        }
        
        foreach ($data as $key => $value) {
            LandingSetting::updateOrCreate(
                ['key' => $key],
                ['value' => is_array($value) ? json_encode(array_values(array_filter($value, fn($v) => !is_null($v) && $v !== ''))) : $value]
            );
        }

        return redirect()->back()->with('status', 'landing-settings-updated');
    }
}
