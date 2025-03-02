<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\File;
use Smalot\PdfParser\Parser; // For PDF analysis

class FileController extends Controller
{
    public static function uploadFile($file, $order_id)
    {
        // Store File
        $path = $file->store('uploads');

        // Extract file details
        $number_of_pages = self::getNumberOfPages($file);
        $impression = request('impression');
        $orientation = request('orientation');
        $paper_size = request('paper_size');
        $front_back = request('front_back', false);
        $binding = request('binding', false);
        $copies = request('copies', 1);

        // Calculate Price if price module is enabled
        $price = Setting::getValue('price_module') == 'on' ? self::calculatePrice($number_of_pages, $copies, $impression) : null;

        // Save File Details
        File::create([
            'order_id' => $order_id,
            'file_path' => $path,
            'number_of_pages' => $number_of_pages,
            'impression' => $impression,
            'orientation' => $orientation,
            'paper_size' => $paper_size,
            'front_back' => $front_back,
            'binding' => $binding,
            'copies' => $copies,
            'price' => $price
        ]);
    }

    private static function getNumberOfPages($file)
    {
        if ($file->getClientOriginalExtension() == 'pdf') {
            $parser = new Parser();
            $pdf = $parser->parseFile($file->getPathname());
            return count($pdf->getPages());
        }
        return null;
    }

    private static function calculatePrice($pages, $copies, $impression)
    {
        $base_price = 0.10; // Example base price per page
        $color_multiplier = $impression == 'color' ? 1.5 : 1;
        return $pages * $copies * $base_price * $color_multiplier;
    }
}
