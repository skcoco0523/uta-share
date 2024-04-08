
{{-- プレイリスト情報更新処理 --}}
@if(!(isset($playlist_detail)))
    <form id="pl_search_form" method="POST" action="{{ route('admin-playlist-chg') }}">
        @csrf
        <div class="row g-3 align-items-end" >
            <input type="hidden" name="keyword" value="{{$input['keyword'] ?? ''}}">
            <input type="hidden" name="admin_flg" value="{{$input['admin_flg'] ?? ''}}">
            <input type="hidden" name="id" value="{{$select->id ?? ''}}">
            <div class="col-sm">
                <label for="inputname" class="form-label">ﾌﾟﾚｲﾘｽﾄ名</label>
                <input type="text" name="name" class="form-control" placeholder="name" value="{{$select->name ?? ''}}">
            </div>
            <div class="col-sm">
                <label for="inputusername" class="form-label">登録者</label>
                <input type="text" name="user_name" class="form-control" value="{{$select->user_name ?? ''}}" style="background-color: #f0f0f0; pointer-events: none;">
            </div>
            <div class="col-md-2">
            <input type="submit" value="更新" class="btn btn-primary">
        </div>
        </div>
    </form>
@endif

{{--エラー--}}
@if(isset($msg))
    <div class="alert alert-danger">
        {!! nl2br(e($msg)) !!}
    </div>
@endif

{{--検索--}}
<form method="GET" action="{{ route('admin-playlist-search') }}">

    <div class="row g-3 align-items-end">
    <div class="col-sm-6">
        <input type="text" id="keyword" name="keyword" class="form-control" value="{{$input['keyword'] ?? ''}}" placeholder="検索(ﾌﾟﾚｲﾘｽﾄ名)">
    </div>
    <div class="col-md-2">
        <select id="inputState" name="admin_flg" class="form-select">
            <option value="1" {{ ($input['admin_flg'] ?? '') == '1' ? 'selected' : '' }}>管理者</option>
            <option value="0" {{ ($input['admin_flg'] ?? '') == '0' ? 'selected' : '' }}>ユーザー</option>
        </select>
    </div>
    <div class="col-auto align-self-end">
        <button type="submit" class="btn btn-success">検索</button>
    </div>
</div>
</form>

{{--プレイリスト一覧--}}
@if(isset($playlist))
    <table class="table table-striped table-hover table-bordered fs-6 ">
        <thead>
        <tr>
            <th scope="col" class="fw-light">#</th>
            <th scope="col" class="fw-light">ﾌﾟﾚｲﾘｽﾄ名</th>
            <th scope="col" class="fw-light">登録曲数</th>
            <th scope="col" class="fw-light">登録者</th>
            <th scope="col" class="fw-light">種別</th>
            <th scope="col" class="fw-light">データ登録日</th>
            <th scope="col" class="fw-light">データ更新日</th>
            <th scope="col" class="fw-light">詳細変更</th>
            <th scope="col" class="fw-light"></th>
        </tr>
        </thead>
        @foreach($playlist as $pl)
            <tr>
                <td class="fw-light">{{$pl->id}}</td>
                <td class="fw-light">{{$pl->name}}</td>
                <td class="fw-light">{{$pl->mus_cnt}}</td>
                <td class="fw-light">{{$pl->user_name}}</td>
                <td class="fw-light">
                    @if($pl->admin_flg === 0)     ユーザー
                    @elseif($pl->admin_flg === 1) 管理者
                    @endif
                </td>
                <td class="fw-light">{{$pl->created_at}}</td>
                <td class="fw-light">{{$pl->updated_at}}</td>
                <td class="fw-light">
                    <form method="GET" action="{{ route('admin-playlist-chgdetail') }}">
                        <input type="hidden" name="id" value="{{$pl->id}}">
                        <input type="submit" value="収録曲変更" class="btn btn-primary">
                    </form>
                </td>
                <td class="fw-light">
                    <form method="POST" action="{{ route('admin-playlist-del') }}">
                        @csrf
                        <input type="hidden" name="pl_id" value="{{$pl->id}}">
                        <input type="hidden" name="pl_name" value="{{$pl->name}}">
                        <input type="hidden" name="keyword" value="{{$input['keyword'] ?? ''}}">
                        <input type="hidden" name="admin_flg" value="{{$input['admin_flg'] ?? ''}}">
                        <input type="submit" value="削除" class="btn btn-danger">
                    </form>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    <nav aria-label="Page navigation example">
        <ul class="pagination justify-content-end">
            <li class="page-item {{ $playlist->currentPage() == 1 ? 'disabled' : '' }}">
                <a class="page-link" href="{{ $playlist->previousPageUrl() }}" aria-label="Previous">
                    <span aria-hidden="true">Previous</span>
                </a>
            </li>
            @for ($i = 1; $i <= $playlist->lastPage(); $i++)
                <li class="page-item {{ $playlist->currentPage() == $i ? 'active' : '' }}">
                    <a class="page-link" href="{{ $playlist->url($i) }}&keyword={{$input['keyword'] ?? ''}}&admin_flg={{$input['admin_flg'] ?? ''}}">{{ $i }}</a>
                    
                </li>
            @endfor
            <li class="page-item {{ $playlist->currentPage() == $playlist->lastPage() ? 'disabled' : '' }}">
                <a class="page-link" href="{{ $playlist->nextPageUrl() }}" aria-label="Next">
                    <span aria-hidden="true">Next</span>
                </a>
            </li>
        </ul>
    </nav>
@endif

{{--収録曲変更--}}
@if(isset($playlist_detail))
    <div class="row g-3 align-items-end">
        <div class="col-sm">
            <label for="inputname" class="form-label">プレイリスト名</label>
            <input class="form-control" type="text" placeholder="{{$playlist_detail->name ?? ''}}" disabled>
        </div>

        <label for="inputmusic" class="form-label">収録曲</label>

        <div class="row align-items-end">
            {{--変更フォーム--}}
            <span class="form-label" style="cursor: pointer; color: blue; text-decoration: underline;" onclick="toggleDetails_chg()">変更</span>
            
            <div id="detail_chg">
                @foreach($playlist_detail->music as $mus)
                <div class="row">
                    <div class="col-sm-9 mb-2"> <!-- フォームの列 -->
                        <input type="text" class="form-control" value="{{$mus->mus_name}}    < {{$mus->art_name}} >" disabled>
                    </div>
                    <div class="col-sm-3 mb-2"> <!-- ボタンの列 -->
                        <div class="d-sm-inline-flex"> <!-- スクリーン幅が小さいときにインラインフレックスにする -->
                            <!--    プレイリストは変更なし
                            <input type="button" class="btn btn-primary me-2" value="更新" onclick="alb_detail_fnc('chg','{{$mus->id}}');" >
                            -->
                            <input type="button" class="btn btn-danger" value="削除" onclick="alb_detail_fnc('del','{{$mus->id}}');" >
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <span class="form-label"  style="cursor: pointer; color: blue; text-decoration: underline;" onclick="toggleDetails_add()">追加</span>
            <div id="detail_add">
                {{--追加用  検索フォーム--}}
                <form method="GET" action="{{ route('admin-playlist-detail-search') }}">

                    <input type="hidden" name="id" value="{{$playlist_detail->id}}">
                    <div class="row g-3 align-items-end">
                        <div class="col-sm-6">
                            <label for="keyword" class="visually-hidden">検索(楽曲名)</label>
                            <input type="text" id="keyword" name="mus_keyword" class="form-control" value="{{$input['mus_keyword'] ?? ''}}" placeholder="検索(楽曲名)">
                        </div>
                        <div class="col-auto align-self-end">
                            <button type="submit" class="btn btn-success">検索</button>
                        </div>
                    </div>
                </form>
                {{--追加用テーブル--}}
                @if(isset($music) && is_iterable($music))
                    <table class="table table-striped table-hover table-bordered fs-6 ">
                        <thead>
                        <tr>
                            <th scope="col" class="fw-light">#</th>
                            <th scope="col" class="fw-light">楽曲名</th>
                            <th scope="col" class="fw-light">ｱｰﾃｨｽﾄ名</th>
                            <th scope="col" class="fw-light">データ登録日</th>
                            <th scope="col" class="fw-light">データ更新日</th>
                            <th scope="col" class="fw-light"></th>
                        </tr>
                        </thead>
                        @foreach($music as $mus)
                            <tr>
                                <td class="fw-light">{{$mus->id}}</td>
                                <td class="fw-light">{{$mus->name}}</td>
                                <td class="fw-light">{{$mus->art_name}}</td>
                                <td class="fw-light">{{$mus->created_at}}</td>
                                <td class="fw-light">{{$mus->updated_at}}</td>
                                <td class="fw-light">
                                    <input type="button" class="btn btn-success" value="追加" onclick="alb_detail_fnc('add','{{$mus->id}}');" >
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    <nav aria-label="Page navigation example">
                        <ul class="pagination justify-content-end">
                            <li class="page-item {{ $music->currentPage() == 1 ? 'disabled' : '' }}">
                                <a class="page-link" href="{{ $music->previousPageUrl() }}" aria-label="Previous">
                                    <span aria-hidden="true">Previous</span>
                                </a>
                            </li>
                            @for ($i = 1; $i <= $music->lastPage(); $i++)
                                <li class="page-item {{ $music->currentPage() == $i ? 'active' : '' }}">
                                    <a class="page-link" href="{{ $music->url($i) }}&mus_keyword={{$input['mus_keyword'] ?? ''}}&id={{$playlist_detail->id}}">{{ $i }}</a>
                                    
                                </li>
                            @endfor
                            <li class="page-item {{ $music->currentPage() == $music->lastPage() ? 'disabled' : '' }}">
                                <a class="page-link" href="{{ $music->nextPageUrl() }}" aria-label="Next">
                                    <span aria-hidden="true">Next</span>
                                </a>
                            </li>
                        </ul>
                    </nav>
                @endif 
            </div>
        </div>
    </div>


{{--削除・追加用フォーム--}}
<form name="detail_form" method="POST" action="{{ route('admin-playlist-chgdetail-fnc') }}">
    @csrf
    <input type="hidden" name="fnc" value="">
    <input type="hidden" name="pl_id" value="{{$playlist_detail->id}}">
    <input type="hidden" name="detail_id" value="">
</form>

@endif

<script>
    function alb_detail_fnc(fnc,id){
        if (fnc === 'del') {
            var rtn = confirm('削除してもよろしいですか？');
            if (rtn === false) {
                return false;
            }
        }
        var trg = document.forms["detail_form"];
        trg.method="post";
        document.detail_form["fnc"].value     =fnc;
        if (fnc === 'del') {
            document.detail_form["detail_id"].value  =id;
            //var chg_name =document.getElementsByName("name_" + mus_id)[0].value;
            //document.detail_form["name"].value    =chg_name;

        }else if(fnc === 'add'){
            document.detail_form["detail_id"].value  =id;
            //var add_name =document.getElementsByName("add_name")[0].value;
            //document.detail_form["name"].value    =add_name;
        }
        trg.submit();
    }
    //変更フォームの表示切替変更フォームの表示切替
    function toggleDetails_chg() {
        var element = document.getElementById("detail_chg");
        if (element.style.display === "none") {
            element.style.display = "block";
        } else {
            element.style.display = "none";
        }
    }
    function toggleDetails_add() {
        var element = document.getElementById("detail_add");
        if (element.style.display === "none") {
            element.style.display = "block";
        } else {
            element.style.display = "none";
        }
    }

    //リストから選択時、art_idをpostできないため、再取得
    document.getElementById('pl_search_form').addEventListener('submit', function(event) {
        var artistInput = document.querySelector('input[name="art_name"]');
        var art_idInput = document.getElementById('selectedArtistId');
        
        // アーティストが入力されている場合のみ、フォームに値を設定する
        if (artistInput.value !== '') {
            var artistSelect = document.getElementById('artistSelect');
            var selectedOption = Array.from(artistSelect.options).find(option => option.value === artistInput.value);
            if (selectedOption) {
                art_idInput.value = selectedOption.dataset.id;
            }
        }
    });
    
    document.addEventListener('DOMContentLoaded', function() {
        // テーブルの各行にクリックイベントリスナーを追加
        document.querySelectorAll('table tr').forEach(row => {
            row.addEventListener('click', () => {
                // クリックされた行からデータを取得
                const cells = row.querySelectorAll('td');
                const id = cells[0].textContent;
                const name = cells[1].textContent;
                const user_name = cells[3].textContent;

                // フォームの対応するフィールドにデータを設定
                document.querySelector('input[name="id"]').value = id;
                document.querySelector('input[name="name"]').value = name;
                document.querySelector('input[name="user_name"]').value = user_name;
                
            });
        });
    });
</script>