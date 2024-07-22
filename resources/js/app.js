/*
==========================================================================================
JSファイルを追加したら
1.importに追加する
2.npm run dev で検証
3.npm run build　でビルド　→build配下のファイルをアップロードする！
==========================================================================================

*/
import './notification.js';            //通知
import './favorite_change.js';         //お気に入り
import './custom_category_change.js';  //ユーザー別カテゴリ
import './search_history.js';          //検索履歴

/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

import './bootstrap';
import { createApp } from 'vue';

/**
 * Next, we will create a fresh Vue application instance. You may then begin
 * registering components with the application instance so they are ready
 * to use in your application's views. An example is included for you.
 */

const app = createApp({});

import ExampleComponent from './components/ExampleComponent.vue';
app.component('example-component', ExampleComponent);

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// Object.entries(import.meta.glob('./**/*.vue', { eager: true })).forEach(([path, definition]) => {
//     app.component(path.split('/').pop().replace(/\.\w+$/, ''), definition.default);
// });

/**
 * Finally, we will attach the application instance to a HTML element with
 * an "id" attribute of "app". This element is included with the "auth"
 * scaffolding. Otherwise, you will need to add an element yourself.
 */

app.mount('#app');

let deferredPrompt;

window.addEventListener('beforeinstallprompt', (e) => {
    // イベントを保存して、デフォルトの処理を防ぐ
    e.preventDefault();
    deferredPrompt = e;
    // プロンプトが表示できる状態にするためにボタンを表示
    showAddToHomeScreenButton();
});

function showAddToHomeScreenButton() {
    const addToHomeScreenButton = document.querySelector('#add-to-home-screen');
    if (addToHomeScreenButton) {
        addToHomeScreenButton.style.display = 'block';
        // ボタンのクリックイベントリスナーを設定
        addToHomeScreenButton.addEventListener('click', () => {
            if (deferredPrompt) {
                // プロンプトを表示
                deferredPrompt.prompt();
                deferredPrompt.userChoice.then((result) => {
                    if (result.outcome === 'accepted') {
                        console.log('User accepted the A2HS prompt');
                    } else {
                        console.log('User dismissed the A2HS prompt');
                    }
                    // `deferredPrompt` をリセットする
                    deferredPrompt = null;
                }).catch((err) => {
                    console.error('Error during A2HS prompt', err);
                    alert('インストールプロンプトの表示中にエラーが発生しました。');
                });
            } else {
                // `deferredPrompt` がリセットされている場合のメッセージ
                console.error('deferredPromptがリセットされている');
                alert('インストールプロンプトを表示できません。プロンプトがすでに表示されたか、キャンセルされた可能性があります。ブラウザを再度開きなおしてください。');
            }
        });
    } else {
        console.error('#add-to-home-screen element not found');
    }
}

// DOMContentLoaded イベントでボタンが存在するか確認し、設定を行う
document.addEventListener('DOMContentLoaded', () => {
    showAddToHomeScreenButton();
});


document.addEventListener('DOMContentLoaded', () => {
    const addToHomeScreenButton = document.querySelector('#add-to-home-screen');
    
    // デスクトップPWAのインストール状況を確認する
    if (navigator.standalone || window.matchMedia('(display-mode: standalone)').matches) {
        // アプリがインストールされている場合はボタンを非表示にする
        if (addToHomeScreenButton) {
            addToHomeScreenButton.style.display = 'none';
        }
    } else {
        // ボタンの表示設定
        let deferredPrompt;
        window.addEventListener('beforeinstallprompt', (e) => {
            e.preventDefault();
            deferredPrompt = e;
            if (addToHomeScreenButton) {
                addToHomeScreenButton.style.display = 'block'; // ボタンを表示する
            }
        });

        addToHomeScreenButton.addEventListener('click', () => {
            if (deferredPrompt) {
                deferredPrompt.prompt();
                deferredPrompt.userChoice.then((result) => {
                    if (result.outcome === 'accepted') {
                        console.log('User accepted the A2HS prompt');
                    } else {
                        console.log('User dismissed the A2HS prompt');
                    }
                    deferredPrompt = null;
                });
            }
        });
    }
});