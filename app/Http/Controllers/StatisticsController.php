<?php

namespace App\Http\Controllers;

use App\Helper\ResponseMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StatisticsController extends Controller
{

    private $response = [], $orders = ['all', 'available', 'accepted', 'delivered', 'rejected'], $verify = ['verified', 'unVerified'];
    public $mostSell, $topDiscount, $bestOffers, $topCategory, $topRate, $popular, $latest, $lowPrice, $highPrice;
    protected $user = null;

    public function __construct()
    {
        $this->user = auth()->user();
    }

    public function statistics()
    {
        $this->response['allUsers'] = DB::table('users')->count();
        $this->response['allStates'] = DB::table('states')->count();
        $this->response['allStores'] = DB::table('stores')->count();
        $this->response['allCategories'] = DB::table('categories')->count();
        $this->response['allMaterials'] = DB::table('materials')->count();
        $this->response['allSizes'] = DB::table('sizes')->count();

        $this->response['orders']['all'] = DB::table('orders')->count();
        $this->response['orders']['available'] = DB::table('orders')->where('status', 'available')->count();
        $this->response['orders']['accepted'] = DB::table('orders')->where('status', 'accepted')->count();
        $this->response['orders']['delivered'] = DB::table('orders')->where('status', 'delivered')->count();
        $this->response['orders']['rejected'] = DB::table('orders')->where('status', 'rejected')->count();

        $this->response['allAds'] = DB::table('ads')->count();
        $this->response['allInvoices'] = DB::table('invoices')->count();
        $this->response['allBillingAccounts'] = DB::table('accounts')->count();
        $this->response['allRate'] = DB::table('rates')->count();
        $this->response['allCoupons'] = DB::table('coupons')->count();

        $this->response['verifications']['verified'] = DB::table('verifications')->where('verified', 1)->count();
        $this->response['verifications']['unVerified'] = DB::table('verifications')->where('verified', 0)->count();

        return ResponseMessage::Success('الإحصائيات', $this->response);
    }

    public function productsFilter(Request $request)
    {
        $validate = (object) $request->validate([
            'category' => 'required|string|exists:categories,name',
            'subCategory' => 'string',
            // 'column' => 'string',
            // 'value' => 'required_if:column|array',
        ]);

        if (isset($validate->subCategory)) {
            $all = \App\Models\Category::with('product')->where(['name' => $validate->category, 'subCategory' => $validate->subCategory])->paginate();
        } else {
            $all = \App\Models\Category::with('product')->where('name', $validate->category)->paginate();
        }

        return ResponseMessage::Success('تم', $all);
    }
}
