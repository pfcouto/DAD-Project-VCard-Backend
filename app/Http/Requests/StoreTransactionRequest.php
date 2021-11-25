<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTransactionRequest extends FormRequest
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
            'vcard' => 'required|exists:vcards,phone_number',
            'type' => 'required|in:C,D',
            'value' => 'required|numeric|min:0.01',
            'payment_type' => 'required|exists:payment_types,code',
            'payment_reference' => 'required|string|max:255',
            'pair_vcard' => 'nullable|exists:vcards,phone_number',
            'category_id' => 'nullable|exists:categories,id',
            'description' => 'nullable|string|max:255'
        ];
    }
    
    public function messages()
    {
        return [
            'type.in' => 'Type must be either \'C\'(Credit) or \'D\'(Deposit)',
            'description.max' => 'Description must be smaller than 255 characters',
        ];
    }
}
