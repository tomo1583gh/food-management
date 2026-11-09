<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;
use App\Models\User;

class FoodStoreTest extends TestCase
{
  use RefreshDatabase;

  protected function setUp(): void
  {
    parent::setUp();

    // 各テストで共通するログイン処理
    $user = User::factory()->create();
    $this->actingAs($user);
  }

  public function test_food_can_be_created(): void
  {
    // テスト用カテゴリを作成
    $categoryId = DB::table('categories')->insertGetId([
      'name' => '野菜',
      'created_at' => now(),
      'updated_at' => now(),
    ]);

    // 食材登録リクエスト
    $response = $this->post(route('foods.store'), [
      'name' => 'キャベツ',
      'category_id' => $categoryId,
      'unit' => '個',
      'min_qty' => 2,
    ]);

    // 登録後のリダイレクトを確認
    $response->assertRedirect(route('foods.index'));
    
    // 成功メッセージを確認
    $response->assertSessionHas(
      'success',
      '食材を登録しました。'
    );

    // DBに登録されたことを確認
    $this->assertDatabaseHas('foods', [
      'name' => 'キャベツ',
      'category_id' => $categoryId,
      'unit' => '個',
      'min_qty' => 2,
    ]);
  }

  public function test_required_fields_are_validated(): void
  {
    // すべての項目を未入力で送信
    $response = $this->post(route('foods.store'), []);

    // 必須項目にエラーが発生することを確認
    $response->assertSessionHasErrors([
      'name',
      'category_id',
      'unit',
      'min_qty',
    ]);

    // 食材が登録されていないことを確認
    $this->assertDatabaseCount('foods', 0);
  }

  public function test_negative_min_qty_is_rejected(): void
  {
    $categoryId = DB::table('categories')->insertGetId([
      'name' => '野菜',
      'created_at' => now(),
      'updated_at' => now(),
    ]);

    $response = $this->post(route('foods.store'), [
      'name' => 'キャベツ',
      'category_id' => $categoryId,
      'unit' => '個',
      'min_qty' => -1,
    ]);

    $response->assertSessionHasErrors(['min_qty']);

    $this->assertDatabaseCount('foods', 0);
  }
}