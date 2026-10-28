@extends('layouts.app')

@section('title', 'ダッシュボード')

{{-- ダッシュボード管理専用CSS --}}
@push('styles')
  @vite('resources/css/dashboard.css')
@endpush

@section('content')

  <h2>食材管理ダッシュボード</h2>

  {{-- 登録食材数 --}}
  <div>
    <h3>登録食材数</h3>

    <p>{{ $totalFoods }} 種類</p>
  </div>

  {{-- 在庫なし --}}
  <div>
    <h3>在庫なし</h3>
    <p>{{ $outOfStockCount }} 種類</p>
  </div>

  {{-- 残りわずか --}}
  <div>
    <h3>残りわずか</h3>
    <p>{{ $lowStockCount }} 種類</p>
  </div>
  
  <h3>補充候補一覧</h3>

  <table>
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
          <td>{{ $food['name'] }}</td>
          <td>{{ $food['current_qty'] }} {{ $food['unit'] }}</td>
          <td>{{ $food['min_qty'] }} {{ $food['unit'] }}</td>
          <td>{{ $food['status'] }}</td>
        </tr>
      @empty
        <tr>
          <td colspan="4">補充が必要な食材はありません。</td>
        </tr>
      @endforelse
    </tbody>
  </table>  
      
@endsection