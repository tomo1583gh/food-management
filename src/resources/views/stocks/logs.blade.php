@extends('layouts.app')

@section('title', '入出庫履歴')

{{-- 在庫管理専用CSS --}}
@push('styles')
  @vite('resources/css/stocks.css')
@endpush

@section('content')

  <h2>入出庫履歴</h2>

  {{--  検索フォーム --}}
  <form
    action="{{ route('stocks.logs') }}"
    method="GET"
    class="stock-search-form"
  >

    <div class="stock-search-field">
      <label for="food_id">食材</label>

      <select name="food_id" id="food_id">
        <option value="">すべての食材</option>

        @foreach ($foods as $food)
          <option
            value="{{ $food->id }}"
            {{ request('food_id') == $food->id ? 'selected' : '' }}
          >
            {{ $food->name }}
          </option>
        @endforeach
      </select>

      @error('food_id')
      <p class="form-error">{{ $message }}</p>
      @enderror
    </div>


    <div class="stock-search-field">
      <label for="type">区分</label>

      <select name="type" id="type">
        <option value="">すべて</option>
        <option value="in" {{ request('type') === 'in' ? 'selected' : '' }}>
          購入
        </option>
        <option value="out" {{ request('type') === 'out' ? 'selected' : '' }}>
          使用
        </option>
      </select>

      @error('type')
        <p class="form-error">{{ $message }}</p>
      @enderror
    </div>

    <div class="stock-search-actions">
    <button type="submit">検索</button>

      <a href="{{ route('stocks.logs') }}">リセット</a>
    </div>

  </form>

  {{-- 入出庫履歴一覧 --}}
  <table class="stock-table stock-log-table">
    <thead>
      <tr>
        <th>日時</th>
        <th>食材名</th>
        <th>区分</th>
        <th>数量</th>
        <th>登録者</th>
      </tr>
    </thead>

    <tbody>
      @forelse ($logs as $log)
        <tr>
          <td>
            <span class="stock-label">日時</span>
            <span class="stock-value">
              {{ $log->created_at->format('Y/m/d H:i') }}
            </span>
          </td>

          <td>
            <span class="stock-label">食材名</span>
            <span class="stock-value">
              {{ $log->food->name }}
            </span>
          </td>

          <td>
            <span class="stock-label">区分</span>
            <span class="stock-value">
              {{ $log->type === 'in' ? '購入' : '使用' }}
            </span>
          </td>

          <td>
            <span class="stock-label">数量</span>
            <span class="stock-value">
              {{ $log->type === 'in' ? '+' : '-' }}
              {{ $log->qty }} {{ $log->food->unit }}
            </span>
          </td>

          <td>
            <span class="stock-label">登録者</span>
            <span class="stock-value">
              {{ $log->user->name }}
            </span>
          </td>
        </tr>
      @empty
          <tr class="stock-log-empty-row">
            <td colspan="5">入出庫履歴はありません。</td>
          </tr>
      @endforelse
    </tbody>
  </table>

  {{-- ページネーション --}}
  <div class="app-pagination">
    {{ $logs->links('pagination::bootstrap-5') }}
  </div>

  {{-- 戻るボタン --}}
  <div class="stock-log-footer">
    <a href="{{ route('stocks.index') }}" class="stock-back-button">
      在庫一覧に戻る
    </a>
  </div>

@endsection