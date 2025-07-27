<?php

namespace App\Http\Requests\MainDeveloperRequests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMainDeveloperRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'picture_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'space' => 'required|string|max:255',
            'space_building' => 'required|string|max:255',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'اسم المطور مطلوب',
            'space.required' => 'المساحة مطلوبة',
            'space_building.required' => 'مساحة البناء مطلوبة',
            'picture.image' => 'يجب أن يكون الملف صورة',
            'picture.mimes' => 'يجب أن يكون نوع الملف jpeg, png, jpg, gif',
            'picture.max' => 'حجم الصورة يجب أن لا يتجاوز 2 ميجابايت',
            'picture_logo.image' => 'يجب أن يكون الملف صورة',
            'picture_logo.mimes' => 'يجب أن يكون نوع الملف jpeg, png, jpg, gif',
            'picture_logo.max' => 'حجم الصورة يجب أن لا يتجاوز 2 ميجابايت',
        ];
    }
} 