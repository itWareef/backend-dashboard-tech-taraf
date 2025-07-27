<?php

namespace App\Http\Requests\MainProjectRequests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMainProjectRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'developer_id' => 'required|exists:main_developers,id',
            'low_price' => 'required|string|max:255',
            'high_price' => 'required|string|max:255',
            'unit_count' => 'required|string|max:255',
            'price_precentage' => 'required|string|max:255',
            'youtube_link' => 'required|string|max:255',
            'low_space' => 'required|string|max:255',
            'high_space' => 'required|string|max:255',
            'pictures.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'اسم المشروع مطلوب',
            'description.required' => 'وصف المشروع مطلوب',
            'developer_id.required' => 'المطور مطلوب',
            'developer_id.exists' => 'المطور غير موجود',
            'low_price.required' => 'السعر الأدنى مطلوب',
            'high_price.required' => 'السعر الأعلى مطلوب',
            'unit_count.required' => 'عدد الوحدات مطلوب',
            'price_precentage.required' => 'نسبة السعر مطلوبة',
            'youtube_link.required' => 'رابط اليوتيوب مطلوب',
            'low_space.required' => 'المساحة الأدنى مطلوبة',
            'high_space.required' => 'المساحة الأعلى مطلوبة',
            'pictures.*.image' => 'يجب أن يكون الملف صورة',
            'pictures.*.mimes' => 'يجب أن يكون نوع الملف jpeg, png, jpg, gif',
            'pictures.*.max' => 'حجم الصورة يجب أن لا يتجاوز 2 ميجابايت',
        ];
    }
} 