@extends('layouts.app')

@section('title', '食材登録')

{{-- 食材管理専用CSS --}}
@push('styles')
  @vite('resources/css/foods.css')
@endpush

@section('content')

  <h2>食材登録</h2>

    <form
      action="{{ route('foods.store') }}"
      method="POST"
      class="food-form"
      novalidate
    >
      @csrf

      <div class="food-form-field">
        <label for="name">食材名</label>
        
        <input
          type="text"
          id="name"
          name="name"
          value="{{ old('name') }}"
          @error('name') aria-invalid="true" @enderror
        >
      
        @error('name')
          <p class="form-error">{{ $message }}</p>
        @enderror
      </div>

      <div class="food-form-field">
        <label for="category_id">カテゴリー</label>
        
        <select
          id="category_id"
          name="category_id"
          @error('category_id') aria-invalid="true" @enderror
        >
          @foreach ($categories as $category)
            <option value="{{ $category->id }}"
              {{ old('category_id') == $category->id ? 'selected' : '' }}>
              {{ $category->name }}
            </option>
          @endforeach
        </select>

        @error('category_id')
            <p class="form-error">{{ $message }}</p>
        @enderror
      </div>

      <div class="food-form-field">
        <label for="unit">単位</label>
        
        <input
          type="text"
          id="unit"
          name="unit"
          value="{{ old('unit') }}"
          @error('unit') aria-invalid="true" @enderror
        >

        @error('unit')
          <p class="form-error">{{ $message }}</p>
        @enderror
      </div>

      <div class="food-form-field">
        <label for="min_qty">最低在庫数</label>
        
        <input
          type="number"
          name="min_qty"
          id="min_qty"
          value="{{old('min_qty', 0) }}"
          min="0"
          step="0.01"
          required
          aria-describedby="min_qty_help"
          @error('min_qty') aria-invalid="true" @enderror
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

      {{-- 更新・戻るボタン --}}
      <div class="food-form-actions">
        <button type="submit">登録</button>

        <a href="{{ route('foods.index') }}" class="food-back-button">
          一覧に戻る
        </a>
      </div>
    </form>

@endsection