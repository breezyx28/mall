<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductsPhoto extends Model
{
    use HasFactory;

    protected $table = 'products_photos';

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function getPhotoAttribute($value)
    {
        // replace http://localhost to by htpp://127.0.0.1
        // $base_url = str_replace('localhost', env('DB_HOST'), env('APP_URL'));

        // return $base_url . ':' . $_SERVER['SERVER_PORT'] . "/storage/" . $value;
        return config('const.immages_space_path') . $value;
    }
}
