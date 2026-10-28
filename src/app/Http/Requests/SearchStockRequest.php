<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SearchStockRequest extends FormRequest
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
            'q' => ['nullable', 'string', 'max:100'],
            'category_id' => ['nullable', 'integer', 'exists:categories,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'q.string' => '検索キーワードは文字列で入力してください。',
            'q.max' => '検索キーワードは100文字以内で入力してください。',
            'category_id.integer' => 'カテゴリの指定が正しくありません。',
            'category_id.exists' => '選択されたカテゴリは存在しません。',
        ];
    }

    public function attributes(): array
    {
        return [
            'q' => '食材名',
            'category_id' => 'カテゴリ',
        ];
    }
}
