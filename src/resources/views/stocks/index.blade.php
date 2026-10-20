@extends('layouts.app')

@section('title', '在庫一覧')

@section('content')

  <h2>在庫一覧</h2>

  <table>
    <thead>
      <tr>
        <th>食材名</th>
        <th>カテゴリー</th>
        <th>現在庫</th>
      </tr>
    </thead>

    <tbody>
      @forelse ($foods as $food)
        @php
            $inQty = $food->stockLogs->where('type', 'in')->sum('qty');
            $outQty = $food->stockLogs->where('type', 'out')->sum('qty');
            $currentQty = $inQty - $outQty;
        @endphp

        <tr>
          <td>{{ $food->name }}</td>
          <td>{{ $food->category->name }}</td>
          <td>{{ $currentQty }} {{ $food->unit }}</td>
        </tr>
      @empty
          <tr>
            <td colspan="3">食材が登録されていません。</td>
          </tr>
      @endforelse
    </tbody>
  </table>

@endsection