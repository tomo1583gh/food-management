<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Support\Facades\Notification;


class EmailVerificationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * メール未認証ユーザはダッシュボードにアクセスできない
     */
    public function test_unverified_user_cannot_access_dashboard(): void
    {
        // メール未認証ユーザーを作成
        $user = User::factory()->unverified()->create();

        // 未認証ユーザーとしてダッシュボードにアクセス
        $response = $this->actingAS($user)
            ->get(route('dashboard.index'));

        // メール認証案内画面へのリダイレクトを確認
        $response->assertRedirect(route('verification.notice'));
    }

    /**
     * メール認証済みユーザーはダッシュボードにアクセスできる
     */
    public function test_verified_user_can_access_dashboard(): void
    {
        // メール認証済みユーザーを作成
        $user = User::factory()->create();

        // 認証済みユーザーとしてダッシュボードにアクセス
        $response = $this->actingAs($user)
            ->get(route('dashboard.index'));

        // 正常に画面が表示されることを確認
        $response->assertOk();
    }

    /**
     * 会員登録時にメール認証通知が送信される
     */
    public function test_verification_email_is_sent_after_registration(): void
    {
        // 通知を実際には通知せず、記録する
        Notification::fake();

        // 新規ユーザーを登録
        $response = $this->post(route('register'), [
            'name' => 'テストユーザー',
            'email' => 'verification-test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        // ユーザーがデータベースに登録されていることを確認
        $this->assertDatabaseHas('users', [
            'email' => 'verification-test@example.com',
        ]);

        // 登録されたユーザーを取得
        $user = User::where('email', 'verification-test@example.com')->firstOrFail();
        
        $this->assertNull($user->email_verified_at);

        // メール認証通知が送信されたことを確認
        Notification::assertSentTo($user, VerifyEmail::class);
    }
}
