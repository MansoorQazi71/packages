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

    //working code for drag and drop file upload
    // public function store(Request $request)
    // {
    //     // dd($request->all());
    //     $validated = $request->validate([
    //         'name' => 'required',
    //         'email' => 'required|email',
    //         'phone' => 'required',
    //         'address' => 'required',
    //         'code_postal' => 'required',
    //         'commune' => 'required',
    //         'files' => 'required|array',
    //         'files.*' => 'file|mimes:pdf,png,jpg,jpeg|max:64000',
    //         'num_pages.*' => 'required|integer|min:1',
    //     ]);

    //     // ✅ Create the order FIRST so we get the ID
    //     $order = Order::create([
    //         'name' => $validated['name'],
    //         'email' => $validated['email'],
    //         'phone' => $validated['phone'],
    //         'address' => $validated['address'],
    //         'code_postal' => $validated['code_postal'],
    //         'commune' => $validated['commune'],
    //     ]);

    //     // ✅ Check if files exist before looping
    //     if ($request->hasFile('files')) {
    //         foreach ($request->file('files') as $index => $file) {
    //             $path = $file->store('uploads');

    //             File::create([
    //                 'order_id' => $order->id,
    //                 'file_path' => $path,
    //                 'num_pages' => $request->num_pages[$index] ?? 1,
    //                 'copies' => $request->copies[$index] ?? 1,
    //                 'price' => $request->price[$index] ?? 0,
    //                 'impression' => $request->impression ?? 'color',
    //                 'orientation' => $request->orientation ?? 'portrait',
    //                 'size' => is_array($request->size) ? implode(',', $request->size) : $request->size ?? 'A4',
    //                 'front_back' => isset($request->front_back[$index]) ? 1 : 0,
    //                 'binding' => isset($request->binding[$index]) ? 1 : 0,
    //             ]);
    //         }
    //     }

    //     return redirect()->back()->with('success', 'Order placed successfully!');
    // }

    // public function store(Request $request)
    // {
    //     // Validate request (Ensure required fields)
    //     $request->validate([
    //         'name' => 'required|string|max:255',
    //         'email' => 'required|email',
    //         'phone' => 'required|string',
    //         'address' => 'required|string',
    //         'postal_code' => 'required|string',
    //         'commune' => 'required|string',
    //         'files' => 'required|array', // Ensure files array exists
    //         'files.*.upload' => 'file|mimes:pdf,jpg,jpeg,png|max:10240', // Validate file type and size
    //     ]);

    //     // Create the Order
    //     $order = Order::create([
    //         'name' => $request->name,
    //         'email' => $request->email,
    //         'phone' => $request->phone,
    //         'address' => $request->address,
    //         'postal_code' => $request->postal_code,
    //         'commune' => $request->commune,
    //     ]);

    //     // Ensure files exist before processing
    //     if ($request->hasFile('files')) {
    //         foreach ($request->file('files') as $index => $file) {
    //             $filename = $file->store('uploads', 'public'); // Save file

    //             // Save file details in database
    //             File::create([
    //                 'order_id' => $order->id,
    //                 'file_name' => $file->getClientOriginalName(),
    //                 'num_pages' => $request->input("files.$index.num_pages", 1), // Default to 1 if not set
    //                 'paper_size' => $request->input("files.$index.paper_size"),
    //                 'impression' => $request->input("files.$index.impression"),
    //                 'orientation' => $request->input("files.$index.orientation"),
    //                 'front_back' => $request->input("files.$index.front_back", false),
    //                 'binding' => $request->input("files.$index.binding", false),
    //                 'file_path' => $filename,
    //             ]);
    //         }
    //     }

    //     return redirect()->back()->with('success', 'Order placed successfully!');
    // }
    // public function store(Request $request)
    // {
    //     $request->validate([
    //         'name' => 'required|string',
    //         'email' => 'required|email',
    //         'phone' => 'required|string',
    //         'address' => 'required|string',
    //         'code_postal' => 'required|string',
    //         'commune' => 'required|string',
    //         'files' => 'required|array',
    //         'files.*.num_pages' => 'required|integer',
    //         'files.*.size' => 'required|string',
    //         'files.*.impression' => 'required|string',
    //         'files.*.orientation' => 'required|string',
    //     ]);

    //     // Create Order
    //     $order = Order::create([
    //         'name' => $request->name,
    //         'email' => $request->email,
    //         'phone' => $request->phone,
    //         'address' => $request->address,
    //         'code_postal' => $request->code_postal,
    //         'commune' => $request->commune,
    //         'billing_same' => $request->billing_same ?? 0,
    //     ]);

    //     // Process each uploaded file
    //     foreach ($request->file('files', []) as $index => $file) {
    //         $path = $file->store('public/uploads'); // Saves in storage/app/public/uploads

    //         File::create([
    //             'order_id' => $order->id,
    //             'file_path' => $path,
    //             'file_name' => $file->getClientOriginalName(),
    //             'num_pages' => $request->input("files.{$index}.num_pages"),
    //             'size' => $request->input("files.{$index}.size"),
    //             'impression' => $request->input("files.{$index}.impression"),
    //             'orientation' => $request->input("files.{$index}.orientation"),
    //             'front_back' => $request->input("files.{$index}.front_back", 0),
    //             'binding' => $request->input("files.{$index}.binding", 0),
    //         ]);
    //     }

    //     return response()->json(['message' => 'Order submitted successfully!']);
    // }
    public function store(Request $request)
    {
        // ✅ Validate incoming request
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email',
            'phone' => 'required|string',
            'address' => 'required|string',
            'code_postal' => 'required|string',
            'commune' => 'required|string',
            'files' => 'required|array',
            'files.*.num_pages' => 'required|integer',
            'files.*.size' => 'required|string',
            'files.*.impression' => 'required|string',
            'files.*.orientation' => 'required|string',
        ]);

        // ✅ Create the order in the database
        $order = Order::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'code_postal' => $request->code_postal,
            'commune' => $request->commune,
            'billing_same' => $request->billing_same ?? 0,
        ]);

        // ✅ Process each uploaded file
        $uploadedFiles = $request->file('files'); // Gets the uploaded files
        $fileDetails = $request->input('files', []); // Gets the file details (num_pages, size, etc.)

        foreach ($fileDetails as $index => $fileData) {
            if (!isset($uploadedFiles[$index])) {
                return response()->json(['error' => "Missing file for index $index"], 400);
            }

            $uploadedFile = $uploadedFiles[$index]['file']; // Get the uploaded file instance

            // ✅ Store the file and get the path
            $path = $uploadedFile->store('public/uploads');

            // ✅ Store file details in the database
            File::create([
                'order_id' => $order->id,
                'file_path' => $path,
                'file_name' => $uploadedFile->getClientOriginalName(),
                'num_pages' => $fileData['num_pages'] ?? 1,
                'size' => $fileData['size'] ?? 'A4',
                'impression' => $fileData['impression'] ?? 'black_white',
                'orientation' => $fileData['orientation'] ?? 'portrait',
                'front_back' => $fileData['front_back'] ?? 0,
                'binding' => $fileData['binding'] ?? 0,
            ]);
        }

        return response()->json(['message' => 'Order submitted successfully!', 'order_id' => $order->id]);
    }
}
