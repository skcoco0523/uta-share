function showNotification(message, type, sec) {
    const notification = document.getElementById('notification');
    //アイコン情報：https://fontawesome.com/
    switch(type){
        case "loading":     //スピナー
                notification.innerHTML = `
                <div class="d-flex flex-column align-items-center">
                    <div id="spinnerSection" class="spinner-border mt-2" role="status" aria-hidden="true"></div>
                </div>
                <div id="messageSection" style="display: none;">
                    <strong>${message}</strong>
                </div>
                `;
            break;
        case "fav_add":    //お気に入り追加
            notification.innerHTML = `<i class="fa-solid fa-heart-circle-plus fa-bounce red icon-50"></i>
            <p>${message}</p>`;
            break;
        case "fav_del":    //お気に入り削除
            notification.innerHTML = `<i class="fa-solid fa-heart-circle-xmark fa-shake red icon-50"></i>
            <p>${message}</p>`;
            break;
        default:            //メッセージのみ
            notification.innerHTML = `<p>${message}</p>`;
            break;
    }

    notification.style.display = 'block';

    if (type === "loading") {
        // 半分の時刻が経過したらスピナーを非表示にしてメッセージを表示
        setTimeout(() => {
            document.getElementById('spinnerSection').style.display = 'none';
            document.getElementById('messageSection').style.display = 'block';
        }, sec / 2);
    }

    // 指定された秒数後に通知を非表示にする
    setTimeout(hideNotification, sec);
}

function hideNotification() {
    document.getElementById('notification').style.display = 'none';
}
