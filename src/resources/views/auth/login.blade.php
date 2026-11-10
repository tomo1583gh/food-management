@extends('layouts.app')

@section('title', 'ログイン')

@push('styles')
  @vite('resources/css/auth.css')
@endpush

@section('content')

<div class="auth-container">

  <h2 class="auth-title">ログイン</h2>
  
  <form 
    action="{{ route('login') }}"
    method="POST"
    class="auth-form"
    novalidate
  >
    @csrf

    {{-- メールアドレス --}}
    <div class="auth-form__group">
      <label for="email" class="auth-form__label">
        メールアドレス
      </label>

      <input
        type="email"
        name="email"
        id="email"
        class="auth-form__input @error('email') auth-form__input--error @enderror"
        value="{{ old('email') }}"
        autocomplete="email"
        required
      >

      @error('email')
        <p class="auth-form__error">{{ $message }}</p>
      @enderror
    </div>

    {{-- パスワード --}}
    <div class="auth-form__group">
      <label for="password" class="auth-form__label">
        パスワード
      </label>

      <input
        type="password"
        name="password"
        id="password"
        class="auth-form__input@error('password') auth-form__input--error @enderror"
        autocomplete="current-password"
        required
      >

      @error('password')
          <p class="auth-form__error">{{ $message }}</p>
      @enderror
    </div>

    <button type="submit" class="auth-form__button">
      ログイン
    </button>

  </form>

  {{-- 会員登録画面へのリンク --}}
  <p class="auth-link">
    アカウントをお持ちでない方は
    <a href="{{ route('register') }}">会員登録はこちら</a>
  </p>

</div>

@endsection