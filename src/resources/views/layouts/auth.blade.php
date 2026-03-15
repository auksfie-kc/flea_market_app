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

        <div class="header-left">
            <a href="/">
                <img src="{{ asset('images/logo.svg') }}" alt="Logo" height="35">
            </a>
        </div>
    </header>


    <main>
        @yield('content')
    </main>

</body>

</html>