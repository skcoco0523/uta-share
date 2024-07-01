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
        case "profile":    //プロフィール変更
            notification.innerHTML = `<i class="fa-solid fa-address-card fa-fade icon-50"></i>
                                        <p>${message}</p>`;
            break; 
        case "error":    //エラー
        notification.innerHTML = `<i class="fa-solid fa-triangle-exclamation fa-shake icon-50"></i>
                                    <p>${message}</p>`;
            break;
        case "friend":    //フレンド関連
        notification.innerHTML = `<i class="fa-solid fa-user-group fa-fade icon-50"></i>
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

//モーダル(シェアポップアップ)-----------------------------
function openShareModal(url) {
    var modal = document.getElementById('shareModal');
    var shareButtons = modal.querySelectorAll('.share-button');

    shareButtons.forEach(function(button) {
        var platform = button.getAttribute('data-platform');
        button.setAttribute('onclick', "shareToPlatform('" + platform + "', '" + url + "')");
    });

    modal.style.display = 'block';
}

function closeShareModal(event) {
    // オーバーレイまたは閉じるボタンがクリックされた場合にのみモーダルを閉じる
    if (event.target.classList.contains('notification-overlay') || event.target.classList.contains('close')) {
        document.getElementById('shareModal').style.display = 'none';
    }
}

function shareToPlatform(platform, url) {

    let popupUrl;
    const width = 600;
    const height = 400;
    const left = (screen.width / 2) - (width / 2);
    const top = (screen.height / 2) - (height / 2);

    switch(platform) {
        case 'line':
            popupUrl = 'https://social-plugins.line.me/lineit/share?url=' + encodeURIComponent(url);
            break;
        case 'twitter':
            popupUrl = 'https://twitter.com/intent/tweet?url=' + encodeURIComponent(url);
            break;
        case 'facebook':
            popupUrl = 'https://www.facebook.com/sharer/sharer.php?u=' + encodeURIComponent(url);
            break;
        default:
            return;
    }

    window.open(popupUrl, platform + 'Share', 'width=' + width + ',height=' + height + ',top=' + top + ',left=' + left);
    closeShareModal({ target: document.querySelector('.notification-overlay') }); // モーダルを閉じる
}
