<?php

namespace App\Http\Controllers;

use App\Models\Food;
use App\Models\StockLog;
use App\Models\Category;
use App\Http\Requests\StoreStockInRequest;
use App\Http\Requests\StoreStockOutRequest;
use App\Http\Requests\SearchStockLogRequest;
use App\Http\Requests\SearchStockRequest;

class StockController extends Controller
{
    public function index(SearchStockRequest $request)
    {
        $data = $request->validated();

        // カテゴリ一覧を取得
        $categories = Category::orderBy('id')->get();

        // 在庫一覧を取得
        $foods = Food::with('category', 'stockLogs')
            ->when(!empty($data['q']), function ($query) use ($data) {
                $query->where('name', 'like', '%' . $data['q'] . '%');
            })
            ->when(!empty($data['category_id']), function ($query) use ($data) {
                $query->where('category_id', $data['category_id']);
            })
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString();

        return view('stocks.index', compact('foods', 'categories'));
    }

    public function createIn()
    {
        $foods = Food::with('category')->orderBy('name')->get();

        return view('stocks.in', compact('foods'));
    }

    public function storeIn(StoreStockInRequest $request)
    {
        $data = $request->validated();

        StockLog::create([
            'food_id' => $data['food_id'],
            'type' => 'in',
            'qty' => $data['qty'],
            'user_id' => $request->user()->id,
        ]);

        return redirect()
            ->route('stocks.index')
            ->with('success', '購入情報を登録しました。');
    }

    public function createOut()
    {
        $foods = Food::with('category')->orderBy('name')->get();

        return view('stocks.out', compact('foods'));
    }

    public function storeOut(StoreStockOutRequest $request)
    {
        // バリデーション済みの入力値
        $data = $request->validated();

        // 対象の食材を取得
        $food = Food::with('stockLogs')->findOrFail($data['food_id']);

        // 入庫数量の合計
        $inQty = $food->stockLogs()
            ->where('type', 'in')
            ->sum('qty');

        // 出庫数量の合計
        $outQty = $food->stockLogs
            ->where('type', 'out')
            ->sum('qty');

        // 現在庫
        $currentQty = $inQty - $outQty;

        // 在庫不足の確認
        if ($data['qty'] > $currentQty) {
            return back()
                ->withErrors([
                    'qty' => '在庫が不足しています。現在庫：' . $currentQty . $food->unit,
                ])
                ->withInput();
        }

        // 出庫履歴を保存
        StockLog::create([
            'food_id' => $data['food_id'],
            'type' => 'out',
            'qty' => $data['qty'],
            'user_id' => $request->user()->id,
        ]);

        return redirect()
            ->route('stocks.index')
            ->with('success', '使用情報を登録しました。');
    }

    public function logs(SearchStockLogRequest $request)
    {
        $foods = Food::orderBy('name')->get();

        $data = $request->validated();

        $logs = StockLog::with(['food', 'user'])
            ->when(!empty($data['food_id']), function ($query) use ($data) {
                $query->where('food_id', $data['food_id']);
            })
            ->when(!empty($data['type']), function ($query) use ($data) {
                $query->where('type', $data['type']);
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('stocks.logs', compact('logs', 'foods'));
    }
}
