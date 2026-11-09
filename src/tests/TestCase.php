<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use RuntimeException;

abstract class TestCase extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    protected function setUpTraits(): array
    {
        // RefreshDatabaseなどのトレイトを実行する前に確認
        $this->assertSafeTestDatabase();

        return parent::setUpTraits();
    }

    private function assertSafeTestDatabase(): void
    {
        //テスト専用DB以外への接続を禁止
        if (
            app()->environment() !== 'testing' ||
            config('database.default') !== 'mysql' ||
            config('database.connections.mysql.database') !== 'food_management_test' ||
            config('database.connections.mysql.username') !== 'laravel_test_user'
        ) {
            throw new RuntimeException(
                'テスト専用DB以外への接続が検出されたため、テストを中断しました。'
            );
        }
    }
}
