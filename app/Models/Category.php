<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    public function product()
    {
        return $this->hasMany(Product::class);
    }

    public function getCatImgAttribute($value)
    {
        if ($value) {

            // replace http://localhost to by htpp://127.0.0.1
            // $base_url = str_replace('localhost', env('DB_HOST'), env('APP_URL'));

            // return $base_url . ':' . $_SERVER['SERVER_PORT'] . "/storage/" . $value;

            return 'https://laravelstorage.sgp1.digitaloceanspaces.com/' . $value;
        }
    }

    public function getSubImgAttribute($value)
    {
        if ($value) {

            // // replace http://localhost to by htpp://127.0.0.1
            // $base_url = str_replace('localhost', env('DB_HOST'), env('APP_URL'));

            // return $base_url . ':' . $_SERVER['SERVER_PORT'] . "/storage/" . $value;
            return 'https://laravelstorage.sgp1.digitaloceanspaces.com/' . $value;
        }
    }
}
