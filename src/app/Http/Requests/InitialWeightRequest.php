<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class InitialWeightRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules()
    {
        return [
            'current_weight' => [
                'required',
                'numeric',
                'regex:/^\d{1,3}(\.\d{1})?$/',
            ],
            'target_weight' => [
                'required',
                'numeric',
                'regex:/^\d{1,3}(\.\d{1})?$/',
            ],
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages()
    {
        return [
            'current_weight.required' => '現在の体重を入力してください',
            'current_weight.numeric' => '有効な数値を入力してください',
            'current_weight.regex' => '4桁までの数字で入力してください。小数点は1桁で入力してください',

            'target_weight.required' => '目標の体重を入力してください',
            'target_weight.numeric' => '有効な数値を入力してください',
            'target_weight.regex' => '4桁までの数字で入力してください。小数点は1桁で入力してください',
        ];
    }
}