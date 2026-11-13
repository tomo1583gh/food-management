<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * 怪異登録時に必須項目が未入力の場合、エラーになる 
     */
    public function test_registration_requires_mandatory_fields(): void
    {
        // 必須項目を空欄で送信
        $response = $this->post(route('register'), [
            'name' => '',
            'email' => '',
            'password' => '',
            'password_confirmation' => '',
        ]);

        // 必須項目のバリデーションエラーを確認
        $response->assertSessionHasErrors([
            'name',
            'email',
            'password',
        ]);

        // ユーザー登録がされていないことを確認
        $this->assertDatabaseCount('users', 0);
    }

    /**
     * メールアドレスの形が不正な場合、登録できない
     */
    public function test_registration_rejects_invalid_email(): void
    {
        // 不正なメールアドレスで登録を試みる
        $response = $this->post(route('register'), [
            'name' => 'テストユーザー',
            'email' => 'invalid-email',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        // メールアドレスのバリデーションエラーを確認
        $response->assertSessionHasErrors(['email']);

        // ユーザーが登録されていないことを確認
        $this->assertDatabaseCount('users', 0);
    }

    /**
     * パスワードと確認用パスワードが一致しない場合、登録できない
     */
    public function test_registration_rejects_password_mismatch(): void
    {
        // 確認用パスワードを異なる値にして登録
        $response = $this->post(route('register'), [
            'name' => 'テストユーザー',
            'email' => 'password-test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'different123',
        ]);

        // パスワードのバリデーションエラーを確認
        $response->assertSessionHasErrors(['password']);

        // ユーザーが登録されていないことを確認
        $this->assertDatabaseCount('users', 0);
    }

    /**
     * 登録済みメールアドレスでは新規登録できない
     */
    public function test_registration_rejects_duplicate_email(): void
    {
        // 既存ユーザーを作成
        User::factory()->create([
            'email' => 'duplicate@example.com',
        ]);

        // 同じメールアドレスで新規登録を試みる
        $response = $this->post(route('register'), [
            'name' => '新規ユーザー',
            'email' => 'duplicate@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        // メールアドレスの重複エラーを確認
        $response->assertSessionHasErrors(['email']);

        // ユーザー数が増えていないことを確認
        $this->assertDatabaseCount('users', 1);
    }
}
