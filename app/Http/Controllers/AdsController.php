<?php

namespace App\Http\Controllers;

use App\Helper\ResponseMessage as Resp;
use Illuminate\Http\Request;

class AdsController extends Controller
{
    public function AdsOptions(Request $request)
    {
        $validate = (object) $request->validate([
            'limit' => 'integer',
        ]);

        $all = \App\Models\Ad::with('category', 'product')->where('status', 1)->limit($validate->limit)->orderBy('updated_at', 'desc')->get();
        return Resp::Success('تم', $all);
    }

    public function AdsGroupBy(Request $request)
    {
        $validate = (object) $request->validate([
            'groupBy' => 'string|max:191',
            'limit' => 'integer',
        ]);

        $all = \App\Models\Ad::with('category', 'product')->where('status', 1)->limit($validate->limit)->groupBy($validate->groupBy)->get();
        return Resp::Success('تم', $all);
    }
}
