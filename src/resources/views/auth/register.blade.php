@extends('layouts.app')

@section('title', '会員登録')

{{-- 認証画面用CSS --}}
@push('styles')
  @vite('resources/css/auth.css')
@endpush

@section('content')

<div class="auth-container">

  <h2 class="auth-title">会員登録</h2>

  <form
    action="{{ route('register.store') }}"
    method="POST"
    class="auth-form"
    novalidate
  >
    @csrf

    {{-- ユーザー名 --}}
    <div class="auth-form__group">
      <label for="name" class="auth-form__label">
        ユーザー名
      </label>

      <input
        type="text"
        name="name"
        id="name"
        class="auth-form__input @error('name') auth-form__input--error @enderror"
        value="{{ old('name') }}"
        autocomplete="name"
        maxlength="255"
        required
      >

      @error('name')
        <p class="auth-form__error">{{ $message }}</p>
      @enderror
    </div>

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
        maxlength="255"
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
        class="auth-form__input @error('password') auth-form__input--error @enderror"
        autocomplete="new-password"
        required
      >

      @error('password')
        <p class="auth-form__error">{{ $message }}</p>
      @enderror
    </div>

    {{-- パスワード確認 --}}
    <div class="auth-form__group">
      <label for="password_confirmation" class="auth-form__label">
        パスワード確認
      </label>

      <input
        type="password"
        name="password_confirmation"
        id="password_confirmation"
        class="auth-form__input"
        autocomplete="new-password"
        required
      >
    </div>

    {{-- 会員登録ボタン --}}
    <button type="submit" class="auth-form__button">
      会員登録
    </button>

  </form>

  {{-- ログイン画面へのリンク --}}
  <p class="auth-link">
    すでにアカウントをお持ちの方は
    <a href="{{ route('login') }}">ログイン</a>
  </p>

</div>

@endsection