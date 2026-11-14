<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;
use App\Models\User;

class FoodStoreTest extends TestCase
{
  use RefreshDatabase;

  /**
   * 各テストの実行前に、ログイン済みユーザーを準備する
   */
  protected function setUp(): void
  {
    parent::setUp();

    // 各テストで共通するログイン処理
    /** @var \App\Models\User $user */
    $user = User::factory()->create();
    $this->actingAs($user);
  }

  /**
   * 正しい入力内容で食材を新規登録できることを確認する
   */
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

  /**
   * 必須項目が未入力の場合、バリデーションエラーが発生し、
   * 食材が登録されないことを確認する
   */
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

  /**
   * 最低在庫数にマイナスの値を入力した場合、
   * バリデーションエラーが発生することを確認する
   */
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

  /**
   * 最低在庫数に小数点以下3桁の値を入力した場合、
   * バリデーションエラーが発生することを確認する
   */
  public function test_min_qty_with_three_decimal_places_is_rejected(): void
  {
    $categoryId = DB::table('categories')->insertGetId([
      'name' => '野菜',
      'created_at' => now(),
      'updated_at' =>now(),
    ]);

    $response = $this->post(route('foods.store'), [
      'name' => 'キャベツ',
      'category_id' => $categoryId,
      'unit' => '個',
      'min_qty' => 1.234,
    ]) ;

    $response->assertSessionHasErrors(['min_qty']);

    $this->assertDatabaseCount('foods', 0);
  }

  /**
   * 在庫しないカテゴリーIDを指定した場合、
   * バリデーションエラーが発生することを確認する
   */
  public function test_nonexistent_category_is_rejected(): void
  {
    // 存在しないカテゴリーIDを指定
    $response = $this->post(route('foods.store'), [
      'name' => 'キャベツ',
      'category_id' => 99999,
      'unit' => '個',
      'min_qty' => 2,
    ]);

    // カテゴリーIDのバリデーションエラーを確認
    $response->assertSessionHasErrors(['category_id']);

    // 食材が登録されてないことを確認
    $this->assertDatabaseCount('foods', 0);
  }

  /**
   * 食材名が最大文字数の255文字を超えた場合、
   * バリデーションエラーが発生することを確認する
   */
  public function test_food_name_exceeding_max_length_is_rejected(): void
  {
    // テスト用カテゴリーを作成
    $categoryId = DB::table('categories')->insertGetId([
      'name' => '野菜',
      'created_at' => now(),
      'updated_at' => now(),
    ]);

    // 256文字の食材名を作成
    $longName = str_repeat('あ', 256);

    $response = $this->post(route('foods.store'), [
      'name' => $longName,
      'category_id' => $categoryId,
      'unit' => '個',
      'min_qty' => 2,
    ]);

    // 食材名の文字制限エラーを確認
    $response->assertSessionHasErrors(['name']);

    // 食材が登録されていないことを確認
    $this->assertDatabaseCount('foods', 0);
  }

  /**
   * 食材の単位が最大文字数の50文字を超えた場合、
   * バリデーションエラーが発生することを確認する
   */
  public function test_food_unit_exceeding_max_length_is_rejected(): void
  {
    // テスト用カテゴリーを作成
    $categoryId = DB::table('categories')->insertGetId([
      'name' => '野菜',
      'created_at' => now(),
      'updated_at' => now(),
    ]);

    // 51文字の単位を作成
    $longUnit = str_repeat('個', 51);

    $response = $this->post(route('foods.store'), [
      'name' => 'キャベツ',
      'category_id' => $categoryId,
      'unit' => $longUnit,
      'min_qty' => 2,
    ]);

    // 単位の文字数制限エラーを確認
    $response->assertSessionHasErrors(['unit']);

    // 食材が登録されていないことを確認
    $this->assertDatabaseCount('foods', 0);
  }
}