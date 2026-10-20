<!DOCTYPE html>
<html lang="ja">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title','食材管理アプリ')</title>
  </head>
  <body>

    <header>
      <h1>食材管理アプリ</h1>

      <nav>
        @auth
          <div>
            <span>{{ auth()->user()->name }} さん</span>

            <form action="{{ route('logout') }}" method="POST">
              @csrf
              <button type="submit">ログアウト</button>
            </form>
          </div>

          <div>
            <a href="{{ route('foods.index') }}">食材一覧</a>
            <a href="{{ route('foods.create') }}">食材登録</a>
          </div>

          <div>
            <a href="{{ route('stocks.index') }}">在庫一覧</a>
            <a href="{{ route('stocks.in') }}">購入登録</a>
            <a href="{{ route('stocks.out') }}">使用登録</a>
            <a href="{{ route('stocks.logs') }}">入出庫履歴</a>
          </div>
          
        @else
          <a href="{{ route('login') }}">ログイン</a>
        @endauth
            
        
      </nav>
    </header>

    <hr>

    <main>
      @yield('content')
    </main>

  </body>
</html>