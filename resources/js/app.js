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

const user_id = window.Laravel.user_id;
const vapidPublicKey = window.Laravel.vapidPublicKey;    // Base64 URLエンコード形式の公開鍵
//アプリインストールメニューの表示切替
document.addEventListener('DOMContentLoaded', () => {
    // DOMContentLoaded イベントでボタンが存在するか確認し、設定を行う
    showAddToHomeScreenButton();

    const addToHomeScreenButton = document.querySelector('#add-to-home-screen');

    const isStandalone = window.matchMedia('(display-mode: standalone)').matches || navigator.standalone;
    if (isStandalone) {
        // PWAアプリで開かれている　　
        if (addToHomeScreenButton) {
            addToHomeScreenButton.style.display = 'none';   //インストールメニューを非表示にする  
        }
    }else {
        //ブラウザで開かれている
        window.addEventListener('beforeinstallprompt', (e) => {
            e.preventDefault();
            deferredPrompt = e;
            if (addToHomeScreenButton) {
                addToHomeScreenButton.style.display = 'block'; // インストールメニューを表示する
            }
        });
    }
    
    // URL にログインフラグがある場合にデバイス登録処理を実行
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.get('login') === 'success') {
        if(user_id){
            console.log('デバイス登録');
            registerDevice();
        }
    }
});


function showAddToHomeScreenButton() {
    // ボタンのクリックイベントリスナーを設定
    const addToHomeScreenButton = document.querySelector('#add-to-home-screen');
    addToHomeScreenButton.addEventListener('click', () => {

  
        if (getOS()=='iOS') {
            console.log('device is iOS');
            alert('iOSでは、ブラウザの「共有」メニューから「ホーム画面に追加」を選択してください。');
            return false;
        }
        // ユーザーにプッシュ通知の許可を促す サブスクリプションを作成し取得
        requestNotificationPermission().then((subscription) => {
            
            if (deferredPrompt) {
                deferredPrompt.prompt();
                deferredPrompt.userChoice.then((result) => {
                    if (result.outcome === 'accepted') {
                        console.log('インストール');
                        // デバイス情報をサーバーに登録 →登録時ではなく、アプリでログイン時にする
                    } else {
                        //テスト==============================================================================
                        console.log('キャンセル');
                        //registerDevice(subscription);
                    }
                    //deferredPrompt = null;
                }).catch((err) => {
                    console.error('Error during A2HS prompt', err);
                    alert('インストールプロンプトの表示中にエラーが発生しました。');
                });

            } else {
                console.log('通知拒否');
                // インストールプロンプトがない場合の処理
                // deferredPrompt が設定されていない理由を説明する
                //console.error('deferredPrompt が設定されていないか、サポートされていない環境です。');
                alert('インストールプロンプトを表示できません。\nサポートされていない環境か、プロンプトが既に表示されている可能性があります。\nブラウザを再度開きなおしてください。');

            }
        }).catch((error) => {
            //通知許可のためサブスクリプション生成不可
            console.error('Notification permission process failed:', error);
        });
    });
}

//ユーザーにプッシュ通知の許可を促す 
function requestNotificationPermission() {
    if ('Notification' in window) {
        switch(Notification.permission){
            case "default": // 通知の許可をリクエスト
                return Notification.requestPermission().then((permission) => {
                    if (permission === 'granted') {
                        console.log('通知許可成功');
                        return subscribeUser(); // 通知の許可が得られたらサブスクリプションを作成
                    } else {
                        console.log('通知拒否');
                        alert("通知が拒否されているため、インストールできません。\n許可してからインストールしてください。");
                        return Promise.reject('Notification permission denied.');
                    }
                }).catch((error) => {
                    console.error('Notification permission request error:', error);
                    return Promise.reject(error);
                });
            case "granted":
                console.log('通知許可済み');
                return subscribeUser(); // すでに許可されている場合はサブスクリプションを作成
            case "denied":
                console.log('明示的拒否');
                alert("通知が拒否されているため、インストールできません。\n許可してからインストールしてください。");
                return Promise.reject('Notification permission denied.');
        }
    } else {
        console.log('Notifications are not supported.');
        return Promise.reject('Notifications are not supported.');
    }
}

// デバイス情報をサーバーに送信する関数
async function registerDevice() {
    try {
        const subscription = await subscribeUser()
        const deviceData = {
            user_id: user_id, // 必要に応じて設定
            device_id: getUniqueId(), // 一意のデバイスIDを生成する関数
            os: getOS(), // OSを検出する関数
            browser: getBrowser(), // ブラウザを検出する関数
            endpoint: subscription.endpoint, // サブスクリプションのエンドポイント
            public_key: arrayBufferToBase64(subscription.getKey('p256dh')), // 公開キー
            auth_token: arrayBufferToBase64(subscription.getKey('auth')) // 認証キー
        };

        console.log('p256dh Base64:=',arrayBufferToBase64(subscription.getKey('p256dh')));
        console.log('auth Base64:=',arrayBufferToBase64(subscription.getKey('auth')));
        console.log(deviceData);

        // デバイス情報登録
        $.ajax({
            type: "post",
            url: checkDevicesUrl,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: deviceData,
        })
        .done(response => {
            console.log(response);
        })
        .fail((xhr, status, error) => {
            if (xhr.status === 401) {
                // 認証エラーの場合の処理
                console.log('未ログイン');
            } else {
                // その他のエラーが発生した場合の処理
                console.error('エラー:', error);
            }
        });
        
    } catch (error) {
        console.error('Service Worker subscription error:', error);
    }
}

//ユーザーのsubscribeUser情報取得
async function subscribeUser() {
    console.log('call subscribeUser');
    try {
        const registration = await registerSW(); //サービスワーカー登録
        if (!registration) {
            console.log('ServiceWorker 登録失敗');
        }

        console.log('ServiceWorker 準備開始');
        const serviceWorkerReady = await navigator.serviceWorker.ready;
        console.log('ServiceWorker 準備完了:', serviceWorkerReady);

        const subscription = await serviceWorkerReady.pushManager.subscribe({
            userVisibleOnly: true,
            applicationServerKey: vapidPublicKey
        });
        
        //console.log('subscription:',subscription);
        return subscription;
    } catch (error) {
        console.error('Subscription failed:', error);
        return Promise.reject(error);
    }
}

// OS、ブラウザ、デバイスIDを検出・生成する例
function getOS() { /* OS検出ロジック */ 
    var ua = window.navigator.userAgent.toLowerCase();
    if(ua.indexOf("windows") !== -1) {
        return 'Windows';
    } else if(ua.indexOf("android") !== -1) {
        return 'Android';
    } else if(ua.indexOf("iphone") !== -1 || ua.indexOf("ipad") !== -1) {
        return 'iOS';
    } else if(ua.indexOf("mac os x") !== -1) {
        return 'Mac';
    } else {
        return 'Unknown';
    }
}
//ブラウザ名取得
function getBrowser() {
    var userAgent = window.navigator.userAgent.toLowerCase();
    
    if(userAgent.indexOf('msie') != -1 || userAgent.indexOf('trident') != -1) {
        return 'IE';
    } else if(userAgent.indexOf('edg') != -1 || userAgent.indexOf('edge') != -1) {
        return 'Edge';
    } else if(userAgent.indexOf('chrome') != -1) {
        return 'Chrome';
    } else if(userAgent.indexOf('safari') != -1) {
        return 'Safari';
    } else if(userAgent.indexOf('firefox') != -1) {
        return 'FireFox';
    } else if(userAgent.indexOf('opera') != -1) {
        return 'Opera';
    } else {
        return 'Unknown';
    }
}

function getUniqueId() {
    // 一意のIDを生成
    return 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function(c) {
        var r = Math.random() * 16 | 0, v = c == 'x' ? r : (r & 0x3 | 0x8);
        return v.toString(16);
    });
}

//Base64 形式に変換　
function arrayBufferToBase64(buffer) {
    var binary = '';
    var bytes = new Uint8Array(buffer);
    var len = bytes.byteLength;
    for (var i = 0; i < len; i++) {
        binary += String.fromCharCode(bytes[i]);
    }
    //return window.btoa(binary).replace(/\+/g, '-').replace(/\//g, '_');
    //return window.btoa(binary).replace(/\+/g, '-').replace(/\//g, '_').replace(/=+$/, '');
    return window.btoa(binary);
}

//Base64 形式Uint8Arrayに変換
function urlBase64ToUint8Array(base64String) {
    const padding = '='.repeat((4 - base64String.length % 4) % 4);
    const base64 = (base64String + padding).replace(/\-/g, '+').replace(/_/g, '/');
    const rawData = window.atob(base64);
    const outputArray = new Uint8Array(rawData.length);
    for (let i = 0; i < rawData.length; ++i) {
        outputArray[i] = rawData.charCodeAt(i);
    }
    return outputArray;
}