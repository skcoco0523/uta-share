

<div id="add_pl_modal" class="notification-overlay" onclick="closeModal('add_pl_modal')">
    <div class="notification-modal" onclick="event.stopPropagation()">
        <div class="modal-content">
            <!-- プレイリストから曲削除フォーム -->
            <form action="{{ route('myplaylist-detail-fnc') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="newPlaylistModalLabel">プレイリストに追加</h5>
                    <input type="hidden" name="fnc" value='add'>
                    <input type="hidden" id="id" name="id">
                    <input type="hidden" id="detail_id" name="detail_id" value={{$detail_id}}>
                    <input type="hidden" id="url" name="url" value={{$url}}>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <!-- プレイリストの内容が表示されるエリア -->
                        <ul id="playlistList" class="list-group">
                            <!-- プレイリストのリストがここに追加される -->
                        </ul>
                    </div>
                </div>
                <div class="modal-footer row gap-3 justify-content-center">

                    <button type="button" class="col-5 btn btn-secondary" onclick="closeModal('add_pl_modal')">キャンセル</button>
                    <button id="add_button" type="submit" class="col-5 btn btn-danger disabled">追加</button>
                    <button id="create_button" type="button" class="col-5 btn btn-danger" style="display: none;">作成</button>
                </div>
            </form>
        </div>
    </div>
</div>


<script>
    
    function get_myplaylist() {
        //apiToken = getToken();
        //console.log(apiToken);

        $.ajax({
        //GET通信
        type: "get",
        url: getMyPlaylistUrl,
        headers: {
            //'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            //'Authorization': 'Bearer ' + apiToken
        },
        data: {},
        })
        .done(data => {
            let playlistContent = document.getElementById('playlistList');
            playlistContent.innerHTML = ''; // 既存の内容をクリア

            if (data && data.length > 0) {
                // プレイリストの内容をループで表示
                data.forEach(playlist => {
                    // ラベルを作成
                    let label = document.createElement('span');
                    label.textContent = playlist.name; // プレイリストの名前を表示

                    // リストアイテムを作成
                    let listItem = document.createElement('li');
                    listItem.classList.add('list-group-item', 'd-flex', 'justify-content-between', 'align-items-center');
                    listItem.dataset.playlistId = playlist.id; // プレイリストIDをデータ属性に設定
                    listItem.appendChild(label);

                    // 行をクリックしたときの処理
                    listItem.addEventListener('click', function() {
                        // すべてのリストアイテムから選択クラスを削除
                        document.querySelectorAll('#playlistList .list-group-item').forEach(item => {
                            item.classList.remove('list-selected');
                        });

                        // クリックされたアイテムに選択クラスを追加
                        this.classList.add('list-selected');
                        
                        add_button.classList.remove('disabled');
                        
                        // 選択されたアイテムのIDを隠しフィールドに設定
                        document.getElementById('id').value = this.dataset.playlistId;
                    });

                    // プレイリストリストに追加
                    playlistContent.appendChild(listItem);
                });
            } else {
                // プレイリストがない場合のメッセージ
                playlistContent.textContent = 'プレイリストがありません。';
                //表示ボタンを切り替える
                
                document.getElementById('add_button').style.display = 'none';

                let createButton = document.getElementById('create_button');
                createButton.style.display = 'block'; // ボタンを表示
                createButton.addEventListener('click', function() {
                    window.location.href = "{{ route('favorite-show') }}?table=mypl"; // ボタンがクリックされたときにリダイレクト
                });
            }
            
        })
        .fail((xhr, status, error) => {
            console.error('Error fetching playlist:', error);
            document.getElementById('playlistContent').textContent = 'プレイリストの取得に失敗しました。';
        });


    }
</script>
