function showNotification(message, type, sec) {
    const notification = document.getElementById('notification');
    if (type=="loading") {
        notification.innerHTML = `
            <div class="d-flex flex-column align-items-center">
            <div class="spinner-border mt-2" role="status" aria-hidden="true"></div>
                <strong>${message}</strong>
            </div>
        `;
    } else {
        notification.innerHTML = `<p>${message}</p>`;
    }
    notification.style.display = 'block';

    // 指定された秒数後に通知を非表示にする
    setTimeout(hideNotification, sec);
}

function hideNotification() {
    document.getElementById('notification').style.display = 'none';
}