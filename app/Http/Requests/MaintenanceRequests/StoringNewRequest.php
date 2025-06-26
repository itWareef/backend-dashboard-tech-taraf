<?php

namespace App\Http\Requests\MaintenanceRequests;

use Illuminate\Foundation\Http\FormRequest;

class StoringNewRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'unit_id' => ['required', 'exists:units,id'],
            'project_id' => ['required', 'exists:projects,id'],
            'date' => ['required', 'date'],
            'picture'   => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg'],
            'notes'     => ['nullable', 'string'],
            'time'      => ['required', 'string'],
        ];
    }
    public function messages()
    {
        return [
            'unit_id.required' => 'يجب تحديد الوحدة.',
            'project_id.required' => 'يجب تحديد المشروع.',
            'date.required' => 'يجب إدخال التاريخ.',
            'picture.image' => 'الملف المرفق يجب أن يكون صورة.',
            'time.required' => 'يرجى تحديد الوقت.',
        ];
    }

}
