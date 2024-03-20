{{--検索--}}
<form id="alb_search_form" method="GET" action="{{ route('admin-playlist-chgdetail') }}">

    <div class="row g-3 align-items-end">
        <div class="col-sm-6">
            <label for="keyword" class="visually-hidden">検索(ﾌﾟﾚｲﾘｽﾄ名)</label>
            <input type="text" id="keyword" name="keyword" class="form-control" value="{{$input['keyword'] ?? ''}}" placeholder="検索(ﾌﾟﾚｲﾘｽﾄ名)">
        </div>
        <div class="col-md-2">
            <select id="inputState" name="admin_flg" class="form-select">
                <option value="0" {{ ($input['admin_flg'] ?? '') == '0' ? 'selected' : '' }}>ユーザー</option>
                <option value="1" {{ ($input['admin_flg'] ?? '') == '1' ? 'selected' : '' }}>管理者</option>
            </select>
        </div>
        <div class="col-auto align-self-end">
            <button type="submit" class="btn btn-success">検索</button>
        </div>
    </div>
</form>

{{--プレイリスト一覧--}}
@if(isset($playlists))
    <table class="table table-striped table-hover table-bordered fs-6 ">
        <thead>
        <tr>
            <th scope="col" class="fw-light">#</th>
            <th scope="col" class="fw-light">ﾌﾟﾚｲﾘｽﾄ名</th>
            <th scope="col" class="fw-light">登録曲数</th>
            <th scope="col" class="fw-light">登録者</th>
            <th scope="col" class="fw-light">データ登録日</th>
            <th scope="col" class="fw-light">データ更新日</th>
            <th scope="col" class="fw-light"></th>
        </tr>
        </thead>
        @foreach($playlists as $playlist)
            <tr>
                <td class="fw-light">{{$playlist->id}}</td>
                <td class="fw-light">{{$playlist->name}}</td>
                <td class="fw-light">{{$playlist->mus_cnt}}</td>
                <td class="fw-light">{{$playlist->user_name}}</td>
                <td class="fw-light">{{$playlist->created_at}}</td>
                <td class="fw-light">{{$playlist->updated_at}}</td>
                <td class="fw-light">
                    <form method="GET" action="{{ route('admin-playlist-chgdetail') }}">
                        <input type="hidden" name="id" value="{{$playlist->id}}">
                        <input type="submit" value="収録曲変更" class="btn btn-primary">
                    </form>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    <nav aria-label="Page navigation example">
        <ul class="pagination justify-content-end">
            <li class="page-item {{ $playlists->currentPage() == 1 ? 'disabled' : '' }}">
                <a class="page-link" href="{{ $playlists->previousPageUrl() }}" aria-label="Previous">
                    <span aria-hidden="true">Previous</span>
                </a>
            </li>
            @for ($i = 1; $i <= $playlists->lastPage(); $i++)
                <li class="page-item {{ $playlists->currentPage() == $i ? 'active' : '' }}">
                    <a class="page-link" href="{{ $playlists->url($i) }}&keyword={{$input['keyword'] ?? ''}}&admin_flg={{$input['admin_flg'] ?? ''}}">{{ $i }}</a>
                    
                </li>
            @endfor
            <li class="page-item {{ $playlists->currentPage() == $playlists->lastPage() ? 'disabled' : '' }}">
                <a class="page-link" href="{{ $playlists->nextPageUrl() }}" aria-label="Next">
                    <span aria-hidden="true">Next</span>
                </a>
            </li>
        </ul>
    </nav>
@endif

{{--エラー--}}
@if(isset($msg))
    <div class="alert alert-danger">
        {!! nl2br(e($msg)) !!}
    </div>
@endif

{{--収録曲変更--}}
@if($input['chg_flg'])
    <div class="row g-3 align-items-end">
        <div class="col-sm">
            <label for="inputname" class="form-label">プレイリスト名</label>
            <input class="form-control" type="text" placeholder="{{$playlist->name ?? ''}}" disabled>
        </div>

        <label for="inputmusic" class="form-label">収録曲</label>

        <div class="row align-items-end">
            {{--変更フォーム--}}
            <span class="form-label" style="cursor: pointer; color: blue; text-decoration: underline;" onclick="toggleDetails_chg()">変更</span>
            
            <div id="detail_chg" style="display:  {{ $input['redirect_flg'] ? 'block' : 'none' }}">
                @foreach($playlist->music as $mus)
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
            <div id="detail_add" style="display:  {{ $input['redirect_flg'] ? 'block' : 'none' }}">
                {{--追加用  検索フォーム--}}
                <form method="GET" action="{{ route('admin-playlist-music-search') }}">

                    <input type="hidden" name="id" value="{{$playlist->id}}">
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
                                    <a class="page-link" href="{{ $music->url($i) }}&mus_keyword={{$input['mus_keyword'] ?? ''}}&id={{$playlist->id}}">{{ $i }}</a>
                                    
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
<form name="detail_form" method="POST" action="{{ route('admin-playlist-chgdetail_fnc') }}">
    @csrf
    <input type="hidden" name="fnc" value="">
    <input type="hidden" name="pl_id" value="{{$playlist->id}}">
    <input type="hidden" name="mus_id" value="">
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
            document.detail_form["mus_id"].value  =id;
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
    document.getElementById('alb_search_form').addEventListener('submit', function(event) {
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
    

</script>

