<?php

namespace App\Http\Requests\SupervisorRequests;

use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;

class RegisterSupervisorRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }
    protected $stopOnFirstFailure = true;

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'last_name' => ['required', 'string', 'max:255'],
            'first_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:supervisors,email'],
            'phone' => ['required', 'string', 'unique:supervisors,phone'],
            'password' => ['required', 'confirmed','string', 'min:8'],
        ];
    }
    public function messages()
    {
        return [
            'last_name.required' => 'اسم العائلة مطلوب.',
            'last_name.string' => 'اسم العائلة يجب أن يكون نصاً.',
            'last_name.max' => 'اسم العائلة لا يجب أن يتجاوز 255 حرفاً.',

            'first_name.required' => 'الاسم الأول مطلوب.',
            'first_name.string' => 'الاسم الأول يجب أن يكون نصاً.',
            'first_name.max' => 'الاسم الأول لا يجب أن يتجاوز 255 حرفاً.',

            'email.required' => 'البريد الإلكتروني مطلوب.',
            'email.email' => 'يجب إدخال بريد إلكتروني صالح.',
            'email.unique' => 'هذا البريد الإلكتروني مستخدم بالفعل.',

            'phone.required' => 'رقم الهاتف مطلوب.',
            'phone.string' => 'رقم الهاتف يجب أن يكون نصاً.',
            'phone.unique' => 'رقم الهاتف مستخدم بالفعل.',

            'password.required' => 'كلمة المرور مطلوبة.',
            'password.confirmed' => 'تأكيد كلمة المرور غير مطابق.',
            'password.string' => 'كلمة المرور يجب أن تكون نصاً.',
            'password.min' => 'كلمة المرور يجب أن تتكون من 8 أحرف على الأقل.',
        ];
    }

}
