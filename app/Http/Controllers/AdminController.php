<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Setting;

class AdminController extends Controller
{
    public function updateSettings(Request $request)
    {
        $request->validate([
            'print_module' => 'required|in:on,off',
            'payment_module' => 'required|in:on,off',
            'smtp_module' => 'required|in:on,off',
            'price_module' => 'required|in:on,off',
        ]);

        foreach ($request->all() as $key => $value) {
            Setting::updateOrCreate(['key' => $key], ['value' => $value]);
        }

        return response()->json(['message' => 'Settings updated successfully!']);
    }
}

