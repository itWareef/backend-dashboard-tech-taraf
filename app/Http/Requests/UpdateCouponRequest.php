<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCouponRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // تأكد من التحقق من صلاحيات الأدمن إذا لزم
    }

    public function rules(): array
    {
        return [
            'code' => 'required|string|unique:coupons,code',
            'discount_percentage' => 'required|numeric|min:1|max:100',
            'start_date' => 'required|date|before_or_equal:end_date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'type_permission' => 'required|in:new_visitors,contracting_clients,non_contracting_clients',
        ];
    }

}
