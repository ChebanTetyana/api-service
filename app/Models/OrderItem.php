<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $fillable = [
        'order_id',
        'product_id',
        'rental_expiration',
        'unique_code'
    ];

    public function product()
    {
        $this->belongsTo(Product::class);
    }

    public function order()
    {
        $this->belongsTo(Order::class);
    }
}
