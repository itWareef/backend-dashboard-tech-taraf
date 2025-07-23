<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAdvertisingPostRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize()
        {
            return true;
        }

    public function rules()
    {
        return [
            'title'        => 'required|string|max:255',
            'picture'     => 'required',
        ];
    }
}
