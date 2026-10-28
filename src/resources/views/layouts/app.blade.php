<!DOCTYPE html>
<html lang="ja">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title','食材管理アプリ')</title>

    {{-- 共通CSS --}}
    @vite([
      'resources/css/app.css',
      'resources/css/header.css'
    ])

    {{-- 画面別CSS --}}
    @stack('styles')

  </head>
  
  <body>

    <header class="site-header">

      <div class="header-top">
        <h1 class="site-title">食材管理アプリ</h1>

        @auth
          <div class="header-user">
            <span>{{ auth()->user()->name }} さん</span>

            <form action="{{ route('logout') }}" method="POST">
              @csrf
              <button type="submit">ログアウト</button>
            </form>
          </div>
        @endauth
      </div>

      @auth
        {{-- PC用ナビゲーション --}}    
        <nav class="header-nav-pc" aria-label="メインメニュー">
          <a href="{{ route('dashboard.index') }}">ダッシュボード</a>
          <a href="{{ route('foods.index') }}">食材一覧</a>
          <a href="{{ route('foods.create') }}">食材登録</a>
          <a href="{{ route('stocks.index') }}">在庫一覧</a>
          <a href="{{ route('stocks.in') }}">購入登録</a>
          <a href="{{ route('stocks.out') }}">使用登録</a>
          <a href="{{ route('stocks.logs') }}">入出庫履歴</a>
        </nav>

        {{-- スマートフォン用ナビゲーション --}}
        <details class="header-nav-mobile">
          <summary>メニュー</summary>

          <nav aria-label="スマートフォン用メニュー">
            <a href="{{ route('dashboard.index') }}">ダッシュボード</a>
            <a href="{{ route('foods.index') }}">食材一覧</a>
            <a href="{{ route('foods.create') }}">食材登録</a>
            <a href="{{ route('stocks.index') }}">在庫一覧</a>
            <a href="{{ route('stocks.in') }}">購入登録</a>
            <a href="{{ route('stocks.out') }}">使用登録</a>
            <a href="{{ route('stocks.logs') }}">入出庫履歴</a>
          </nav>
        </details>
      @else
        <nav class="header-nav-guest" aria-label="ゲストメニュー">
          <a href="{{ route('login') }}">ログイン</a>
        </nav>
      @endauth
    
    </header>

    <hr>

    <main>
      @yield('content')
    </main>

  </body>
</html>