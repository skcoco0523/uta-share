
@if(isset($ope_type))
    @switch($ope_type)
        {{---------------- 音楽メニュー ------------------}}
        @case("music_reg")
            @include('admin.admin_music_reg')
            @break
        {{---------------- アルバムメニュー ------------------}}
        @case("album_reg")
            @include('admin.admin_album_reg')
            @break
        @case("album_chg_detail")
            @include('admin.admin_album_chg_detail')
            @break
        @case("album_search")
            @include('admin.admin_album_search')
            @break
        {{---------------- アーティストメニュー ------------------}}
        @case("artist_reg")
            @include('admin.admin_artist_reg')
            @break
        @case("artist_search")
            @include('admin.admin_artist_search')
            @break
        {{---------------- プレイリストメニュー ------------------}}
        @case("playlist_reg")
            @include('admin.admin_playlist_reg')
            @break
        @case("playlist_chg_detail")
            @include('admin.admin_playlist_chg_detail')
            @break
        @case("playlist_search")
            @include('admin.admin_playlist_search')
            @break
        {{---------------- おすすめメニュー ------------------}}
        @case("recommend_reg")
            @include('admin.admin_recommend_reg')
            @break
        @default
    @endswitch
@endif