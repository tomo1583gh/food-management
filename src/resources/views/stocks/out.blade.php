@extends('layouts.app')

@section('title', '使用登録')

{{-- 在庫管理専用CSS --}}
@push('styles')
  @vite('resources/css/stocks.css')
@endpush

@section('content')

  <h2>使用（出庫）登録</h2>

  <form
    action="{{ route('stocks.out.store') }}"
    novalidate method="POST"
    class="stock-form"
    novalidate
  >
    @csrf

    {{-- 食材選択 --}}
    <div class="stock-form-field">
      <label for="food_id">食材</label>

      <select
        name="food_id"
        id="food_id"
        required
      >
        <option value="">食材を選択してください</option>

        @foreach ($foods as $food)
          <option value="{{ $food->id }}"
            {{ old('food_id') == $food->id ? 'selected' : '' }}
          >
            {{ $food->name }} ({{ $food->category->name }})
          </option>
        @endforeach
      </select>

      @error('food_id')
        <p class="form-error">{{ $message }}</p>
      @enderror
    </div>

    {{-- 購入数量 --}}
    <div class="stock-form-field">
      <label for="qty">使用数量</label>

      <input
        type="number"
        name="qty"
        id="qty"
        min="0.01"
        step="0.01"
        value="{{ old('qty') }}"
        required
        @error('qty') aria-invalid="true" @enderror
      >

      @error('qty')
        <p class="form-error">{{ $message }}</p>
      @enderror
    </div>

    {{-- 操作ボタン --}}
    <div class="stock-form-actions">
      <button type="submit">使用を登録</button>

      <a
        href="{{route('stocks.index') }}"
        class="stock-back-button"
      >
        在庫一覧に戻る
      </a>
    </div>
  </form>

@endsection