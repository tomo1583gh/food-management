<?php

return [
  'required' => ':attributeは必須項目です。',
  'email' => ':attributeには有効なメールアドレスを入力してください。',
  'string' => ':attributeは文字列で入力してください。',
  'confirmed' => ':attributeが確認用の入力と一致しません。',
  'unique' => 'この:attributeはすでに登録されています。',

  'min' => [
    'string' => ':attributeは:min文字以上で入力してください。',
  ],

  'max' => [
    'string' => ':attributeは:max文字以内で入力してください。',
  ],

  'attributes' => [
    'name' => 'ユーザー名',
    'email' => 'メールアドレス',
    'password' => 'パスワード',
    'password_confirmation' => 'パスワード（確認）',
  ],
];
