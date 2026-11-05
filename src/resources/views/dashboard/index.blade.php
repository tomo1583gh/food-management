@extends('layouts.app')

@section('title', 'ダッシュボード')

{{-- ダッシュボード管理専用CSS --}}
@push('styles')
  @vite('resources/css/dashboard.css')
@endpush

@section('content')

  <h2>食材管理ダッシュボード</h2>

  {{-- 集計情報 --}}
  <div class="dashboard-summary">

    <div class="dashboard-card">
      <h3>登録食材数</h3>
      <p class="dashboard-card-value">
        {{ $totalFoods }} <span>種類</span>
      </p>
    </div>

    <div class="dashboard-card dashboard-card-empty">
      <h3>在庫なし</h3>
      <p class="dashboard-card-value">
        {{ $outOfStockCount }} <span>種類</span>
      </p>
    </div>

    <div class="dashboard-card dashboard-card-low">
      <h3>残りわずか</h3>
      <p class="dashboard-card-value">
        {{ $lowStockCount }}  <span>種類</span>
      </p>
    </div>
  
  </div>

  {{-- 補充候補一覧 --}}
  <selection class="dashboard-selection">
    <h3>補充候補一覧</h3>

    <table class="dashboard-table">
      <thead>
        <tr>
          <th>食材名</th>
          <th>現在庫</th>
          <th>最低在庫数</th>
          <th>在庫状況</th>
        </tr>
      </thead>

      <tbody>
        @forelse ($replenishmentFoods as $food)
          <tr>
            <td>
              <span class="dashboard-label">食材名</span>
              <span class="dashboard-value">
                {{ $food['name'] }}
              </span>
            </td>

            <td>
              <span class="dashboard-label">現在庫</span>
              <span class="dashboard-value">
                {{ $food['current_qty'] }} {{ $food['unit'] }}
              </span>
            </td>

            <td>
              <span class="dashboard-label">最低在庫数</span>
              <span class="dashboard-value">
                {{ $food['min_qty'] }} {{ $food['unit'] }}
              </span>
            </td>

            <td>
              <span class="dashboard-label">在庫状況</span>
              <span class="dashboard-value">
                @if ($food['status'] === '在庫なし')
                  <span class="dashboard-status dashboard-status--empty">
                    在庫なし
                  </span>
                @elseif ($food['status'] === '残りわずか')
                  <span class="dashboard-status dashboard-status--low">
                    残りわずか
                  </span>
                @else
                  <span class="dashboard-status">
                    {{ $food['status'] }}
                  </span>
                @endif
              </span>
            </td>
          </tr>
        @empty
          <tr class="dashboard-empty-row">
            <td colspan="4">
              補充が必要な食材はありません。
            </td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </selection>
      
@endsection