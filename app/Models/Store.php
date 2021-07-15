<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Store extends Model
{
    use HasFactory;
    protected $table = 'stores';

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getThumbnailAttribute($value)
    {
        // replace http://localhost to by htpp://127.0.0.1
        // $base_url = str_replace('localhost', env('DB_HOST'), env('APP_URL'));

        // return $base_url . ':' . $_SERVER['SERVER_PORT'] . "/storage/" . $value;
        return config('const.immages_space_path') . $value;
    }
}
