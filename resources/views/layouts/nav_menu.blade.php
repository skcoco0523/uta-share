<?//ナビ追加 20240118 kanno?>
@guest
@else
<style>
    /* 画面下部に固定するスタイル */
    .fixed-bottom-menu {
        position: fixed;
        bottom: 0;
        left: 0;
        width: 100%;
        background-color: #fff; /* メニューの背景色を指定 */
        border-top: 1px solid #ddd; /* 上部にボーダーを追加 */
    }

    /* ナビメニューのスタイルが必要な場合に追加 */
    .fixed-bottom-menu nav {
        /* ここにナビメニューのスタイルを追加 */
    }
    .nav-pills .nav-link {
        color: #000 !important; /* 任意の文字色に変更 */
    }
    .footer {
        margin-bottom:50px; /* fixed-bottom-menu の高さ + 追加の余白 */;
    }

</style>

<div class="fixed-bottom-menu"><?//storageにシンボリックリンク作成?>
    <nav class="nav nav-pills nav-justified">
        <a class="flex-sm-fill text-sm-center nav-link p-2 d-flex flex-column align-items-center" href="#">
            <img src="{{ asset('img/icon/home.png') }}" alt="アイコン" class="img-fluid img-sm">
            <span style="font-size: 0.75rem;">トップ</span></a>
        <div class="border-right"></div>
        <a class="flex-sm-fill text-sm-center nav-link p-2 d-flex flex-column align-items-center" href="#">
            <img src="{{ asset('img/icon/ranking.png') }}" alt="アイコン" class="img-fluid img-sm">
            <span style="font-size: 0.75rem;">ランキング</span></a>
        <div class="border-right"></div>
        <a class="flex-sm-fill text-sm-center nav-link p-2 d-flex flex-column align-items-center" href="#">
            <img src="{{ asset('img/icon/search.png') }}" alt="アイコン" class="img-fluid img-sm">
            <span style="font-size: 0.75rem;">検索</span></a>
        <div class="border-right"></div>
        <a class="flex-sm-fill text-sm-center nav-link p-2 d-flex flex-column align-items-center" href="#">
            <img src="{{ asset('img/icon/favorite.png') }}" alt="アイコン" class="img-fluid img-sm">
            <span style="font-size: 0.75rem;">お気に入り</span></a>
        <div class="border-right"></div>
        <a class="flex-sm-fill text-sm-center nav-link p-2 d-flex flex-column align-items-center" href="#">
            <img src="{{ asset('img/icon/friend.png') }}" alt="アイコン" class="img-fluid img-sm">
            <span style="font-size: 0.75rem;">フレンド</span></a>
    </nav>
    <div class="border-bottom"></div>
</div>
<div class="footer"></div>
@endguest




