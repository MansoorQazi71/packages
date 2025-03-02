<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'email', 'phone', 'address', 'postal_code', 'commune', 
        'activity', 'billing_same_as_shipping', 'grand_total', 'is_paid'
    ];

    public function files()
    {
        return $this->hasMany(File::class);
    }
}
