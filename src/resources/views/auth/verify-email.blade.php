@extends('layouts.app')

@section('title', 'メール認証')

@push('styles')
  @vite('resources/css/auth.css')
@endpush

@section('content')

<div class="auth-container">

  <h2 class="auth-title">メールアドレスの認証</h2>

  <p>
    ご登録いただいたメールアドレスに
    認証メールを送信しました。
  </p>

  <p>
    メールに記載されたリンクをクリックして
    メールアドレスの認証を完了してください。
  </p>

  {{-- 認証メール再送信の完了メッセージ --}}
  @if (session('status') === 'verification-link-sent')
    <p>
      認証メールを再送信しました。
    </p>
  @endif

  {{-- 認証メール再送信 --}}
  <form
    action="{{ route('verification.send') }}"
    method="POST"
    class="auth-form"
  >
    @csrf

    <button type="submit" class="auth-form__button">
      認証メールを再送信
    </button>
  </form>

</div>

@endsection