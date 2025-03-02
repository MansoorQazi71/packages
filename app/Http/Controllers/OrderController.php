<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\File;
use App\Models\Setting;
use Illuminate\Support\Facades\Mail;

class OrderController extends Controller
{
    public function store(Request $request)
    {
        // Validate input
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email',
            'phone' => 'required|string',
            'address' => 'required|string',
            'postal_code' => 'required|string',
            'commune' => 'required|string',
            'activity' => 'required|in:particular,professional',
            'grand_total' => 'nullable|numeric',
        ]);

        // Create Order
        $order = Order::create($request->all());

        // Handle File Uploads
        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                FileController::uploadFile($file, $order->id);
            }
        }

        // Send Confirmation Email if SMTP is activated
        if (Setting::getValue('smtp_module') == 'on') {
            Mail::to($order->email)->send(new \App\Mail\OrderConfirmation($order));
        }

        return response()->json(['message' => 'Order submitted successfully!', 'order' => $order]);
    }
}
