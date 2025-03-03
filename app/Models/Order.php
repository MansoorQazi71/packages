<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
        'postal_code',
        'commune',
        'activity',
        'billing_same_as_shipping',
        'grand_total',
        'payment_method',
        'is_paid',
        'impression',
        'orientation',
        'paper_size',
        'front_back',
        'need_binding',
        'name',
        'email',
        'phone',
        'address',
        'code_postal',
        'commune',
        'billing_same',
        'grand_total',
    ];
    public function files()
    {
        return $this->hasMany(File::class);
    }
}
