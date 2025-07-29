<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class InvoiceRequest extends FormRequest
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
            'amount' => 'required|numeric|min:0',
            'description' => 'nullable|string|max:1000',
            'status' => 'sometimes|in:paid,unpaid',
            'invoiceable_type' => 'required|string',
            'invoiceable_id' => 'required|integer',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'amount.required' => 'قيمة الفاتورة مطلوبة',
            'amount.numeric' => 'قيمة الفاتورة يجب أن تكون رقماً',
            'amount.min' => 'قيمة الفاتورة يجب أن تكون أكبر من صفر',
            'description.max' => 'الوصف يجب أن لا يتجاوز 1000 حرف',
            'status.in' => 'حالة الفاتورة يجب أن تكون paid أو unpaid',
            'invoiceable_type.required' => 'نوع الطلب مطلوب',
            'invoiceable_id.required' => 'معرف الطلب مطلوب',
            'invoiceable_id.integer' => 'معرف الطلب يجب أن يكون رقماً',
        ];
    }
}
