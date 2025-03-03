<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Setting;

class AdminController extends Controller {
    public function showSettings() {
        $settings = Setting::all();
        return view('admin.settings', compact('settings'));
    }

    public function updateSettings(Request $request) {
        $settingsKeys = ['print_module', 'payment_module', 'smtp_module', 'price_module'];
    
        foreach ($settingsKeys as $key) {
            Setting::setValue($key, $request->has($key) ? 'on' : 'off');
        }
    
        return response()->json(['message' => 'Settings updated successfully!']);
    }
    
}
