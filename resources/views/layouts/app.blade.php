
<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', '歌Share') }}</title>

    <!-- jQueryの読み込み -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- JS用ルーティングの定義 -->
    <script>
        const loginUrl                  = "{{ route('login') }}";
        const historyDeleteUrl          = "{{ route('history-delete') }}";
        const favoriteChangeUrl         = "{{ route('favorite-chg') }}";
        const customCategoryChangeUrl   = "{{ route('custom-category-chg') }}";
        const searchListShowUrl         = "{{ route('search-list-show') }}";
    </script>
    <!-- 通知関連JSを読み込む -->
    <script src="{{ asset('js/notification.js') }}"></script>
    <!-- お気に入り関連JSを読み込む -->
    <script src="{{ asset('js/favorite_change.js') }}"></script>
    <!-- カスタムカテゴリ関連JSを読み込む -->
    <script src="{{ asset('js/custom_category_change.js') }}"></script>
    <!-- tab操作関連JSを読み込む -->
    <script src="{{ asset('js/tab.js') }}"></script>
    <!-- 検索履歴関連JSを読み込む -->
    <script src="{{ asset('js/search_history.js') }}"></script>

    <!-- Font Awesome CDN アイコン　　　https://fontawesome.com/　　　-->
    <script src="https://kit.fontawesome.com/46a805b165.js" crossorigin="anonymous"></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    <!-- 追加 -->
    <link rel="stylesheet" href="{{ asset('/css/style.css') }}">
    
    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])

    <!-- google広告 -->
    <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-1093408896428535"
    crossorigin="anonymous"></script>
</head>
<div class="header"></div>

<body>
    
    <div id="notification">
        <img src="" alt="通知GIF">
    </div>

    @if (session('message'))
    <script>
        showNotification('{{ session('message') }}', '{{ session('type') }}', '{{ session('sec') }}');
        @php
            session()->forget(['message', 'type', 'sec']);
        @endphp
        //alert('{{ session('error') }}');
    </script>
    @endif


    <div class="container">
            <div id="app">
                <div class="fixed-top">
                    <div class="container-fluid fixed-top-menu">
                        <nav class="navbar navbar-expand-md navbar-light">
                        
                            <a class="navbar-brand" href="{{ url('/') }}">
                                {{ config('app.name', '歌Share') }}
                            </a>
                            @auth
                                <span style="margin-left: auto;">{{ Auth::user()->name }}</span>&nbsp;
                            @else
                                <span style="margin-left: auto;">ゲストユーザー</span>&nbsp;
                            @endauth
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
                                    @auth
                                            <a class="dropdown-item" href="{{ route('profile-show') }}">
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
                                    @else
                                        @if (Route::has('login'))
                                            <a class="dropdown-item" href="{{ route('login') }}">{{ __('Login') }}</a>
                                        @endif
                                        @if (Route::has('register'))
                                            <a class="dropdown-item" href="{{ route('register') }}">{{ __('Register') }}</a>
                                        @endif
                                    @endauth
                                    </li>
                                </ul>
                            </div>
                        </nav>
                    </div>
                </div>

                <main class="py-4">
                    <div class="container-fluid" style="max-width: 600px;">
                    @yield('content')
                    </div>
                </main>
            </div>
            <div class="footer">
                <?//ナビ?>   
                @include('layouts.nav_menu')
            </div>
        </div>
    
    </div>
</html>
