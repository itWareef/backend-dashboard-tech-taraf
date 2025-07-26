<?php

namespace App\Http\Requests\SupplierRequests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSupplierRequest extends FormRequest
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
        $developerId = $this->route('supplier')->id;

        return [
            'name'         => 'required_with:name|string|max:255',
            'email'        => 'required_with:email|email|max:255|unique:suppliers,email,' . $developerId,
            'cr_no' => 'required_with:cr_no|string|max:255',
            'phone'   => 'required_with:phone|string|max:255|unique:suppliers,phone,' . $developerId,
        ];
    }
}
