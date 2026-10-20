@extends('layouts.app')

@section('title', '食材登録')

@section('content')

  <h1>食材登録</h1>

    <form action="{{ route('foods.store') }}" method="POST">
      @csrf

      <div>
        <label for="name">食材名</label>
        <input type="text" id="name" name="name">
      
        @error('name')
          <div>{{ $message }}</div>
        @enderror
      </div>

      <div>
        <label for="category_id">カテゴリー</label>
        <select is="category_id" name="category_id">
          @foreach ($categories as $category)
            <option value="{{ $category->id }}"
              {{ old('category_id') == $category->id ? 'selected' : '' }}>
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
        <input type="text" id="unit" name="unit">
      
        @error('unit')
          <div>{{ $message }}</div>
        @enderror
      </div>

      <button type="submit">登録</button>
    </form>

    <a href="{{ route('foods.index') }}">一覧へ戻る</a>

@endsection