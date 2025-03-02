<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id', 'file_path', 'number_of_pages', 'copies', 'impression', 
        'orientation', 'paper_size', 'front_back', 'binding', 'price'
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
