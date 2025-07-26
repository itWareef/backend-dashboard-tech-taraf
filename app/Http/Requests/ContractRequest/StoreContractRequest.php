<?php

namespace App\Http\Requests\ContractRequest;

use Illuminate\Foundation\Http\FormRequest;

class StoreContractRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'developer'        => 'nullable|string|max:255',
            'project'          => 'nullable|string|max:255',
            'property_type'    => 'required|string|in:دور,فيلا,شقة,بنتهاوس,تاون هاوس,عمارة سكنية',
            'property_age'     => 'required|string|max:100',
            'area'             => 'required|string|max:100',
            'unit_number'      => 'required|string|max:100',
            'ownership_number' => 'required|string|max:100',
            'location'  => 'required|string|max:255',
            'contract_type'    => 'required|array|max:255',
        ];
    }
}
