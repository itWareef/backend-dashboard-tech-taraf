<?php

// app/Http/Requests/Requests/StoreMaintenanceRequest.php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Requests\MaintenanceRequest;

class StoreMaintenanceRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'unit'         => 'required|string|max:255',
            'category_id'  => 'required|exists:categories,id',
            'date'         => 'required|date',
            'picture'      => 'nullable|image|max:2048',
            'time'         => 'nullable|string|max:50',
            'phone'        => 'nullable|string|max:20',
            'otp'          => 'nullable|string|max:10',
            'notes'        => 'nullable|string',
            'rating'       => 'nullable|numeric|min:0|max:5',
            'visits_count' => 'nullable|integer|min:0',
            'status'       => 'nullable|in:' . implode(',', MaintenanceRequest::STATUSES),
        ];
    }
}
