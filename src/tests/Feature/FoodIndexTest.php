<?php

namespace Tests\Feature;

use App\Models\User;
Use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Food;
use Illuminate\Support\Facades\DB;

class FoodIndexTest extends TestCase
{
    use RefreshDatabase;

    // 各テストで共通利用するカテゴリーID
    private int $categoryId;

    /**
     * 各テストの実行前にログイン処理とテスト用カテゴリーの登録を行う
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
    }

    /**
     * メール認証済みユーザーは食材一覧画面を表示できる
     */
    public function test_verified_user_can_view_food_index(): void
    {
        // 食材一覧にアクセス
        $response = $this->get(route('foods.index'));

        // HTTPステータス200を確認
        $response->assertOk();

        // 正しいbladeビューが使用されていることを確認
        $response->assertViewIs('foods.index');
    }

    /**
     * 登録済みの食材一覧画面のデータに含まれることを確認する
     */
    public function test_registered_food_is_in_index(): void
    {
        // テスト用食材を登録
        $food = Food::create([
            'name' => 'キャベツ',
            'category_id' => $this->categoryId,
            'unit' => '個',
            'min_qty' => 2,
        ]);

        // 食材一覧にアクセス
        $response = $this->get(route('foods.index'));

        // 正常にアクセスできることを確認
        $response->assertOk();

        // 一覧に渡されたデータに登録済み食材が含まれていることを確認
        $response->assertViewHas('foods', function ($foods) use ($food) {
            return $foods->contains('id', $food->id);
        });
    }

    /**
     * 食材が新しく登録された食材から順番に並ぶことをかくにんする
     */
    public function test_foods_are_ordered_by_latest_first(): void
    {
        // 食材を順番に登録
        $firstFood = Food::create([
            'name' => 'キャベツ',
            'category_id' => $this->categoryId,
            'unit' => '個',
            'min_qty' => 3,
        ]);

        $secondFood = Food::create([
            'name' => 'にんじん',
            'category_id' => $this->categoryId,
            'unit' => '個',
            'min_qty' => 3,
        ]);

        // 食材一覧にアクセス
        $response = $this->get(route('foods.index'));

        // 一覧データを取得
        $foods = $response->viewData('foods');

        // 新しく登録した食材が先頭にあることを確認
        $this->assertSame(
            [$secondFood->id, $firstFood->id],
            $foods->pluck('id')->all()
        );
    }

    /**
     * 食材一覧が1ページ10件ずつ表示されることを確認する
     */
    public function test_foods_are_paginated_by_ten(): void
    {
        // テスト用食材を11件登録
        for ($i = 1; $i <= 11; $i++) {
            Food::create([
                'name' => '食材' . $i,
                'category_id' => $this->categoryId,
                'unit' => '個',
                'min_qty' => 2,
            ]);
        }

        // テスト用食材が11件登録されていることを確認    
        $this->assertDatabaseCount('foods', 11);

        // 1ページ目にアクセス
        $response = $this->get(route('foods.index'));

        // 1ページ目の食材件数を確認
        $response->assertOk();

        $foods = $response->viewData('foods');

        $this->assertCount(10, $foods);

        // 2ページ目にアクセス
        $response = $this->get(route('foods.index', ['page' => 2]));

        // 2ページ目の食材件数を確認
        $response->assertOk();

        $foods = $response->viewData('foods');

        $this->assertCount(1, $foods);
        
    }
}