
<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#000000"> <!-- テーマカラー -->
    <!-- アイコン設定（オプション） -->
    <!-- <link rel="icon" href="/icons/icon-192x192.png" sizes="192x192" type="image/png"> -->
    
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', '歌Share') }}</title>
    <meta name="description" content="お気に入りの曲を友達と共有しよう">
    <meta name="keywords" content="カラオケ, 歌, 好きな歌, 共有, 友達, 盛り上がる歌, おすすめ曲">
    
    <?//SNSで表示される際の説明?>
    <meta property="og:url" content="https://skcoco.com/app01">
    <meta property="og:title" content="{{ config('app.name', '歌Share') }}">
    <meta property="og:description" content="お気に入りの曲を友達と共有しよう">




    <!-- jQueryの読み込み -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        // ベースURLを定義
        //const baseUrl = "{{ url('/app01') }}";
        //JS用ルーティングの定義
        const loginUrl                  = "{{ route('login') }}";
        const historyDeleteUrl          = "{{ route('history-delete') }}";
        const favoriteChangeUrl         = "{{ route('favorite-chg') }}";
        const customCategoryChangeUrl   = "{{ route('custom-category-chg') }}";
        const searchListShowUrl         = "{{ route('search-list-show') }}";
        const checkDevicesUrl           = "{{ route('devices-check') }}";
        // APIエンドポイントのURLを定義
        const api_login                 = `{{ url('/api/login') }}`;
        const getMyPlaylistUrl          = `{{ url('/api/myplaylist/get') }}`;

        //configからJSで使用する値を取得
        window.Laravel = {
            user_id: '{{Auth::id() }}',
            vapidPublicKey: '{{ config('webpush.vapid.public_key') }}',
            // 必要な他の変数があれば追加
        };
    </script>
    
    <!-- resources/js/app.js　で一括管理
    <script src="{{ asset('js/notification.js') }}"></script>
    <script src="{{ asset('js/favorite_change.js') }}"></script>
    <script src="{{ asset('js/custom_category_change.js') }}"></script>
    <script src="{{ asset('js/tab.js') }}"></script>
    <script src="{{ asset('js/search_history.js') }}"></script>
    -->
    <!-- Font Awesome CDN アイコン　　　https://fontawesome.com/　　　-->
    <script src="https://kit.fontawesome.com/46a805b165.js" crossorigin="anonymous"></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    <!-- 追加　resources/css,jsで管理　viteでビルドしてアップロード -->
    <!--<link rel="stylesheet" href="{{ asset('/css/style.css') }}">-->
    
    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/css/app.css'])
    <!-- マニフェストファイルの読み込み -->
    <link rel="manifest" href="{{ asset('manifest.json') }}">

    <!-- サービスワーカーの登録 -->
    <script src="{{ asset('registerSW.js') }}"></script>

    <!-- google広告 -->
    <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-1093408896428535"
    crossorigin="anonymous"></script>

    <style>
        .dropdown-item {
            padding: 0.5rem 1.0rem; /* メニューアイテムのパディングを調整 */
            /*margin-bottom: 1.0rem; /* メニューアイテムの下にスペースを追加 */
            /*line-height: 1.5; /* 行間の調整 */
        }
    </style>

</head>
<div class="header"></div>

<body>
    
    <div id="notification">
        <img src="" alt="">
    </div>

    @if (session('message'))
        @php
            $message = session('message');
            $type = session('type');
            $sec = session('sec');
            session()->forget(['message', 'type', 'sec']);
        @endphp
        <script>
        document.addEventListener('DOMContentLoaded', function() {
            showNotification('{{ $message }}', '{{ $type }}', '{{ $sec }}');
        });
        </script>
    @endif


    <div class="container">
            <div id="app">
                <div class="fixed-top">
                    <div class="container-fluid fixed-top-menu">
                        <nav class="navbar navbar-light">
                        
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
                                        @if (Auth::user()->admin_flag)
                                            <a class="dropdown-item" href="{{ route('admin-home') }}">
                                                {{ __('Admin') }}
                                            </a>
                                        @endif
                                            <a class="dropdown-item" href="{{ route('profile-show') }}">
                                                {{ __('Profile') }}
                                            </a>
                                            <a class="dropdown-item" href="{{ route('request-show') }}">
                                                {{ __('Request') }}
                                            </a>
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
                                        <a id="add-to-home-screen" class="dropdown-item" href="#">{{ __('Install to Application') }}</a>
                                    </li>
                                </ul>
                            </div>
                        </nav>
                    </div>
                </div>

                <main class="py-4">
                    <div class="container-fluid fixed-main">
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
    
    <!-- Scripts -->
    @vite(['resources/js/app.js'])
</body>
</html>

