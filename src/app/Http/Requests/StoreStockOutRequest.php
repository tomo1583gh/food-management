<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreStockOutRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'food_id' => ['required', 'exists:foods,id'],
            'qty' => [
                'required',
                'numeric',
                'gt:0',
                'decimal:0,2',
                'max:99999999.99',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'food_id.required' => '食材を選択してください。',
            'food_id.exists' => '選択された食材は存在しません。',
            'qty.required' => '使用数量を入力してください。',
            'qty.numeric' => '使用数狼は数値で入力してください。',
            'qty.gt' => '使用数量は0より大きい数値を入力してください。',
            'qty.decimal' => '使用数量は小数点以下2桁まで入力してください。',
            'qty.max' => '使用数量が上限を超えています。',
        ];
    }
}
