<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUpdateVCardRequest extends FormRequest
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
            'phone_number' => 'required|digits:9|unique:vcards,phone_number',
            'name' => 'required|string|max:255',
            'email' => 'required|string|max:255',
            'photo_url' => 'nullable|string|max:255',
            'password' => 'required|string|max:255',
            'confirmation_code' => 'required|digits:4',
            'blocked' => 'required|digits:1',
            'balance' => ['required', 'regex:/^(^\d{1,7}\.\d{2}$)|(^\d{1,8}\.\d{1}$)|(^\d{1,9}$)$/'],
            //'max_debit' => ['required', 'regex:/^(^\d{1,7}\.\d{2}$)|(^\d{1,8}\.\d{1}$)|(^\d{1,9}$)$/'],
            'custom_options' => 'nullable|json',
            'custom_data' => 'nullable|json',
        ];
    }
}
