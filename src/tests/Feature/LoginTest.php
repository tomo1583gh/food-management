<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    /**
     * 正しいメールアドレスとパスワードでログインできる 
     */
    public function test_user_can_login_with_valid_credentials(): void
    {
        // テストユーザーを作成
        $user = User::factory()->create([
            'email' => 'login-test@example.com',
            'password' => bcrypt('password123'),
        ]);

        // ログインを試みる
        $response = $this->post(route('login'), [
            'email' => 'login-test@example.com',
            'password' => 'password123',
        ]);

        // ログイン状態を確認
        $this->assertAuthenticatedAs($user);

        // ログイン後のリダイレクトを確認
        $response->assertRedirect();
    }

    /**
     * パスワードが間違っている場合、ログインできない
     */
    public function test_user_cannot_login_with_invalid_password(): void
    {
        // テストユーザーを作成
        User::factory()->create([
            'email' => 'login-test@example.com',
            'password' => bcrypt('password123'),
        ]);

        // 間違ったパスワードでログインを試みる
        $response = $this->post(route('login'), [
            'email' => 'login-test@example.com',
            'password' => 'wrongpassword',
        ]);

        // ログインしていないことを確認
        $this->assertGuest();

        // 認証エラーが発生していることを確認
        $response->assertSessionHasErrors(['email']);
    }

    /**
     * ログイン時に必須項目が未入六の場合、エラーになる
     */
    public function test_login_requires_mandatory_fields(): void
    {
        // メールアドレスとパスワードを空欄で送信
        $response = $this->post(route('login'), [
            'email' => '',
            'password' => '',
        ]);

        // 必須事項のバリデーションエラーを確認
        $response->assertSessionHasErrors([
            'email',
            'password',
        ]);

        // ログインしていないことを確認
        $this->assertGuest();
    }
}
