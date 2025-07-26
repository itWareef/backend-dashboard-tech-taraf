<?php

namespace App\Http\Requests\ProjectRequests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProjectRequest extends FormRequest
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
            'developer_id' => ['required_with:developer_id','exists:developers,id'],
            'place' => ['required_with:place','string','max:255',],
            'guarantees' => ['array'],
            'guarantees.*' => ['required','exists:guarantees,id'],
        ];
    }
}
