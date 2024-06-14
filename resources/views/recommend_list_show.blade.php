@extends('layouts.app')

<div class="container">
    <div class="row justify-content-center">
    
    <div class="col-12 col-md-6">
        <?//コンテンツ?>  
        @section('content')
        <a href="{{ url()->previous() }}" style="text-decoration: none; color: inherit;">＜＜</a>
        <div class="py-2 d-flex justify-content-center">
            <p class="card-text">{{ $recommnd->name }}</p>

        </div>
        <div class="text-center ">
            <table class="table table-borderless table-center">
                <tbody>
                {{--お気に入りの初期状態を取得--}}
                <script> let favoriteActions = {}; </script>
                @foreach ($recommnd->detail as $key => $detail)
                    <script> favoriteActions[{{ $detail->detail_id }}] = '{{ $detail->fav_flag ? "del" : "add" }}'; </script>
                    <tr>
                        <td class="col-1" onclick="redirectToDetailShow({{ $detail->detail_id }}, {{ $recommnd->category }})">
                            {{ $key + 1 }}
                        </td>
                        <td class="col-9" onclick="redirectToDetailShow({{ $detail->detail_id }}, {{ $recommnd->category }})">
                            {{ Str::limit($detail->name, 30, '...') }}
                        </td>
                        <td class="col-1" data-detail-id="{{ $detail->detail_id }}">
                            @if($detail->fav_flag)
                                <i id="favoriteIcon" class="fa-solid fa-heart icon-20 red" onclick="chgToFavorite({{ $detail->detail_id }}, {{ $recommnd->category }})"></i>
                            @else
                                <i id="favoriteIcon" class="fa-regular fa-heart icon-20 red" onclick="chgToFavorite({{ $detail->detail_id }}, {{ $recommnd->category }})"></i>
                            @endif
                        </td>
                        <td class="col-1">
                            <img src="{{ asset('img/icon/add.png') }}" alt="アイコン" class="icon-20">
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        <?//ログインユーザーのみ表示させるナビ?>   
        @include('layouts.nav_menu')

    </div>
</div>
</div>
@endsection

<script>
    function redirectToDetailShow(detail_id,category) {
        switch(category){
            case 0:
                window.location.href = "{{ route('music-show') }}?id=" + detail_id;
                break;
            case 1:
                break;
            case 2:
                window.location.href = "{{ route('album-show') }}?id=" + detail_id;
                break;
            case 3:
                window.location.href = "{{ route('playlist-show') }}?id=" + detail_id;
                break;
            default:
                break;
        }
    }
    
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
            data: {detail_id: detail_id,category: category,type: favoriteActions[detail_id]},
        })
        .done(response => {
            if (response === "add") {
                //切り替え通知
                //showNotification('お気に入りに追加しました。',"loading",2000);
                showNotification('お気に入りに追加しました。',"fav_add",2000);
                const favoriteIcon = document.querySelector(`[data-detail-id="${detail_id}"] i`);
                //アイコン切り替え
                favoriteIcon.classList.remove("fa-regular");
                favoriteIcon.classList.add("fa-solid");
                favoriteActions[detail_id] = "del";

            } else if (response === "del") {
                //切り替え通知
                //showNotification('お気に入りに追加しました。',"loading",2000);
                showNotification('お気に入りから削除しました。',"fav_del",2000);
                const favoriteIcon = document.querySelector(`[data-detail-id="${detail_id}"] i`);
                //アイコン切り替え
                favoriteIcon.classList.remove("fa-solid");
                favoriteIcon.classList.add("fa-regular");
                favoriteActions[detail_id] = "add";
            } else {
                showNotification(response,"お気に入りの切り替えに失敗しました。",1000);
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