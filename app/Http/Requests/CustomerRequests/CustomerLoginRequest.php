<?php

namespace App\Http\Requests\CustomerRequests;

use Illuminate\Foundation\Http\FormRequest;

class CustomerLoginRequest extends FormRequest
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
            'email'    => ['required', 'email' , 'exists:customers,email'],
            'password' => ['required' ]
        ];
    }
    public function messages()
    {
        return [
            'email.required'  => 'البريد الإلكتروني مطلوب.',
            'email.email'     => 'يجب إدخال بريد إلكتروني صالح.',
            'email.exists'    => 'هذا البريد الإلكتروني غير مسجل لدينا.',

            'password.required' => 'كلمة المرور مطلوبة.',
        ];
    }

}
