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

  <div class="food-create-action">
    <a href="{{ route('foods.create') }}" class="food-create-button">
      ＋ 新しい食材を登録
    </a>
  </div>

  <table class="food-table">
    <thead>
      <tr>
        <th>食材名</th>
        <th>カテゴリー</th>
        <th>単位</th>
        <th>操作</th>
      </tr>
    </thead>

    <tbody>
      @forelse ($foods as $food)
        <tr>
          <td>
            <span class="food-label">食材名</span>
            <span class="food-value">{{ $food->name }}</span>
          </td>

          <td>
            <span class="food-label">カテゴリー</span>
            <span class="food-value">{{ $food->category->name }}</span>
          </td>

          <td>
            <span class="food-label">単位</span>
            <span class="food-value">{{ $food->unit }}</span>
          </td>

          <td>
            <span class="food-label">操作</span>

            <div class="food-actions">
              <a href="{{route('foods.edit', $food) }}">編集</a>

              <form 
                action="{{ route('foods.destroy', $food) }}"
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

      @empty
        <tr class="food-empty-row">
          <td colspan="4">
            食材が登録されていません。
          </td>
        </tr>
      @endforelse
    </tbody>
  </table>

  {{-- ページネーション --}}
  <div class="app-pagination">
    {{ $foods->links('pagination::bootstrap-5') }}
  </div>

@endsection
  