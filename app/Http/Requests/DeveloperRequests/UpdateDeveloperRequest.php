<?php

namespace App\Http\Requests\DeveloperRequests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateDeveloperRequest extends FormRequest
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
        $developerId = $this->route('developer')->id;

        return [
            'name'         => 'required_with:name|string|max:255',
            'email'        => 'required_with:email|email|max:255|unique:developers,email,' . $developerId,
            'bank_account' => 'required_with:bank_account|string|max:255',
            'tax_number'   => 'required_with:tax_number|string|max:255|unique:developers,tax_number,' . $developerId,
        ];
    }
}
