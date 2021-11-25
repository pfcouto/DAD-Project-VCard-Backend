<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTransactionRequest extends FormRequest
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
            'category_id' => 'nullable|exists:categories,id',
            'description' => 'nullable|string|max:255'
        ];
    }

    public function messages()
    {
        return [
            'description.max' => 'Description must be smaller than 255 characters',
        ];
    }
}
