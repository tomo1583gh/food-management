<?php

namespace Tests\Feature;

use App\Models\Food;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class FoodDestroyTest extends TestCase
{
    use RefreshDatabase;

    private int $categoryId;
    private Food $food;
    private User $user;

    /**
     * 各テスト実行前にログイン処理とテストデータを準備する
     */
    protected function setUp(): void
    {
        parent::setUp();

        // メール認証済みユーザーを作成してログイン
        $this->user = User::factory()->create();
        $this->actingAs($this->user);

        // テスト用カテゴリーを作成
        $this->categoryId = Db::table('categories')->insertGetId([
            'name' => '野菜',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 削除対象の食材を登録
        $this->food = Food::create([
            'name' => 'キャベツ',
            'category_id' => $this->categoryId,
            'unit' => '個',
            'min_qty' => 2,
        ]);
    }

    /**
     * 入出庫履歴がない食材を正常に削除できることを確認する
     */
    public function test_food_without_stock_logs_can_be_deleted(): void
    {
        // 食材の削除リクエストを送信
        $response = $this->delete(
            route('foods.destroy', $this->food)
        );

        // 食材一覧画面へのリダイレクトを確認
        $response->assertRedirect(route('foods.index'));

        // 削除成功メッセージを確認
        $response->assertSessionHas(
            'success',
            '食材を削除しました。'
        );

        // 対象の食材がDBから削除されていることを確認
        $this->assertDatabaseMissing('foods', [
            'id' => $this->food->id,
        ]);
    }

    /**
     * 入出庫履歴がある食材は削除できず、
     * 食材と入出庫履歴が保持されていることを確認する
     */
    public function test_food_with_stock_logs_cannot_be_deleted(): void
    {
        // 入出庫履歴を登録
        $stockLogId = DB::table('stock_logs')->insertGetId([
            'food_id' => $this->food->id,
            'user_id' => $this->user->id,
            'type' => 'in',
            'qty' => 5,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 入出庫履歴がある食材の削除リクエストを送信
        $response = $this->delete(
            route('foods.destroy', $this->food)
        );

        // 食材一覧画面へリダイレクトを確認
        $response->assertRedirect(route('foods.index'));

        // 削除できないことを示すエラーメッセージを確認
        $response->assertSessionHas(
            'error',
            '入出庫履歴がある食材は削除できません。'
        );

        // 食材が削除されずに保持されていることを確認
        $this->assertDatabaseHas('foods', [
            'id' => $this->food->id,
            'name' => 'キャベツ',
        ]);

        // 入出庫履歴も保持されていることを確認
        $this->assertDatabaseHas('stock_logs', [
            'id' =>$stockLogId,
            'food_id' => $this->food->id,
            'type' => 'in',
            'qty' => 5,
        ]);
    }

    /**
     * 存在しない食材IDを指定した場合、
     * 404エラーが返されることを確認
     */
    public function test_nonexistent_food_cannot_be_deleted(): void
    {
        // 存在しない食材IDを指定
            $nonexistentFoodId = 99999;

        // 存在しない食材の削除リクエストを送信
        $response = $this->delete(
            route('foods.destroy', $nonexistentFoodId)
        );

        // 404エラーが返されることを確認
        $response->assertNotFound();

        // 登録済みの食材が削除されていないことを確認
        $this->assertDatabaseHas('foods', [
            'id' =>$this->food->id,
            'name' => 'キャベツ',
        ]);
    }
}
