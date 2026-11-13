<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * トップページからダッシュボードへリダイレクトされる
     */
    public function test_root_redirects_to_dashboard(): void
    {
        // トップページにアクセス
        $response = $this->get('/');

        // ダッシュボードへのリダイレクトを確認
        $response->assertRedirect(route('dashboard.index'));
    }
}
