<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUnitRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'project_name'   => 'required|string|max:255',
            'developer_name' => 'required|string|max:255',
            'unit_no'        => 'required|string|max:100',
            'space'          => 'required|string|max:100',
            'deed_number'    => 'required|string|max:100',
            'date'           => 'required|date',
        ];
    }
}
