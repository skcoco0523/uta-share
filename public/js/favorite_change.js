function setFavoriteActions(table, id, fav_flag) {
    if (typeof favoriteActions === 'undefined') {
        window.favoriteActions = {}; // グローバルオブジェクトを定義
    }
    if (typeof favoriteActions[table] === 'undefined') {
        favoriteActions[table] = {}; // テーブルオブジェクトを定義
    }
    favoriteActions[table][id] = fav_flag ? "del" : "add";

    console.log(favoriteActions[table][id]);
}

function chgToFavorite(table, detail_id) {
    // アイコンのdata属性を取得
    const favoriteIconSelector = `[data-favorite-id="${table}-${detail_id}"]`;
    
    // クリックイベントを無効化する
    document.querySelectorAll(favoriteIconSelector).forEach(icon => {
        icon.onclick = null;
    });
    
    // お気に入りの状態を取得するためのHTTPリクエストを行う
    $.ajax({
        //POST通信
        type: "post",
        url: "/app01/favorite-chg",
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: { detail_id: detail_id, table: table, type: favoriteActions[table][detail_id] },
    })
    .done(response => {
        if (response === "add") {
            showNotification('お気に入りに追加しました。', "fav_add", 2000);
            document.querySelectorAll(favoriteIconSelector).forEach(icon => {
                // アイコン切り替え
                icon.classList.remove("fa-regular");
                icon.classList.add("fa-solid");
            });
            favoriteActions[table][detail_id] = "del";
        } else if (response === "del") {
            showNotification('お気に入りから削除しました。', "fav_del", 2000);
            document.querySelectorAll(favoriteIconSelector).forEach(icon => {
                // アイコン切り替え
                icon.classList.remove("fa-solid");
                icon.classList.add("fa-regular");
            });
            favoriteActions[table][detail_id] = "add";
        } else {
            showNotification(response, "お気に入りの切り替えに失敗しました。", 1000);
        }
        console.log(favoriteActions[table][detail_id]);
    })
    .always(() => {
        // 一定時間後にクリックイベントを再度有効化する
        setTimeout(() => {
            document.querySelectorAll(favoriteIconSelector).forEach(icon => {
                icon.onclick = () => chgToFavorite(table, detail_id);
            });
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
            showNotification('エラーが発生しました。もう一度試してください。', "", 2000);
        }
    });
}
