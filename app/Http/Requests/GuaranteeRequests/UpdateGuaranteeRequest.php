<?php

namespace App\Http\Requests\GuaranteeRequests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateGuaranteeRequest extends FormRequest
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
            'name' => ['required_with:name','string','max:255',],
            'duration' => ['required_with:name','string','max:255',],
            'picture' => ['required_with:picture',],
            'supplier_id' => ['required_with:supplier_id','exists:suppliers,id'],
        ];
    }
}
