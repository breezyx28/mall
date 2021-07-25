<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Helper\ValidateArray;

class ProductSizes extends Model
{
    use HasFactory;

    protected $table = 'product_sizes';

    protected $casts = [
        'sizes_array' => 'array',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
