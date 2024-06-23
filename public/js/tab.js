
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
}