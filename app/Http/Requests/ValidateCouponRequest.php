<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ValidateCouponRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // عادةً المستخدم
    }

    public function rules(): array
    {
        return [
            'code' => 'required|string|exists:coupons,code',
        ];
    }
}
