<?php

namespace App\Http\Requests\CustomerRequests;

use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CustomerUpdatingRequest extends FormRequest
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
            'username' => ['required_if:username,null', Rule::unique('customers','username')->ignore(auth('customer')->id()), 'string', 'max:255'],
            'first_name' => ['required_if:first_name,null', 'string', 'max:255'],
            'last_name' => ['required_if:last_name,null', 'string', 'max:255'],
            'national_identify' => ['required_if:national_identify,null',],
            'date_of_birth' => [
                'required_if:date_of_birth,null',
                'date',
                function ($attribute, $value, $fail) {
                    $minAgeDate = Carbon::now()->subYears(18);
                    if (Carbon::parse($value)->gt($minAgeDate)) {
                        $fail('The date of birth must be at least 18 years ago.');
                    }
                },
            ],
            'expire_identify' => [
                'required_if:expire_identify,null',
                'date',
                function ($attribute, $value, $fail) {
                    $minFutureDate = Carbon::now()->addMonth();
                    if (Carbon::parse($value)->lt($minFutureDate)) {
                        $fail('The expiration date must be at least one month in the future.');
                    }
                },
            ],
            'phone' => ['required_if:phone,null', 'string', 'max:15',],
            'email' => ['required_if:email,null',Rule::unique('customers','email')->ignore(auth('customer')->id()), 'email'],
        ];
    }

    public function messages()
    {
        return [
            'date_of_birth.required' => 'The date of birth is required.',
            'date_of_birth.date' => 'The date of birth must be a valid date.',
            'expire_identify.required' => 'The expiration date is required.',
            'expire_identify.date' => 'The expiration date must be a valid date.',
        ];
    }
}
