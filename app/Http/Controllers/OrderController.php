<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\File;
use App\Models\Setting;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Smalot\PdfParser\Parser;

class OrderController extends Controller
{
    /**
     * Display the order form with settings.
     */
    public function create()
    {
        // $settings = Setting::pluck('value', 'key')->toArray();
        $settings = getSettings();
        // dd($settings);
        return view('order_form', compact('settings'));
    }

    /**
     * Store the order with multiple file uploads.
     */
    // public function store(Request $request)
    // {
    //     $validated = $request->validate([
    //         'name' => 'required',
    //         'email' => 'required|email',
    //         'phone' => 'required',
    //         'address' => 'required',
    //         'code_postal' => 'required',
    //         'commune' => 'required',
    //         'files.*' => 'required|file|mimes:pdf|max:64000',
    //     ]);

    //     // Store print order data
    //     $order = Order::create($validated);

    //     foreach ($request->file('files') as $file) {
    //         $path = $file->store('uploads');

    //         // Use PDF Parser to count pages
    //         $parser = new Parser();
    //         $pdf = $parser->parseFile(storage_path('app/' . $path));
    //         $numPages = count($pdf->getPages());

    //         File::create([
    //             'print_order_id' => $order->id,
    //             'file_path' => $path,
    //             'num_pages' => $numPages,
    //             'copies' => $request->copies ?? 1,
    //             'price' => null, // You can add price calculation logic here
    //             'impression' => $request->impression ?? 'color',
    //             'orientation' => $request->orientation ?? 'portrait',
    //             'size' => $request->size ?? 'A4',
    //             'front_back' => $request->front_back ?? false,
    //             'binding' => $request->binding ?? false,
    //         ]);
    //     }

    //     return redirect()->back()->with('success', 'Order placed successfully!');
    // }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'phone' => 'required',
            'address' => 'required',
            'code_postal' => 'required',
            'commune' => 'required',
            'files.*' => 'required|file|mimes:pdf|max:64000',
            'num_pages' => 'required|array',
        ]);

        // ✅ Create the order FIRST so we get the ID
        $order = Order::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'address' => $validated['address'],
            'code_postal' => $validated['code_postal'],
            'commune' => $validated['commune'],
        ]);

        // ✅ Now insert files linked to the created order
        foreach ($request->file('files') as $index => $file) {
            $path = $file->store('uploads');

            File::create([
                'order_id' => $order->id, // ✅ Correctly set the order ID
                'file_path' => $path,
                'num_pages' => $request->num_pages[$index] ?? 1,
                'copies' => $request->copies[$index] ?? 1,
                'price' => $request->price[$index] ?? 0,
                'impression' => $request->impression ?? 'color',
                'orientation' => $request->orientation ?? 'portrait',
                'size' => is_array($request->size) ? implode(',', $request->size) : $request->size ?? 'A4',
                'front_back' => $request->front_back  ? 1 : 0,
                'binding' => $request->binding ? 1 : 0,
            ]);
        }

        return redirect()->back()->with('success', 'Order placed successfully!');
    }
}
