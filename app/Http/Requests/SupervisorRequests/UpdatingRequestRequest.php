<?php

namespace App\Http\Requests\SupervisorRequests;

use App\Models\Requests\SuperVisorRequests;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatingRequestRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'status' => 'required|string|'.Rule::in(SuperVisorRequests::STATUES),
        ];
    }
    public function messages()
    {
        return [
            'status.required' => 'حقل الحالة مطلوب.',
            'status.string'   => 'قيمة الحالة يجب أن تكون نصاً.',
            'status.in'       => 'قيمة الحالة غير صحيحة.',
        ];
    }


}
