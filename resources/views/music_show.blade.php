@extends('layouts.app')


<div class="container">
    <div class="row justify-content-center">
    
    <div class="col-12 col-md-6">
        <?//コンテンツ?>  
        @section('content')
        <a href="{{ url()->previous() }}" style="text-decoration: none; color: inherit;">＜＜</a>
        <a href="{{$music->href }}">
            <div class="py-2 d-flex justify-content-center">
                <div class="card" style="width: 80%; height: calc(80% * 1);">
                    <img src="{{ $music->src }}" class="card-img-top" alt="pic" style="object-fit: cover; ">
                </div>
            </div>
        </a>
        <div class="text-center">
            <p class="detail-title">{{ $music->name }}</p>
            <p class="detail-txt">
                <a href="{{ route('album-show',  ['id' => $music->alb_id]) }}" style="text-decoration: none; color: inherit;">{{ $music->alb_name }}</a>
            </p>
            <p class="detail-txt">{{ $music->art_name }}</p>     <!--対象アーティストの曲一覧に遷移させるリンク-->
        </div>
        <!--共有　お気に入り　プレイリスト登録-->
        <table class="table table-borderless table-center">
            <tbody>
                <tr>
                    <td class="col-1"></td>
                    <th class="col-3" onclick="ShareToMusic({{ $music->mus_id }})">
                        <img src="{{ asset('img/icon/share_red1.png') }}" alt="アイコン" class="icon-20">
                    </th>
                    <td class="col-3" data-mus-id="{{ $music->mus_id }}">
                        <img id="favoriteIcon" src="{{ asset('img/icon/fav_red'. ($music->fav_flag). '.png') }}" alt="アイコン" class="icon-20" onclick="chgToFavorite({{ $music->mus_id }}, 0)">
                    </td>
                    <td class="col-3">
                        <img src="{{ asset('img/icon/add.png') }}" alt="アイコン" class="icon-20">
                    </td>
                    <td class="col-1"></td>
                </tr>
            </tbody>
        </table>

        <?//ログインユーザーのみ表示させるナビ?>   
        @include('layouts.nav_menu')

    </div>
</div>
</div>
@endsection

<script>
    let action = '{{ $music->fav_flag ? "del" : "add" }}';
    function chgToFavorite(detail_id, category) {
        // クリックイベントを無効化する
        document.getElementById('favoriteIcon').onclick = null;
        
        // お気に入りの状態を取得するためのHTTPリクエストを行う
        $.ajax({
            //POST通信
            type: "post",
            url: "/app01/favorite-chg",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {detail_id: detail_id,category: category,type: action},
        })
        .done(response => {
            if (response === "add") {
                //切り替え通知
                //showNotification('お気に入りに追加しました。',"fav_chg.gif",2000);
                showNotification('お気に入りに追加しました。',"loading",2000);
                const favoriteIcon = document.querySelector(`[data-mus-id="${detail_id}"] img`);
                //アイコン切り替え
                favoriteIcon.src = "{{ asset('img/icon/fav_red1.png') }}";
                action = "del";

            } else if (response === "del") {
                //切り替え通知
                //showNotification('お気に入りから削除しました。',"fav_chg.gif",2000);
                showNotification('お気に入りから削除しました。',"loading",2000);
                const favoriteIcon = document.querySelector(`[data-mus-id="${detail_id}"] img`);
                //アイコン切り替え
                favoriteIcon.src = "{{ asset('img/icon/fav_red0.png') }}";
                action = "add";
            } else {
                showNotification(response,"",1000);
            }
        })
        .always(() => {
            // 一定時間後にクリックイベントを再度有効化する
            setTimeout(() => {
                document.getElementById('favoriteIcon').onclick = () => chgToFavorite(detail_id, category);
            }, 2000); // 2000ミリ秒（2秒）後に再設定する例
        })
        .fail((xhr, status, error) => {
            if (xhr.status === 401) {
                // 認証エラーの場合の処理
                alert('ログインが必要です。');
                window.location.href = "/app01/login"; // ログインページにリダイレクト
            } else {
                // その他のエラーが発生した場合の処理
                console.error('エラー:', error);
                showNotification('エラーが発生しました。もう一度試してください。',"",2000);
            }
        });
    }
</script>