<?php

namespace App\Http\Requests\SupplierRequests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSupplierRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required','string','max:255',],
            'email' => ['required','string','max:255','email:rfc,dns','unique:suppliers,email'],
            'phone' => ['required','string','max:255','unique:suppliers,phone'],
            'cr_no' => ['required','string','max:255',]
        ];
    }
}
