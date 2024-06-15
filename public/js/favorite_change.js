function setFavoriteActions(id,fav_flag) {
    if (typeof favoriteActions === 'undefined') {
        window.favoriteActions = {}; // グローバルオブジェクトを定義
    }
    favoriteActions[id] = fav_flag ? "del" : "add" ;
}

function chgToFavorite(detail_id, category) {
    // アイコンのIDを取得
    const favoriteIconId = `favoriteIcon-${detail_id}`;
    // クリックイベントを無効化する
    document.getElementById(favoriteIconId).onclick = null;
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
            const favoriteIcon = document.querySelector(`[favorite-id="${detail_id}"] i`);
            //アイコン切り替え
            favoriteIcon.classList.remove("fa-regular");
            favoriteIcon.classList.add("fa-solid");
            favoriteActions[detail_id] = "del";

        } else if (response === "del") {
            //切り替え通知
            //showNotification('お気に入りに追加しました。',"loading",2000);
            showNotification('お気に入りから削除しました。',"fav_del",2000);
            const favoriteIcon = document.querySelector(`[favorite-id="${detail_id}"] i`);
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
            document.getElementById(favoriteIconId).onclick = () => chgToFavorite(detail_id, category);
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