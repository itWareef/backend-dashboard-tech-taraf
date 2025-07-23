<?php

// app/Http/Requests/Requests/StoreMaintenanceRequest.php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Requests\MaintenanceRequest;

class GardenRequestStoringRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'unit_type' => 'required|string|max:255',
            'space'     => 'required|string|max:100',
            'longitude'  => 'required|string|max:255',
            'latitude'  => 'required|string|max:255',
            'visit_type'=> 'required|in:once,annually',
            'notes'     => 'nullable|string|max:1000',
            'action'    => 'nullable|string|max:255',
        ];
    }
}
