<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TrackingUserController extends Controller
{
    public function trackingCategory()
    {
        $user = auth()->user();

        // track what user like

        // \App\Models\Invoice::whereHas('user',function($query){
        //     $query->
        // })->get();
    }
}
