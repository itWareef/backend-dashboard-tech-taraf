<?php

namespace App\Http\Requests\MainProjectRequests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateMainProjectRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required_with:name|string|max:255',
            'description' => 'required_with:description|string',
            'developer_id' => 'required_with:developer_id|exists:main_developers,id',
            'low_price' => 'required_with:low_price|string|max:255',
            'high_price' => 'required_with:high_price|string|max:255',
            'unit_count' => 'required_with:unit_count|string|max:255',
            'price_precentage' => 'required_with:price_precentage|string|max:255',
            'youtube_link' => 'required_with:youtube_link|string|max:255',
            'low_space' => 'required_with:low_space|string|max:255',
            'high_space' => 'required_with:high_space|string|max:255',
            'pictures.*.path' => 'nullable',
        ];
    }

    public function messages()
    {
        return [
            'name.required_with' => 'اسم المشروع مطلوب عند التحديث',
            'description.required_with' => 'وصف المشروع مطلوب عند التحديث',
            'developer_id.required_with' => 'المطور مطلوب عند التحديث',
            'developer_id.exists' => 'المطور غير موجود',
            'low_price.required_with' => 'السعر الأدنى مطلوب عند التحديث',
            'high_price.required_with' => 'السعر الأعلى مطلوب عند التحديث',
            'unit_count.required_with' => 'عدد الوحدات مطلوب عند التحديث',
            'price_precentage.required_with' => 'نسبة السعر مطلوبة عند التحديث',
            'youtube_link.required_with' => 'رابط اليوتيوب مطلوب عند التحديث',
            'low_space.required_with' => 'المساحة الأدنى مطلوبة عند التحديث',
            'high_space.required_with' => 'المساحة الأعلى مطلوبة عند التحديث',
            'pictures.*.image' => 'يجب أن يكون الملف صورة',
            'pictures.*.mimes' => 'يجب أن يكون نوع الملف jpeg, png, jpg, gif',
            'pictures.*.max' => 'حجم الصورة يجب أن لا يتجاوز 2 ميجابايت',
        ];
    }
} 