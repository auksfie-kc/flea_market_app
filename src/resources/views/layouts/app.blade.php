<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Flea Market App')</title>
    <link rel="stylesheet" href="{{ asset('css/common.css') }}">
    @stack('styles')
</head>

<body>
    <header class="header">

        <div class="header-inner">
            <a href="/" class="header-logo">
                <img src="{{ asset('images/logo.svg') }}" alt="Logo" height="35">
            </a>

            <form action="{{ route('item-index') }}" method="GET" class="header__search-form">
                <input type="hidden" name="tab" value="{{ request('tab', 'all') }}">
                <input
                    type="text"
                    name="keyword"
                    value="{{ request('keyword') }}"
                    class="header__search-input"
                    placeholder="何をお探しですか？">
                <button type="submit" class="header__search-button">検索</button>
            </form>

            <nav class="header-nav">
                <ul class="header-nav-list">
                    @auth
                    <li>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="logout-btn">ログアウト</button>
                        </form>
                    </li>
                    @endauth

                    @guest
                    <li><a href="{{ route('login') }}">ログイン</a></li>
                    @endguest

                    @auth
                    <li><a href="{{ route('profile.index') }}">マイページ</a></li>
                    <li><a href="{{ route('sell.create') }}" class="sell-btn">出品</a></li>
                    @endauth

                    @guest
                    <li><span class="menu-disabled">マイページ</span></li>
                    <li><span class="sell-btn sell-btn--disabled">出品</span></li>
                    @endguest
                </ul>
            </nav>
        </div>

    </header>


    <main>
        @yield('content')
    </main>

</body>

</html>