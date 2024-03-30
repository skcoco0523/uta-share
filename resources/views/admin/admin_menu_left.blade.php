
    <div class="menu_section">
    @if(isset($ope_type))
        @switch($tab_name)
            @case("音楽")
                {{$tab_name}}メニュー
                <li><a href="{{ route('admin-music-reg') }}">新規登録 </a></li>
                {{--<li><a href="{{ route('admin-music-bulkreg') }}">一括登録 </a></li>--}}
                <li><a href="{{ route('admin-music-search') }}">検索/変更/削除</a></li>
                @break
            @case("アルバム")
                {{$tab_name}}メニュー
                <li><a href="{{ route('admin-album-reg') }}">新規登録 </a></li>
                <li><a href="{{ route('admin-album-chgdetail') }}">詳細変更</a></li>
                <li><a href="{{ route('admin-album-search') }}">検索/変更/削除</a></li>
                @break
            @case("アーティスト")
                {{$tab_name}}メニュー
                <li><a href="{{ route('admin-artist-reg') }}">新規登録 </a></li>
                <li><a href="{{ route('admin-artist-search') }}">検索/変更/削除</a></li>
                @break
            @case("プレイリスト")
                {{$tab_name}}メニュー
                <li><a href="{{ route('admin-playlist-reg') }}">新規登録 </a></li>
                <li><a href="{{ route('admin-playlist-search') }}">検索/変更/削除</a></li>
                @break
            @case("おすすめ")
                {{$tab_name}}メニュー
                <li><a href="{{ route('admin-recommend-reg') }}">新規登録 </a></li>
                <li><a href="{{ route('admin-recommend-chg') }}">詳細変更</a></li>
                <li><a href="{{ route('admin-recommend-search') }}">検索/変更/削除</a></li>
                @break
            @endswitch
        @endif
    
    </div>
