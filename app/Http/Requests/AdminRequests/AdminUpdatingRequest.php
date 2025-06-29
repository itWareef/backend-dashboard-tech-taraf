<?php

namespace App\Http\Requests\AdminRequests;

use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AdminUpdatingRequest extends FormRequest
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
            'first_name' => ['required_if:first_name,null', 'string', 'max:255'],
            'last_name' => ['required_if:last_name,null', 'string', 'max:255'],
            'phone' => ['required_if:phone,null', 'string', 'max:15',Rule::unique('users','phone')->ignore(auth('api')->id())],
            'email' => ['required_if:email,null',Rule::unique('users','email')->ignore(auth('api')->id()), 'email'],
        ];
    }

    public function messages()
    {
        return [
            'first_name.required' => 'الاسم الأول مطلوب.',
            'first_name.string' => 'الاسم الأول يجب أن يكون نصاً.',
            'first_name.max' => 'الاسم الأول لا يجب أن يتجاوز 255 حرفاً.',

            'last_name.required' => 'اسم العائلة مطلوب.',
            'last_name.string' => 'اسم العائلة يجب أن يكون نصاً.',
            'last_name.max' => 'اسم العائلة لا يجب أن يتجاوز 255 حرفاً.',

            'phone.required' => 'رقم الهاتف مطلوب.',
            'phone.string' => 'رقم الهاتف يجب أن يكون نصاً.',
            'phone.max' => 'رقم الهاتف لا يجب أن يتجاوز 15 رقماً.',

            'email.required' => 'البريد الإلكتروني مطلوب.',
            'email.email' => 'يجب إدخال بريد إلكتروني صالح.',
            'email.unique' => 'هذا البريد الإلكتروني مستخدم بالفعل.',
        ];
    }

}
