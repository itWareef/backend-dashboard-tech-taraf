<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OrderRequest extends FormRequest
{
    public function rules()
    {
        return [
            'customer_name' => 'required|string|max:255',
            'email'         => 'nullable|email',
            'phone'         => 'required|string|max:20',
            'address'       => 'required|string|max:500',
            'unit'          => 'nullable|string|max:255',
            'location_map'  => 'nullable|string|max:255',
            'coupon_code'   => 'nullable|string|exists:coupons,code',
        ];
    }

    public function authorize()
    {
        return true;
    }
}
