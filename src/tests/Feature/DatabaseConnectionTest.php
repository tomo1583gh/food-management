<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class DatabaseConnectionTest extends TestCase
{
    public function test_database_connection_is__test_mysql(): void
    {

        // laravelのデフォルト接続がMySQLであることを確認
        $this->assertSame(
            'mysql',
            config('database.default')
        );

        // 実際に接続しているDBとユーザーを取得
        $result = DB::selectOne(
            'SELECT DATABASE() AS db_name, CURRENT_USER() AS db_user'
        );

        // 接続先がテスト専用であることを確認
        $this->assertSame(
            'food_management_test',
            $result->db_name
        );

        // 接続ユーザーがテスト専用ユーザーであることを確認
        $this->assertSame(
            'laravel_test_user@%',
            $result->db_user
        );
    } 
}
