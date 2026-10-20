<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Food;
use App\Http\Requests\StoreFoodRequest;
use App\Http\Requests\UpdateFoodRequest;

class FoodController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $foods = Food::with('category')->get();

        return view('foods.index', compact('foods'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::all();

        return view('foods.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreFoodRequest $request)
    {
        Food::create($request->validated());

        return redirect()
            ->route('foods.index')
            ->with('success', '食材を登録しました。');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Food $food)
    {
        $categories = Category::orderBy('name')->get();

        return view('foods.edit', compact('food', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateFoodRequest $request, Food $food)
    {
        $food->update($request->validated());

        return redirect()
            ->route('foods.index')
            ->with('success', '食材を更新しました。');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Food $food)
    {
        if ($food->stockLogs()->exists()) {
            return redirect()
                ->route('foods.index')
                ->with('error', '入出庫履歴がある食材は削除できません。');
        }

        $food->delete();

        return redirect()
            ->route('foods.index')
            ->with('success', '食材を削除しました。');
    }

    
}
