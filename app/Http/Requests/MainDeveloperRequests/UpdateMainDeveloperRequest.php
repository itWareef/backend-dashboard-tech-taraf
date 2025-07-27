<?php

namespace App\Http\Requests\MainDeveloperRequests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateMainDeveloperRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required_with:name|string|max:255',
            'picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'picture_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'space' => 'required_with:space|string|max:255',
            'space_building' => 'required_with:space_building|string|max:255',
        ];
    }

    public function messages()
    {
        return [
            'name.required_with' => 'اسم المطور مطلوب عند التحديث',
            'space.required_with' => 'المساحة مطلوبة عند التحديث',
            'space_building.required_with' => 'مساحة البناء مطلوبة عند التحديث',
            'picture.image' => 'يجب أن يكون الملف صورة',
            'picture.mimes' => 'يجب أن يكون نوع الملف jpeg, png, jpg, gif',
            'picture.max' => 'حجم الصورة يجب أن لا يتجاوز 2 ميجابايت',
            'picture_logo.image' => 'يجب أن يكون الملف صورة',
            'picture_logo.mimes' => 'يجب أن يكون نوع الملف jpeg, png, jpg, gif',
            'picture_logo.max' => 'حجم الصورة يجب أن لا يتجاوز 2 ميجابايت',
        ];
    }
} 