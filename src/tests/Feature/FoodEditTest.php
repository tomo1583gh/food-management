<?php

namespace Tests\Feature;

use App\Models\Food;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class FoodEditTest extends TestCase
{
    use RefreshDatabase;

    private int $categoryId;
    private Food $food;

    /**
     * 各テスト実行前にログイン処理とテストデータを準備する
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

        // 編集対象の食材を登録
        $this->food = Food::create([
            'name' => 'キャベツ',
            'category_id' => $this->categoryId,
            'unit' => '個',
            'min_qty' => 2,
        ]);
    }
    
    /**
     * 登録済み食材の編集画面を正常に表示できることを確認する
     */
    public function test_registered_food_edit_page_can_be_displayed(): void
    {
        // 食材編集画面にアクセス
        $response = $this->get(
            route('foods.edit', $this->food)
        );

        // HTTPステータス200を確認
        $response->assertOk();

        // 正しいBladeビューが使用されていることを確認
        $response->assertViewIs('foods.edit');

        // 編集対象の食材がビューに渡されていることを確認
        $response->assertViewHas('food', function ($food) {
            return $food->id === $this->food->id;
        });

        // カテゴリー一覧がビューに渡されていることを確認
        $response->assertViewHas('categories', function ($categories) {
            return $categories->contains('id', $this->categoryId);
        });
    }

    /**
     * 存在しない食材IDを指定した場合、
     * 編集画面が表示されず404エラーになることを確認する
     */
    public function test_nonexistent_food_edit_page_returns_404(): void
    {
        // 存在しない食材IDを指定
        $nonexistentFoodId = 99999;

        // 存在しない食材の編集画面にアクセス
        $response = $this->get(
            route('foods.edit', $nonexistentFoodId)
        );

        // 404エラーが返されることを確認
        $response->assertNotFound();

        // 登録済み食材が保持されていることを確認
        $this->assertDatabaseHas('foods', [
            'id' => $this->food->id,
            'name' => 'キャベツ'
        ]);
    }
}
