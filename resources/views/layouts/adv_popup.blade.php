<div id="adv_modal" class="notification-overlay">
    <div class="notification-modal" onclick="event.stopPropagation()">
        <div class="modal-content">
            <div class="modal-header">
                    <h5 class="modal-title" id="newPlaylistModalLabel">広告</h5>
                    <button id="closeButton" type="button" class="btn-close" aria-label="Close" onclick="closeModal('adv_modal')" style="display: none;"></button>
                    <div id="countdown"></div>  <!--カウントダウンを表示-->
            </div>
            <div class="modal-body">
                <div id="popup-adv-items">
                    <!-- JavaScriptで動的に挿入される -->
                </div>
                
            </div>
        </div>
    </div>
</div>

<script>
    
    document.addEventListener('DOMContentLoaded', async function() {
        
        const advDisplayInterval = 60*15;   // 広告表示間隔　(秒)        ページ遷移時に再度広告が表示されるまでの間隔       一旦15分間隔で
        let advDisplayTime = 10;            // 広告表示時間　(秒)         広告を強制表示する秒数
        let disp_flag = 0;
        // セッションストレージから前回の表示時刻を取得
        const lastDisplayed = sessionStorage.getItem('adv_disp_time');

        if (lastDisplayed) {
            const currentTime = new Date().getTime();
            const elapsedTime = (currentTime - lastDisplayed) / 1000;  // ミリ秒を秒に変換
            // 指定した時間が経過していればモーダルを表示
            if (elapsedTime > advDisplayInterval) {
                disp_flag = 1;
                sessionStorage.setItem('adv_disp_time', new Date().getTime());  //現在の時刻をセッションストレージに保存
            }
        } else {
            disp_flag = 1;
            sessionStorage.setItem('adv_disp_time', new Date().getTime());      //現在の時刻をセッションストレージに保存
        }

        //ここで広告を表示する
        if(disp_flag == 1){

            //一旦複数件取得し、ランダムで1つを表示
            const advertisement = await get_advertisement(10,"popup");
            console.log(advertisement);        

            //表示対象の広告情報(popup)があった場合のみ表示
            if (advertisement && advertisement.length > 0) {
                const select_index = Math.floor(Math.random() * advertisement.length);  //ランダムで1つ選択
                const select_data = advertisement[select_index];
                const popupAdvlInner = document.getElementById('popup-adv-items');
                popupAdvlInner.innerHTML = ''; // 既存のスライドをクリア

                const item = `
                        <a href="${select_data.href}" rel="nofollow" onclick="adv_click(${select_data.id})">
                            <img src="${select_data.src}" class="d-block w-100">
                            <img border="0" width="1" height="1" src="${select_data.tracking_src}">
                        </a>
                `;
                popupAdvlInner.insertAdjacentHTML('beforeend', item);
                openModal('adv_modal');
            }
            
            //閉じるボタンの使用を制限
            const closeButton = document.getElementById('closeButton');
            const countdownDisplay  = document.getElementById('countdown');

            countdownDisplay .innerHTML = advDisplayTime;

            const interval = setInterval(function() {
                advDisplayTime--;
                countdownDisplay .innerHTML = advDisplayTime;

                if (advDisplayTime === 0) {
                    clearInterval(interval);
                    countdownDisplay.style.display = 'none'; // ボタンを表示
                    closeButton.style.display = 'block'; // ボタンを表示
                }
            }, 1000); // 1秒ごとにカウントダウン
        }
    });

</script>

