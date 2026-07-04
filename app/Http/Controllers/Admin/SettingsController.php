<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function index()
    {
        $settings = Setting::all()->groupBy('group');
        return response()->json($settings);
    }

    public function update(Request $request)
    {
        $request->validate([
            'group' => 'required|string|in:site,payment,mail',
            'settings' => 'required|array',
            'settings.*' => 'nullable|string',
        ]);

        foreach ($request->settings as $key => $value) {
            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $value, 'group' => $request->group]
            );
        }

        return response()->json(['message' => 'Settings updated.']);
    }
}
