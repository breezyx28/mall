<?php

namespace App\Http\Controllers;

use App\Helper\ResponseMessage as Resp;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function categoryList(Request $request)
    {
        $validate = (object) $request->validate([
            'groupBy' => 'required|string',
            'limit' => 'numeric'
        ], [
            'groupBy' => 'هذا غير الحقل متوفر',
            'limit' => 'يجب ان يكون العنصر ال limit رقمي',
        ]);

        $data = \App\Models\Category::all()->groupBy($validate->groupBy)->take($validate->limit);

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

    public function ourNew($request)
    {
        $validate = (object) $request->validate([
            'limit' => 'integer',
            'days' => 'integer',
        ]);

        // bring all new products
        $data = \App\Models\Product::where('created_at', '>', \Carbon\Carbon::now()->subDays($validate->days ?? 30))->limit($validate->limit ?? 10)->get();

        return Resp::Success('تم', $data);
    }

    public function latest(int $limit)
    {
        // bring all latest products
        $data = \App\Models\Product::orderBy('id', 'DESC')->limit($limit)->first();

        return Resp::Success('تم', $data);
    }

    public function tempOffers($request)
    {
        $validate = (object) $request->validate([
            'limit' => 'integer',
        ]);

        $data = \App\Models\Product::where('discount', '>', 0)->limit($validate->limit ?? 10)->groupBy('discount');

        return Resp::Success('تم', $data);
    }
}
