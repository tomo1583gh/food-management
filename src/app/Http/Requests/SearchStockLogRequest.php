<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SearchStockLogRequest extends FormRequest
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
            'food_id' => ['nullable', 'integer', 'exists:foods,id'],
            'type' => ['nullable', 'in:in,out'],
        ];
    }

    public function messages(): array
    {
        return [
            'food_id.integer' => '食材の指定が正しくありません。',
            'food_id.exists' => '選択された食材は存在しません。',
            'type.in' => '入出庫区分の指定が正しくありません。',
        ];
    }

    public function attributes(): array
    {
        return [
            'food_id' => '食材',
            'type' => '入出庫区分',
        ];
    }
}
