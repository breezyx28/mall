<?php

namespace App\Http\Controllers;

use App\Helper\ResponseMessage as Resp;
use App\Models\Invoice;
use Illuminate\Http\Request;

class InvoiceControllerResource extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $invoices = \App\Models\Invoice::all();
        return Resp::Success('تم', $invoices);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Invoice  $Invoice
     * @return \Illuminate\Http\Response
     */
    public function show(Invoice $Invoice)
    {
        return Resp::Success('تم بنجاح', $Invoice);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Invoice  $Invoice
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Invoice $Invoice)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Invoice  $Invoice
     * @return \Illuminate\Http\Response
     */
    public function destroy(Invoice $Invoice)
    {
        //
    }
}
