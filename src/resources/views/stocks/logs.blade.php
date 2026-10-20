@extends('layouts.app')

@section('title', '入出庫履歴')

@section('content')

  <h2>入出庫履歴</h2>

  <form action="{{ route('stocks.logs') }}" method="GET">

    <div>
      <label for="food_id">食材</label>

      <select name="food_id" id="food_id">
        <option value="">すべての食材</option>

        @foreach ($foods as $food)
          <option value="{{ $food->id }}"
            {{ request('food_id') == $food->id ? 'selected' : '' }}>
            {{ $food->name }}
          </option>
        @endforeach
      </select>

      @error('food_id')
      <p style="color: red;">{{ $message }}</p>
      @enderror
    </div>

    <div>
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
        <p style="color: red;">{{ $message }}</p>
      @enderror
    </div>

    <button type="submit">検索</button>

    <a href="{{ route('stocks.logs') }}">リセット</a>

  </form>

  <table>
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
          <td>{{ $log->created_at->format('Y/m/d H:i') }}</td>

          <td>{{ $log->food->name }}</td>

          <td>{{ $log->type === 'in' ? '購入' : '使用' }}</td>

          <td>
            {{ $log->type === 'in' ? '+' : '-' }}
            {{ $log->qty }} {{ $log->food->unit }}
          </td>

          <td>{{ $log->user->name }}</td>
        </tr>
      @empty
          <tr>
            <td colspan="5">入出庫履歴はありません。</td>
          </tr>
      @endforelse
    </tbody>
  </table>

  <div>
    {{ $logs->links() }}
  </div>

  <p>
    <a href="{{ route('stocks.index') }}">在庫一覧に戻る</a>
  </p>

@endsection