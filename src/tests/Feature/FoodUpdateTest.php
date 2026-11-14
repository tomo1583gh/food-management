<?php

namespace Tests\Feature;

use App\Models\Food;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class FoodUpdateTest extends TestCase
{
    use RefreshDatabase;

    // 各テストで共通利用するデータ
    private int $categoryId;
    private Food $food;

    /**
     * 各テスト実行前にログイン処理とデータを準備する
     */

    protected function setUp(): void
    {
        parent::setUp();

        // メール認証済みユーザーを作成してログイン
        $user = User::factory()->create();
        $this->actingAs($user);

        // テスト用カテゴリーを作成
        $this->categoryId = DB::table('categories')->insertGetId([
            'name' => '野菜',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 更新対象の食材を登録
        $this->food = Food::create([
            'name' => 'キャベツ',
            'category_id' => $this->categoryId,
            'unit' => '個',
            'min_qty' =>2,
        ]);
    }

    /**
     * 正しい入力内容で登録済みの食材情報を更新できることを確認する
     */
    public function test_food_can_be_updated(): void
    {

        // 食材情報の更新リクエストを送信
        $response = $this->put(route('foods.update', $this->food), [
                'name' => '白菜',
                'category_id' => $this->categoryId,
                'unit' => '玉',
                'min_qty' => 3,
            ]);

        // 更新後のリダイレクトを確認
        $response->assertRedirect(route('foods.index'));

        // 成功メッセージを確認
        $response->assertSessionHas(
            'success',
            '食材を更新しました。'
        );

        // DBの食材情報がこう思惟されていることを確認
        $this->assertDatabaseHas('foods', [
            'id' => $this->food->id,
            'name' => '白菜',
            'category_id' => $this->categoryId,
            'unit' => '玉',
            'min_qty' => 3,
        ]);
    }

    /**
     * 必須項目が未入力の場合、商材情報が更新されないことを確認する
     */
    public function test_required_fields_are_validated_on_update(): void
    {
        // 必須項目を空欄にして更新リクエストを送信
        $response = $this->put(route('foods.update', $this->food), []);

        // 必須項目のバリデーションエラーを確認
        $response->assertSessionHasErrors([
            'name',
            'category_id',
            'unit',
            'min_qty',
        ]);

        // 元の食材情報が変更されていないことを確認
        // 元の食材情報が変更されていないことを確認
        $this->assertDatabaseHas('foods', [
            'id' => $this->food->id,
            'name' => 'キャベツ',
            'category_id' => $this->categoryId,
            'unit' => '個',
            'min_qty' => 2,
        ]);
    }

    /**
     * 最低在庫数にマイナスの値を指定した場合、
     * 食材情報が更新されていないことを確認する
     */
    public function test_negative_min_qty_is_rejected_on_update(): void
    {
        // 最低在庫数にマイナスの値を指定して更新
        $response = $this->put(route('foods.update', $this->food), [
                'name' => '白菜',
                'category_id' => $this->categoryId,
                'unit' => '玉',
                'min_qty' => -1,
            ]);

        // 最低在庫数のバリデーションエラーを確認
        $response->assertSessionHasErrors(['min_qty']);

        // 更新前の食材情報が保持されていることを確認
        $this->assertDatabaseHas('foods', [
            'id' => $this->food->id,
            'name' => 'キャベツ',
            'category_id' => $this->categoryId,
            'unit' => '個',
            'min_qty' => 2,
        ]);
    }

    /**
     * 最低在庫数に小数点以下3桁の値を指定した場合、
     * 食材情報が更新されていないことを確認する
     */
    public function test_min_qty_with_three_decimal_places_is_rejected_on_update(): void
    {
        // 最低在庫数に小数点3桁の値を指定して更新
        $response = $this->put(route('foods.update', $this->food), [
                'name' => '白菜',
                'category_id' => $this->categoryId,
                'unit' => '玉',
                'min_qty' => 1.234, 
            ]);

        // 最低在庫数のバリデーションエラーを確認
        $response->assertSessionHasErrors(['min_qty']);

        // 更新前の食材情報が保持されていることを確認
        $this->assertDatabaseHas('foods', [
            'id' => $this->food->id,
            'name' => 'キャベツ',
            'category_id' => $this->categoryId,
            'unit' => '個',
            'min_qty' => 2,
        ]);
    }

    /**
     * 存在しないカテゴリーIDを指定した場合、
     * 食材情報が更新されないことを確認する
     */
    public function test_nonexistent_category_is_rejected_on_update(): void
    {
        // 存在しないカテゴリーで更新リクエストを送信
        $response = $this->put(route('foods.update', $this->food), [
                'name' => '白菜',
                'category_id' => 99999,
                'unit' => '玉',
                'min_qty' => 3,
            ]);

        // カテゴリーIDのバリデーションエラーを確認
        $response->assertSessionHasErrors(['category_id']);

        // 更新前の食材情報が保持されていることを確認
        $this->assertDatabaseHas('foods', [
            'id' => $this->food->id,
            'name' => 'キャベツ',
            'category_id' => $this->categoryId,
            'unit' => '個',
            'min_qty' => 2,
        ]);
    }

    /**
     * 食材名が最大文字数の255文字を超えた場合、
     * 食材情報が更新されないことを確認する
     */

    public function test_food_name_exceeding_max_length_is_rejected_on_update(): void
    {
        // 256文字の食材名を作成
        $longName = str_repeat('あ', 256);

        // 文字数制限を超える食材名で更新リクエストを送信
        $response = $this->put(route('foods.update', $this->food), [
                'name' => $longName,
                'category_id' => $this->categoryId,
                'unit' => '玉',
                'min_qty' => 3,
            ]);

        // 食材名のバリデーションエラーを確認
        $response->assertSessionHasErrors(['name']);

        // 更新前の食材情報が保持されていることを確認
        $this->assertDatabaseHas('foods', [
            'id' => $this->food->id,
            'name' => 'キャベツ',
            'category_id' => $this->categoryId,
            'unit' => '個',
            'min_qty' => 2,
        ]);
    }

    /**
     * 食材の単位が最大文字数の50文字を超えた場合、
     * 食材情報が更新されていないことを確認する
     */
    public function test_food_unit_exceeding_max_length_is_rejected_on_update(): void
    {
        // 51文字の単位を作成
        $longUnit = str_repeat('個', 51);

        // 文字数制限を超える単位で更新リクエストを送信
        $response = $this->put(route('foods.update', $this->food), [
                'name' => '白菜',
                'category_id' => $this->categoryId,
                'unit' => $longUnit,
                'min_qty' => 3,
            ]);

        // 単位のバリデーションエラーを確認
        $response->assertSessionHasErrors(['unit']);

        // 更新前の食材情報が保持されていることを確認
        $this->assertDatabaseHas('foods', [
            'id' => $this->food->id,
            'name' => 'キャベツ',
            'category_id' => $this->categoryId,
            'unit' => '個',
            'min_qty' => 2,
        ]);
    }
}
