<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBrandRequest extends FormRequest
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
            'name'        => 'required|string|max:255',
            'picture'     => 'required_with:picture',
            'description' => 'required|string',
            'price'       => 'required|numeric|min:0',
            'section_id'  => 'required|exists:sections,id',
            'features' => 'nullable|array',
            'features.*' => 'exists:features,id',

        ];
    }
}
