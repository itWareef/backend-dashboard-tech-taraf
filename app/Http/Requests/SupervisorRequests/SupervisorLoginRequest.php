<?php

namespace App\Http\Requests\SupervisorRequests;

use Illuminate\Foundation\Http\FormRequest;

class SupervisorLoginRequest extends FormRequest
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
            'email'    => ['required', 'email' , 'exists:supervisors,email'],
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
