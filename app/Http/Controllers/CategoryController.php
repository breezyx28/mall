<?php

namespace App\Http\Controllers;

use App\Helper\ResponseMessage as Resp;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function categoryList(Request $request)
    {
        $validate = (object) $request->validate([
            'groupBy' => 'required|string'
        ], [
            'groupBy' => 'هذا الحقل متوفر'
        ]);

        $data = \App\Models\Category::all()->groupBy($validate->groupBy);

        return Resp::Success('ok', $data);
    }

    public function getCategories()
    {
        $category = new \App\Models\Category();

        // bring all records
        $all = $category::where('status', 1)->get();

        // unique category array
        $group = $all->groupBy('name');

        // map through category and check if it has sub category
        $data = $group->map(function ($item, $key) {
            if (count($item) > 0) {
                return [
                    'name' => $key,
                    'img' => $item[0]['cat_img'],
                    'hasSub' => true
                ];
            } else {
                return [
                    'name' => $key,
                    'img' => $item[0]['cat_img'],
                    'hasSub' => false
                ];
            }
        });

        $arr = [];
        foreach ($data as $key => $value) {

            $arr[] = $value;
        }

        return Resp::Success('تم بنجاح', $arr);
    }

    public function getSubCategories(Request $request)
    {

        $category = new \App\Models\Category();

        $vaildate = (object) $request->validate([
            'categoryName' => 'required|exists:categories,name'
        ], [
            'categoryName.required' => 'اسم الصنف مطلوب',
            'categoryName.exists' => 'اسم الصنف غير موجود في السجلات',
        ]);

        $data = $category::where('name', $vaildate->categoryName)->get();

        return Resp::Success('تم بنجاح', $data);
    }
}
