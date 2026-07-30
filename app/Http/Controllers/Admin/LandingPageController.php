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
        
        foreach ($data as $key => $value) {
            LandingSetting::updateOrCreate(
                ['key' => $key],
                ['value' => is_array($value) ? json_encode($value) : $value]
            );
        }

        return redirect()->back()->with('status', 'landing-settings-updated');
    }
}
