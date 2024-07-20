
/*
function openTab(evt, tabName) {
    var i, tabcontent, tablinks;

    // 全てのタブコンテンツを非表示にする
    tabcontent = document.getElementsByClassName("tab-content");
    for (i = 0; i < tabcontent.length; i++) {
        tabcontent[i].style.display = "none";
    }

    // 全てのタブリンクからactiveクラスを削除する
    tablinks = document.getElementsByClassName("nav-link");
    for (i = 0; i < tablinks.length; i++) {
        tablinks[i].className = tablinks[i].className.replace(" active", "");
    }

    // 現在のタブコンテンツを表示し、タブリンクにactiveクラスを追加する
    document.getElementById(tabName).style.display = "block";
    evt.currentTarget.className += " active";

    // アクティブなタブをセッションストレージに保存
    sessionStorage.setItem('activeTab', tabName);
}

document.addEventListener('DOMContentLoaded', function() {
    const activeTab = sessionStorage.getItem('activeTab');
    if (activeTab) {
        document.querySelector(`.nav-link[onclick="openTab(event, '${activeTab}')"]`).click();
    } else {
        document.querySelector('.nav-link.active').click();
    }
});

*/