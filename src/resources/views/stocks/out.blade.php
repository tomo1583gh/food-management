@extends('layouts.app')

@section('title', '使用登録')

@section('content')

  <h2>使用（出庫）登録</h2>

  <form action="{{ route('stocks.out.store') }}" method="POST">
    @csrf

    <div>
      <label for="food_id">食材</label>

      <select name="food_id" id="food_id" required>
        <option value="">食材を選択してください</option>

        @foreach ($foods as $food)
          <option value="{{ $food->id }}"
            {{ old('food_id') == $food->id ? 'selected' : '' }}>
            {{ $food->name }} ({{ $food->category->name }})
          </option>
        @endforeach
      </select>

      @error('food_id')
        <p style="color: red;">{{ $message }}</p>
      @enderror
    </div>

    <div>
      <label for="qty">使用数量</label>

      <input
        type="number"
        name="qty"
        id="qty"
        min="0.01"
        step="0.01"
        value="{{ old('qty') }}"
        required
      >

      @error('qty')
        <p style="color: red;">{{ $message }}</p>
      @enderror
    </div>

    <button type="submit">使用を登録</button>
  </form>

  <p>
    <a href="{{route('stocks.index') }}">在庫一覧に戻る</a>
  </p>

@endsection