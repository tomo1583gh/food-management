@extends('layouts.app')

@section('title', '食材一覧')

{{-- 食材管理専用CSS --}}
@push('styles')
  @vite('resources/css/foods.css')
@endpush

@section('content')

  <h2>食材一覧</h2>

  {{-- 登録・更新の成功メッセージ --}}
  @if (session('success'))
    <p>{{ session('success') }}</p>
  @endif

  {{-- エラーメッセージ --}}
  @if (session('error'))
    <p>{{ session('error') }}</p>
  @endif

  <a href="{{ route('foods.create') }}">新しい食材を登録</a>

  <table>
    <thead>
      <tr>
        <th>食材名</th>
        <th>カテゴリー</th>
        <th>単位</th>
        <th>操作</th>
      </tr>
    </thead>

    <tbody>
      @foreach ($foods as $food)
        <tr>
          <td>{{ $food->name }}</td>
          <td>{{ $food->category->name }}</td>
          <td>{{ $food->unit }}</td>
          <td>
            <div class="food-actions">
              <a href="{{route('foods.edit', $food) }}">編集</a>

              <form action="{{ route('foods.destroy', $food) }}"
                method="POST"
                class="delete-form"
                onsubmit="return confirm('この食材を削除しますか？');">

                @csrf
                @method('DELETE')

                <button type="submit">削除</button>
              </form>
            </div>
          </td>
        </tr>
      @endforeach
    </tbody>
  </table>

@endsection
  