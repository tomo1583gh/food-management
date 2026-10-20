@extends('layouts.app')

@section('title', 'ログイン')

@section('content')

  <h2>ログイン</h2>
  
  <form action="{{ route('login') }}" method="POST">
    @csrf

    <div>
      <label for="email">メールアドレス</label>
      <input
        type="email"
        name="email"
        id="email"
        value="{{ old('email') }}"
        required
      >

      @error('email')
        <p style="color:red;">{{ $message }}</p>
      @enderror
    </div>

    <div>
      <label for="password">パスワード</label>
      <input
        type="password"
        name="password"
        id="password"
        required
      >

      @error('password')
          <p style="color:red;"></p>
      @enderror
    </div>

    <button type="submit">ログイン</button>
  </form>

@endsection