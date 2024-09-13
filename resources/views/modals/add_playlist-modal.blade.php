

<div id="add_pl_modal" class="notification-overlay" onclick="closeModal('add_pl_modal')">
    <div class="notification-modal" onclick="event.stopPropagation()">
        <div class="modal-content">
            <!-- プレイリストから曲削除フォーム -->
            <form action="{{ route('myplaylist-detail-fnc') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="newPlaylistModalLabel">プレイリストに追加</h5>
                    <input type="hidden" name="fnc" value='add'>
                    <input type="hidden" id="url" name="url" value={{$url}}>
                    <input type="hidden" id="detail_id" name="{{ $detail_id}}">
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <!-- プレイリストの内容が表示されるエリア -->
                        <div id="playlistContent">
                            <!-- ここにプレイリストを表示 -->
                        </div>
                    </div>
                </div>
                <div class="modal-footer row gap-3 justify-content-center">

                    <button type="button" class="col-5 btn btn-secondary" onclick="closeModal('add_pl_modal')">キャンセル</button>
                    <button id="add_button" type="submit" class="col-5 btn btn-danger">追加</button>
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
            let playlistContent = document.getElementById('playlistContent');
            playlistContent.innerHTML = ''; // 既存の内容をクリア

            if (data && data.length > 0) {
                // プレイリストの内容をループで表示
                data.forEach(playlist => {
                    let playlistItem = document.createElement('div');
                    playlistItem.classList.add('playlist-item');
                    playlistItem.textContent = playlist.name; // プレイリストの名前を表示
                    playlistContent.appendChild(playlistItem);
                });
            } else {
                // プレイリストがない場合のメッセージ
                playlistContent.textContent = 'プレイリストがありません。';
            }
            
        })
        .fail((xhr, status, error) => {
            console.error('Error fetching playlist:', error);
            document.getElementById('playlistContent').textContent = 'プレイリストの取得に失敗しました。';
        });


    }
</script>
