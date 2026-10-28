<?php

namespace App\Http\Controllers;

use App\Models\Food;

class DashboardController extends Controller
{
    public function index()
    {
        // 食材ごとの入庫数・出庫数を集計
        $foods = Food::with('category')
            ->withSum([
                'stockLogs as in_qty' => function ($query) {
                    $query->where('type', 'in');
                }
            ],'qty')
            ->withSum([
                'stockLogs as out_qty' => function ($query) {
                    $query->where('type', 'out');
                }
            ],'qty')
            ->get();

        //登録されている食材の種類数
        $totalFoods = Food::count();

        // 在庫なし・残りわずかの件数
        $outOfStockCount = 0;
        $lowStockCount = 0;

        // 補充候補を格納する配列
        $replenishmentFoods = [];
        
        foreach ($foods as $food) {
            $stockStatus = $food->getStockStatus();

            if ($stockStatus === '在庫なし') {
                $outOfStockCount++;
            } elseif ($stockStatus === '残りわずか') {
                $lowStockCount++;
            } else {
                continue;
            }

            // 補充が必要な食材だけを配列に追加
            $replenishmentFoods[] = [
                'name' => $food->name,
                'current_qty' => $food->getCurrentQty(),
                'min_qty' => $food->min_qty,
                'unit' => $food->unit,
                'status' => $stockStatus,
            ];
        }
        
        return view('dashboard.index', compact(
            'totalFoods',
            'outOfStockCount',
            'lowStockCount',
            'replenishmentFoods'
        ));
    }
}
