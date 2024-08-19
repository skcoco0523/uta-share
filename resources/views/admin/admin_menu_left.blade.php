
    <div class="menu_section">
    @if(isset($tab_name))
        {{$tab_name}}メニュー
        @switch($tab_name)
            @case("音楽")
                <li><a href="{{ route('admin-music-reg') }}">               新規登録</a></li>
                <li><a href="{{ route('admin-music-search') }}">            検索/変更/削除</a></li>
                <li><a href="{{ route('admin-custom-category-setting') }}"> カテゴリー設定</a></li>
                @break
            @case("アルバム")
                <li><a href="{{ route('admin-album-reg') }}">               新規登録</a></li>
                <li><a href="{{ route('admin-album-search') }}">            検索/変更/削除</a></li>
                @break
            @case("アーティスト")
                <li><a href="{{ route('admin-artist-reg') }}">              新規登録</a></li>
                <li><a href="{{ route('admin-artist-search') }}">           検索/変更/削除</a></li>
                @break
            @case("プレイリスト")
                <li><a href="{{ route('admin-playlist-reg') }}">            新規登録</a></li>
                <li><a href="{{ route('admin-playlist-search') }}">         検索/変更/削除</a></li>
                @break
            @case("おすすめ")
                <li><a href="{{ route('admin-recommend-reg') }}">           新規登録</a></li>
                <li><a href="{{ route('admin-recommend-search') }}">        検索/変更/削除</a></li>
                @break
            @case("通知")
                <li><a href="">               メール通知(未開発)</a></li>
                <li><a href="">               プッシュ通知(未開発)</a></li>
                <li><a href="">               通知履歴(未開発)</a></li>
                @break
            @case("ユーザー")
                <li><a href="{{ route('admin-user-list') }}">               ユーザー(開発中)</a></li>
                                                            {{--最終アクセス時刻、ログイン回数　追加--}}
                <li><a href="">               お気に入り(未開発)</a></li>
                <li><a href="">               アクセス履歴(未開発)</a></li>
                @break
            @case("その他")
                <li><a href="{{ route('admin-user-request') }}">           依頼 問い合わせ(未開発)</a></li>
                @break
        @endswitch
    @endif
    
    </div>
