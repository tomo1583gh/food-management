<?php

namespace tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class RefreshDatabaseTest extends TestCase
{
  use RefreshDatabase;

  /**
   * テスト中に登録したデータが存在することを確認する
   * （RefreshDatabaseによってテスト終了後に変更が元に戻される）
   */
  public function test_database_changes_are_rolled_back(): void
  {
    // テスト用のカテゴリを登録
    DB::table('categories')->insert([
      'name' => 'テスト用カテゴリ',
      'created_at' => now(),
      'updated_at' => now(),
    ]);

    // 登録したデータが存在することを確認
    $this->assertDatabaseHas('categories', [
      'name' => 'テスト用カテゴリ',
    ]);
  }
}