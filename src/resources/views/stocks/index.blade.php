@extends('layouts.app')

@section('title', '在庫一覧')

{{-- 在庫管理専用CSS --}}
@push('styles')
  @vite('resources/css/stocks.css')
@endpush

@section('content')

  <h2>在庫一覧</h2>

  <form action="{{ route('stocks.index') }}"
    method="GET"
    class="stock-search-form"
  >

    <div class="stock-search-field">
      <label for="q">食材名</label>

      <input
        type="text"
        name="q"
        id="q"
        value="{{ request('q') }}"
        placeholder="食材名を入力"
      >

      @error('q')
        <p class="form-error">{{ $message }}</p>
      @enderror
    </div>

    <div class="stock-search-field">
      <label for="category_id">カテゴリ</label>

      <select name="category_id" id="category_id">
        <option value="">すべてのカテゴリ</option>

        @foreach ($categories as $category)
          <option value="{{ $category->id }}"
            {{ request('category_id') == $category->id ? 'selected' : '' }}>
            {{ $category->name }}
          </option>
        @endforeach
      </select>

      @error('category_id')
        <p class="form-error">{{ $message }}</p>
      @enderror
    </div>

    <div class="stock-search-actions">
      <button type="submit">検索</button>

      <a href="{{ route('stocks.index') }}">リセット</a>
    </div>
  </form>

  <table class="stock-table">
    <thead>
      <tr>
        <th>食材名</th>
        <th>カテゴリー</th>
        <th>現在庫</th>
        <th>最低在庫数</th>
        <th>在庫状況</th>
      </tr>
    </thead>

    <tbody class="stock-table">
      @forelse ($foods as $food)
        @php
            // Foodモデルのメソッドで現在庫を取得
            $currentQty = $food->getCurrentQty();
            $stockStatus = $food->getStockStatus();
        @endphp

        <tr>
          <td date-table="食材名">
            <span class="stock-label">食材名</span>
            <span class="stock-value">{{ $food->name }}</span>
          </td>

          <td date-table="カテゴリー">
            <span class="stock-label">カテゴリー</span>
            <span class="stock-value">{{ $food->category->name }}</span>
          </td>

          <td date-table="現在庫">
            <span class="stock-label">現在庫</span>
            <span class="stock-value">{{ $currentQty }} {{ $food->unit }}</span>
          </td>

          <td date-table="最低在庫数">
            <span class="stock-label">最低在庫数</span>
            <span class="stock-value">{{ $food->min_qty }} {{ $food->unit }}</span>
          </td>

          <td date-table="在庫状況">
            <span class="stock-label">在庫状況</span>

            <span class="stock-value">
              <span class="stock-status
              @if ($stockStatus === '在庫あり')
                stock-status--available
              @elseif ($stockStatus === '残りわずか')
                stock-status--low
              @else
                stock-status--empty
              @endif
              ">
                {{ $stockStatus }}
              </span>
            </span>
          </td>
        </tr>
      @empty
          <tr>
            <td colspan="5">食材が登録されていません。</td>
          </tr>
      @endforelse
    </tbody>
  </table>

  <div class="stock-pagination">
    {{ $foods->links('pagination::bootstrap-5') }}
  </div>

@endsection