<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AdsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        DB::table('ads')->insert([
            'title' => '',
            'content' => '',
            'photo' => '',
            'expireDate' => '',
            'publishDate' => ''
        ]);
    }
}
