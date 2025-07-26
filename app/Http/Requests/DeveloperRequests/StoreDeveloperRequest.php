<?php

namespace App\Http\Requests\DeveloperRequests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDeveloperRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'picture'      => 'required',
            'name'         => 'required|string|max:255',
            'email'        => 'required|email|max:255|unique:developers,email',
            'bank_account' => 'required|string|max:255',
            'tax_number'   => 'required|string|max:255|unique:developers,tax_number',
        ];
    }
}
