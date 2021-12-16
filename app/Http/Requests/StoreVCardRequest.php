<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class StoreVCardRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'phone_number' => ['required', 'regex:/^9[0-9]{8}$/', 'unique:vcards,phone_number,' . (int)$this->phone_number . ',phone_number'],
            'name' => 'required|string|max:255',
            'email' => 'required|string|max:255|unique:vcards,email',
            'photo_url' => 'nullable|string|max:255',

            'password' => [
                'nullable', 'confirmed', 'string', 'max:255',
                Password::min(4)
                // ->mixedCase()
                // ->numbers()
                // ->symbols()
            ],
            'confirmation_code' => 'nullable|confirmed|digits:4',

            'custom_options' => 'nullable|json',
            'custom_data' => 'nullable|json',
        ];
    }
}
