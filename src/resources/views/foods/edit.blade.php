@extends('layouts.app')

@section('title', '食材編集')

{{-- 食材管理専用CSS --}}
@push('styles')
  @vite('resources/css/foods.css')
@endpush

@section('content')

  <h2>食材編集</h2>

  <form action="{{ route('foods.update', $food) }}" method="POST">
    @csrf
    @method('PUT')

    <div>
      <label for="name">食材名</label>
      <input type="text" id="name" name="name"
        value="{{ old('name', $food->name) }}">

      @error('name')
        <div>{{ $message }}</div>
      @enderror
    </div>

    <div>
      <label for="category_id">カテゴリー</label>
      <select id="category_id" name="category_id">
        @foreach ($categories as $category)
          <option value="{{ $category->id }}"
            {{ old('category_id', $food->category_id) == $category->id ? 'selected' : '' }}>
            {{ $category->name }}
          </option>
        @endforeach
      </select>

      @error('category_id')
        <div>{{ $message }}</div>
      @enderror
    </div>

    <div>
      <label for="unit">単位</label>
      <input type="text" id="unit" name="unit"
        value="{{ old('unit', $food->unit) }}">

      @error('unit')
        <div>{{ $message }}</div>
      @enderror
    </div>

    <div>
    <label for="min_qty">最低在庫数</label>

    <input
        type="number"
        name="min_qty"
        id="min_qty"
        value="{{ old('min_qty', $food->min_qty) }}"
        min="0"
        step="0.01"
        required
    >

    <p id="min_qty_help">
      <small>
        現在庫がこの数量以下になると「残りわずか」と表示されます。
        在庫が0の場合は「在庫なし」と表示されます。
      </small>
    </p>

    @error('min_qty')
        <p class="form-error">{{ $message }}</p>
    @enderror
</div>

    <button type="submit">更新</button>
  </form>

  <a href="{{ route('foods.index') }}">一覧へ戻る</a>

@endsection