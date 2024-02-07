<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', '歌Share') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    {{ config('app.name', '歌Share') }}
                </a>
             
                @auth
                    <span style="margin-right: auto;">{{ Auth::user()->name }}</span>&nbsp;
                @endauth
                <ul class="nav nav-underline">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin-music-reg') }}">音楽</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin-album-reg') }}">アルバム</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin-artist-reg') }}">アーティスト</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin-playlist-reg') }}">プレイリスト</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin-recommend-reg') }}">おすすめ</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href=""></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href=""></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href=""></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href=""></a>
                    </li>
                </ul>
            </div>
        </nav>

        <main class="py-4">
            @yield('content')
        </main>
    </div>
</body>
</html>
