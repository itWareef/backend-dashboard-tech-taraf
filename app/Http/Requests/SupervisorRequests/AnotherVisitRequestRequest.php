<?php

namespace App\Http\Requests\SupervisorRequests;

use App\Models\Requests\SuperVisorRequests;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AnotherVisitRequestRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'reason' => 'required|string',
        ];
    }
    public function messages()
    {
        return [
            'reason.required' => 'سبب الطلب مطلوب.',
            'reason.string'   => 'السبب يجب أن يكون نصاً.',
        ];
    }



}
