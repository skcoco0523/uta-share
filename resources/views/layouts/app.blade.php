
<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', '歌Share') }}</title>

    <!-- notification.js を読み込む -->
    <script src="{{ asset('js/notification.js') }}"></script>

    <!-- jQueryの読み込み -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    <!-- 追加 -->
    <link rel="stylesheet" href="{{ asset('/css/style.css') }}">
    
    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    
</head>
<div class="header"></div>

<body>
    @if (session('error'))
        <script>
            alert('{{ session('error') }}');
        </script>
    @endif
    
    <div id="notification">
        <img src="{{ asset('img/icon/defo.gif') }}" alt="通知GIF">
    </div>

    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm fixed-top">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    {{ config('app.name', '歌Share') }}
                </a>
                <!--
                    <a class="navbar-brand" href="{{ url('/') }}">
                        {{ config('app.name', 'KareShare') }}
                    </a>
                -->
                @auth
                    <span style="margin-left: auto;">{{ Auth::user()->name }}</span>&nbsp;
                @endauth
                @guest
                    <span style="margin-left: auto;">ゲストユーザー</span>&nbsp;
                @endguest
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav me-auto">

                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ms-auto">
                        <!-- Authentication Links -->
                        <li class="nav-item dropdown">
                        @guest
                            @if (Route::has('login'))
                                <!--<a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>-->
                                <a class="dropdown-item" href="{{ route('login') }}">{{ __('Login') }}</a>
                            @endif

                            @if (Route::has('register'))
                                <!--<a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>-->
                                <a class="dropdown-item" href="{{ route('register') }}">{{ __('Register') }}</a>
                            @endif
                        @else
                                <a class="dropdown-item" href=""
                                    onclick="event.preventDefault();
                                                    document.getElementById('').submit();">
                                    {{ __('Profile') }}
                                </a>
                                
                            @if (Auth::user()->admin_flag)
                                <a class="dropdown-item" href="{{ route('admin-home') }}">
                                    {{ __('Admin') }}
                                </a>
                            @endif
                                <a class="dropdown-item" href="{{ route('logout') }}"
                                    onclick="event.preventDefault();
                                                    document.getElementById('logout-form').submit();">
                                    {{ __('Logout') }}
                                </a>

                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            <!--</div>-->
                        @endguest
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4">
            @yield('content')
        </main>
    </div>
    <div class="footer"></div>
</html>
