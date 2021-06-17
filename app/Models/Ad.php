<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ad extends Model
{
    use HasFactory;

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function getphotoAttribute($value)
    {
        if ($value) {

            // replace http://localhost to by htpp://127.0.0.1
            // $base_url = str_replace('localhost', env('DB_HOST'), env('APP_URL'));

            // return $base_url . ':' . $_SERVER['SERVER_PORT'] . "/storage/" . $value;

            return 'https://laravelstorage1.sgp1.digitaloceanspaces.com/' . $value;
        }
    }
}
