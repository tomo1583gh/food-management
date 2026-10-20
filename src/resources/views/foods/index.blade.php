@extends('layouts.app')

@section('title', '食材一覧')

@section('content')

  <h1>食材一覧</h1>

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
            <a href="{{route('foods.edit', $food) }}">編集</a>

            <form action="{{ route('foods.destroy', $food) }}"
              method="POST"
              style="display: inline;"
              onsubmit="return confirm('この得材を削除しますか？');">

              @csrf
              @method('DELETE')

              <button type="submit">削除</button>
            </form>
          </td>
        </tr>
      @endforeach
    </tbody>
  </table>

@endsection
  