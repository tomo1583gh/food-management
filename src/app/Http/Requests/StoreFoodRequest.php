<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreFoodRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:255'],
            'category_id' => ['required', 'exists:categories,id'],
            'unit' => ['required', 'string', 'max:50'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => '食材名を入力してください。',
            'name.string' => '食材名は文字列で入力してください。',
            'name.max' => '食材名は255文字以内で入力してください。',

            'category_id.required' => 'カテゴリーを選択してください。',
            'category_id.exists' => '選択したカテゴリーが存在しません。',

            'unit.required' => '単位を入力してください。',
            'unit,string' => '単位は文字列で入力してください。',
            'unit.max' => '単位は50文字以内で入力してください。',
        ];
    }
}
