@extends('layouts.app')

@section('title', 'ログインの一時制限')

@push('styles')
  @vite('resources/css/auth.css')
@endpush

@section('content')

<div class="auth-container">

  <h2 class="auth-title">
    ログインを一時的に制限しています
  </h2>

  <p>
    短時間にログイン操作が繰り返されたため、
    一時的にアクセスを制限しています。
  </p>

  <p>
    1分ほど時間をおいてから、
    再度ログインをお試しください。
  </p>

  <p class="auth-link">
    <a href="{{ route('login') }}">
      ログイン画面へ戻る
    </a>
  </p>

</div>

@endsection