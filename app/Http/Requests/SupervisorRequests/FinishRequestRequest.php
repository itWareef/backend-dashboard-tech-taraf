<?php

namespace App\Http\Requests\SupervisorRequests;

use App\Models\Requests\MaintenanceRequest;
use App\Models\Requests\SuperVisorRequests;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class FinishRequestRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'status' => 'required|string|'.Rule::in(MaintenanceRequest::STATUSES),
            'picture' => 'required|',
        ];
    }
    public function messages()
    {
        return [
            'status.required' => 'حقل الحالة مطلوب.',
            'picture.required' => 'حقل الصورة مطلوب.',
            'status.string'   => 'قيمة الحالة يجب أن تكون نصاً.',
            'status.in'       => 'قيمة الحالة غير صحيحة.',
        ];
    }


}
