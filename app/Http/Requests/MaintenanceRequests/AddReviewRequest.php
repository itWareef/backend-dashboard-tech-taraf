<?php

namespace App\Http\Requests\MaintenanceRequests;

use Illuminate\Foundation\Http\FormRequest;

class AddReviewRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'rating' => 'required|numeric|between:1,5',
        ];
    }
    public function messages()
    {
        return [
            'rating.required' => 'التقييم مطلوب',
            'rating.between' => 'يجب أن يكون التقييم بين 1 و 5',
            'rating.numeric' => 'يجب أن يكون التقييم رقماً',
        ];
    }

}
