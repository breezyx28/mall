<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Log;

class AmountRule implements Rule
{
    private $error = ['code' => false];
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $inventory = \App\Models\Product::find(request()->input('orders'));
        $orders = request()->input('orders');
        foreach ($orders as $key => $val) {

            $prod = \App\Models\Product::find($orders[$key]['product_id']);
            if ($prod->inventory < $value) {
                $this->error['code'] = true;
                $this->error['id'] = $orders[$key]['product_id'];
                $this->error['available'] = $prod->inventory;
                return 0;
            }
        }

        if ($this->error['code'] === false) {
            return true;
        }
        return false;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return "كمية المنتج رقم {$this->error['id']} أكبر من الموجودة في المتجر ... الكمية المتوفرة {$this->error['available']}";
    }
}
