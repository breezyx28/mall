<?php

namespace App\Http\Controllers;

use App\Helper\ResponseMessage as Resp;
use App\Helper\ProducsDetails as Details;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProductController extends Controller
{
    public function topProduct(Request $request)
    {
        $validate = (object) $request->validate([
            'top' => ['required', Rule::in(['sells', 'rate', 'discount'])],
            'limit' => 'integer'
        ]);

        $orders = DB::table('orders');
        $products = new \App\Models\Product();

        $top = [
            'sells' => function () use ($orders, $validate) {
                return $orders
                    ->select('product_id')->groupBy('product_id')
                    ->take(isset($validate->limit) ? $validate->limit : 10)
                    ->pluck('product_id');
            },
            'rate' => $products->whereHas('rate', function ($q) use ($validate) {
                $q->select('rate')->limit(isset($validate->limit) ? $validate->limit : 10)->orderBy('rate');
            })->get(),
            'discount' => (function () use ($products, $validate) {
                return $products->where('status', 1)->limit(isset($validate->limit) ? $validate->limit : 10)->orderBy('price', 'desc')->get();
            }),
        ][$validate->top];

        return Resp::Success('تم', $top);
    }

    public function todayProducts(Request $request)
    {
        $validate = (object) $request->validate([
            'column' => 'string|max:191',
            'exp' => 'string|min:0,max:5',
            'value' => 'string|max:191',
            'limit' => 'required|integer'
        ]);

        if (isset($validate->column) || isset($validate->value)) {

            $switch = [
                '0' => '=',
                '1' => '>',
                '2' => '<',
                '3' => '>=',
                '4' => '<=',
                '5' => 'like'
            ][$validate->exp];

            $data = \App\Models\Product::with('category', 'store')->where($validate->column, $switch, $validate->value)->limit(isset($validate->limit) ? $validate->limit : 10)->orderBy('updated_at', 'desc')->get();
            return Resp::Success('تم', $data);
        } else {
            $data = \App\Models\Product::with('category', 'store')->where('status', 1)->limit($validate->limit)->orderBy('updated_at', 'desc')->get();
            return Resp::Success('تم', $data);
        }
    }

    public function getProducts(Request $request)
    {
        $validate = (object) $request->validate([
            'productsIDs' => 'required|array'
        ]);

        try {
            //code...
            $all = \App\Models\Product::with(['category', 'store.store', 'product_photos', 'additional_description', 'product_sizes'])->find($validate->productsIDs);
            return Resp::Success('تم', $all);
        } catch (\Throwable $th) {
            //throw $th;
            return Resp::Error('حدث خطأ ما', $th->getMessage());
        }
    }

    public function productsWith(Request $request)
    {
        $validate = (object) $request->validate([
            'category' => 'string',
            'subCategory' => 'string',
            'department' => 'string'
        ]);

        if (isset($validate->category) && !isset($validate->subCategory)) {
            $filtered = \App\Models\Product::whereHas('category', function ($query) use ($validate) {
                $query->where('name', $validate->category);
            })->with('category', 'store.store', 'rate', 'product_photos', 'additional_description', 'product_sizes')->get();

            return Resp::Success('تم', Details::details($filtered));
        }

        if (isset($validate->subCategory) && !isset($validate->category)) {
            $filtered = \App\Models\Product::whereHas('category', function ($query) use ($validate) {
                $query->where('subCategory', '=', $validate->subCategory);
            })->with(['category', 'store.store', 'rate', 'product_photos', 'additional_description', 'product_sizes'])->get();

            return Resp::Success('تم', Details::details($filtered));
        }

        if (isset($validate->department) && !isset($validate->category) && !isset($validate->subCategory)) {
            $filtered = \App\Models\Product::whereHas('category', function ($query) use ($validate) {
                $query->where('department', $validate->department);
            })->with(['category', 'store.store', 'rate', 'product_photos', 'additional_description', 'product_sizes'])->get();

            return Resp::Success('تم', Details::details($filtered));
        }

        if (isset($validate->subCategory) && isset($validate->category)) {

            try {
                //code...
                $all = \App\Models\Product::whereHas('category', function ($q) use ($validate) {
                    $q->where(['name' => $validate->category, 'subCategory' => $validate->subCategory]);
                })
                    ->get();
                $all->load('category', 'store.store', 'rate', 'product_photos', 'additional_description', 'product_sizes');
                return Resp::Success('تم', Details::details($all));
            } catch (\Throwable $th) {
                //throw $th;
                return Resp::Error('حدث خطأ ما', $th->getMessage());
            }
        }

        if (!isset($validate->subCategory) && !isset($validate->category) && !isset($validate->department)) {

            try {
                //code...
                $all = \App\Models\Product::with('category', 'store.store', 'rate', 'product_photos', 'additional_description', 'product_sizes')->get();
                return Resp::Success('تم', Details::details($all));
            } catch (\Throwable $th) {
                //throw $th;
                return Resp::Error('حدث خطأ ما', $th->getMessage());
            }
        }
    }

    public function randomProducts()
    {
        $data = Product::inRandomOrder()->limit(3)->get();

        return Resp::Success('ok', $data);
    }

    public function suggestions()
    {
        $cat = new \App\Models\Category();
        $prod = new Product();

        // get random sub categories
        $randomCat = $cat::inRandomOrder()->limit(5)->get()->groupBy('name');

        $arr = [];
        foreach ($randomCat as $key => $value) {

            // bring products of this category Limit 100
            $catProd = $prod::whereHas('category', function ($q) use ($key) {
                $q->where('name', $key);
            })->limit(100)->get();

            //the category
            $catName = $key;
            // three images
            $threeImgs = [];
            // return Resp::Success('ok', $catProd->random(3));
            $randImgs = ($catProd->isNotEmpty() && ($catProd->count() > 2)) ? $catProd->random(3) : [];
            foreach ($randImgs as $key => $value) {
                $threeImgs[] = $value->photo;
            }

            // important product (top rated)
            // $max = $catProd->max('rate.*.rate');
            $topProd = $catProd->sortByDesc('rate.*.rate')->values()->all();

            // convert $topProd to collection
            $col = collect($topProd)->slice(1, 5)->values();
            $arr[] = [
                'watchAll' => $catName,
                'imgs' => $threeImgs,
                'topProduct' => $col->map(function ($item, $key) {
                    return [
                        'id' => $item->id,
                        'photo' => $item->photo,
                        // 'price' => $item->price,
                        // 'discounted_price' => $item->final_price
                    ];
                })
            ];
        }


        return Resp::Success('تم', $arr);
    }

    public function topSuggestions()
    {
        $cat = new \App\Models\Category();
        $prod = new Product();

        // get random sub categories
        $randomCat = $cat::inRandomOrder()->limit(1)->get()->groupBy('name');

        $arr = [];
        foreach ($randomCat as $key => $value) {

            // bring products of this category Limit 100
            $catProd = $prod::whereHas('category', function ($q) use ($key) {
                $q->where('name', $key);
            })->limit(10)->get();

            //the category
            $catName = $key;
            // three images
            $sliderImgs = [];

            $randImgs = $catProd->isNotEmpty() ? $catProd : [];
            foreach ($randImgs as $key => $value) {
                $sliderImgs[] = $value->photo;
            }

            // important product (top rated)
            $silderProduct = $catProd->sortByDesc('rate.*.rate')->values()->all();

            // convert $silderProduct to collection
            $col = collect($silderProduct)->slice(1, 10)->values();
            $arr[] = [
                'category' => $catName,
                'slider' => $sliderImgs,
                'products' => $col->map(function ($item, $key) {
                    return [
                        'id' => $item->id,
                        'photo' => $item->photo,
                        'price' => $item->price,
                        'discounted_price' => $item->final_price
                    ];
                })
            ];
        }


        return Resp::Success('تم', $arr);
    }

    public function webSuggestions()
    {
        $cat = new \App\Models\Category();
        $prod = new Product();

        // get random sub categories
        $dept = $cat::distinct('department')->pluck('department')->all();
        $randDept = collect($dept)->shuffle()->take(5);
        $randomDep = $cat::whereIn('department', $randDept)->get()->groupBy('department');

        $arr = [];
        foreach ($randomDep as $key => $value) {
            // bring products of this category Limit 100
            $catProd = $prod::whereHas('category', function ($q) use ($key) {
                $q->where('department', $key);
            })->limit(100)->get();

            //the category
            $depName = $key;

            // important product (top rated)
            $topProd = $catProd->sortByDesc('rate.*.rate')->take(10);

            // convert $topProd to collection
            $col = collect($topProd);
            $arr[] = [
                'department' => $depName,
                'randomProducts' => $col->map(function ($item, $key) {
                    return [
                        'id' => $item->id,
                        'name' => $item->name,
                        'note' => $item->note,
                        'description' => $item->description,
                        'photo' => $item->photo,
                        'price' => $item->price,
                        'discounted_price' => $item->final_price
                    ];
                })
            ];
        }


        return Resp::Success('تم', $arr);
    }

    public function initItems()
    {
        // $validate = (object) $request->validated();

        $result = \App\Models\Product::with('category', 'store', 'product_sizes', 'additional_description')->get();

        // if there is a result
        if ($result->isEmpty()) {
            return Resp::Error('لا توجد نائج', $result);
        }

        // list of filter properties
        $productsPropertyList = [
            'color' => $result->whereNotNull('additional_description.color')->values()->pluck('additional_description.color'),
            'countryOfMade' => $result->whereNotNull('additional_description.countryOfMade')->values()->pluck('additional_description.countryOfMade'),
            'company' => $result->whereNotNull('additional_description.company')->values()->pluck('additional_description.company'),
            'weight' => $result->whereNotNull('additional_description.weight')->values()->pluck('additional_description.weight'),
            'expireDate' => $result->whereNotNull('additional_description.expireDate')->values()->pluck('additional_description.expireDate'),
            'price' => ['from' => $result->min('price'), "to" => $result->max('price')],
            'rate' => collect($result->whereNotNull('rate.*.rate')->pluck('rate.*.rate'))->filter(function ($value, $key) {
                return !empty($value);
            })->collapse(),
        ];

        // return final result
        return Resp::Success('تم', [
            'productsPropertyList' => $productsPropertyList,
            'result' => $result->values()->all()
        ]);
    }
}
