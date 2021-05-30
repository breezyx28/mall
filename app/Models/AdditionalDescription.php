<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdditionalDescription extends Model
{
    use HasFactory;

    protected $casts = [
        'color' => 'array',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
