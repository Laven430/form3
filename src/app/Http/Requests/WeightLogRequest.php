<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class WeightLogRequest extends FormRequest
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
            'date' => ['required', 'date'],
            'weight' => [
                'required',
                'numeric',
                'regex:/^\d{1,3}(\.\d{1})?$/',
            ],
            'calories_intake' => ['required', 'numeric'],
            'exercise_time' => ['required', 'date_format:H:i'],
            'exercise_content' => ['nullable', 'string', 'max:120'],
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
            'date.required' => '日付を入力してください',
            'date.date' => '有効な日付形式で入力してください',

            'weight.required' => '体重を入力してください',
            'weight.numeric' => '数字で入力してください',
            'weight.regex' => '4桁までの数字で入力してください。小数点は1桁で入力してください',

            'calories_intake.required' => '摂取カロリーを入力してください',
            'calories_intake.numeric' => '数字で入力してください',

            'exercise_time.required' => '運動時間を入力してください',
            'exercise_time.date_format' => '「00(時間):00(分)」形式で入力してください',

            'exercise_content.max' => '120文字以内で入力してください',
        ];
    }
}