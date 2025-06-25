<?php

namespace App\Http\Requests\CustomerRequests;


use Illuminate\Foundation\Http\FormRequest;

class CustomerAddressStoringRequest extends FormRequest
{
    protected $stopOnFirstFailure =true;
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
            'country_id'      =>['required','integer' , 'exists:countries,id'],
            'city_id'      =>['required','integer' , 'exists:cities,id'],
            'street'         =>['required'],
            'building_no'         =>['required'],
            'post_box'     =>['required'],
            'additional_number'    =>['nullable',],
        ];
    }
    public function messages()
    {
        return [
            'street.required' => 'The street field is required.',
            'street.string' => 'The street must be a string.',

            'building_no.required' => 'The building number field is required .',
            'building_no.string' => 'The building number must be a string.',

            'post_box.required' => 'The post box field is required .',
            'post_box.string' => 'The post box must be a string.',

            'additional_number.string' => 'The additional number must be a string.',

            'country_id.required' => 'The country field is required .',
            'country_id.integer' => 'The country must be an integer.',
            'country_id.exists' => 'The selected country is invalid.',

            'city_id.required' => 'The city field is required .',
            'city_id.integer' => 'The city must be an integer.',
            'city_id.exists' => 'The selected city is invalid.',
        ];
    }
}
