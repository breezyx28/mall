<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SearchKeys extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $table = 'search_keys';
    protected $fillable = ['key_word', 'product_id'];
}
