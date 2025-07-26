<?php

namespace App\Http\Requests\ProjectRequests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProjectRequest extends FormRequest
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
            'developer_id' => ['required','exists:developers,id'],
            'place' => ['required','string','max:255',],
            'guarantees' => ['array'],
            'guarantees.*' => ['required','exists:guarantees,id'],
        ];
    }
}
