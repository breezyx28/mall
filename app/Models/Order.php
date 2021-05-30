<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    // protected $appends = 'total_price';

    public function orderNumber()
    {
        return $this->belongsTo(OrdersNumber::class, 'orders_number_id', 'id');
    }

    public function state()
    {
        return $this->belongsTo(State::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // public function setTotalPriceAttribute()
    // {
    //     $total = $this->product->final_price * $this->attributes['amount'];
    //     $this->attributes['total_price'] = $total;
    //     return $total;
    // }
}
