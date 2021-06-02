<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $data  = [
        //     'awdawdad'
        // ];

        // foreach ($data as $item) {
        //     DB::table('categories')->insert([
        //         'department' => 'ملابس رياضية',
        //         'name' => 'إكسسوارات رياضية',
        //         'subCategory' => $item,
        //         'cat_img' => 'https://laravelstorage.sgp1.digitaloceanspaces.com/Category/AfHHV82gim6zksubVHHKreSw836ThDKQOSqMfj20.jpg',
        //         'created_at' => Carbon::now()->toDateTimeString(),
        //         'updated_at' => Carbon::now()->toDateTimeString(),
        //     ]);
        // }
    }
}
