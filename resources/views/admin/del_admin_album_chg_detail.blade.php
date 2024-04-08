{{--検索--}}
<form id="alb_search_form" method="GET" action="{{ route('admin-album-chgdetail') }}">

    <div class="row g-3 align-items-end">
        <div class="col-sm-6">
            <label for="keyword" class="visually-hidden">検索(アルバム名)</label>
            <input type="text" id="keyword" name="keyword" class="form-control" value="{{$input['keyword'] ?? ''}}" placeholder="検索(アルバム名)">
        </div>
        <div class="col-auto align-self-end">
            <button type="submit" class="btn btn-success">検索</button>
        </div>
    </div>
</form>

{{--アルバム一覧--}}
@if(isset($albums))
    <table class="table table-striped table-hover table-bordered fs-6 ">
        <thead>
        <tr>
            <th scope="col" class="fw-light">#</th>
            <th scope="col" class="fw-light">ｱﾙﾊﾞﾑ名</th>
            <th scope="col" class="fw-light">ｱｰﾃｨｽﾄ名</th>
            <th scope="col" class="fw-light">収録数</th>
            <th scope="col" class="fw-light">ﾘﾘｰｽ</th>
            <th scope="col" class="fw-light">データ登録日</th>
            <th scope="col" class="fw-light">データ更新日</th>
            <th scope="col" class="fw-light">ｲﾒｰｼﾞ&ﾘﾝｸ</th>
            <th scope="col" class="fw-light"></th>
        </tr>
        </thead>
        @foreach($albums as $album)
            <tr>
                <!--
                <input type="hidden" name="aff_id" value="{{$album->aff_id}}">
                <input type="hidden" name="keyword" value="{{$input['keyword'] ?? ''}}">
                <input type="hidden" name="music_list" value="{{$album->music_list}}">
                -->
                <td class="fw-light">{{$album->id}}</td>
                <td class="fw-light">{{$album->name}}</td>
                <td class="fw-light">{{$album->art_name}}</td>
                <td class="fw-light">{{$album->mus_cnt}}</td>
                <td class="fw-light">{{$album->release_date}}</td>
                <td class="fw-light">{{$album->created_at}}</td>
                <td class="fw-light">{{$album->updated_at}}</td>
                <td><a href="{{ $album->href }}">
                    <img src="{{ $album->src }}" style="object-fit: cover; width: 100px; height: 100px;" alt="album_image">
                </a></td>
                <td class="fw-light">
                    <form method="GET" action="{{ route('admin-album-chgdetail') }}">
                        <input type="hidden" name="alb_id" value="{{$album->id}}">
                        <input type="submit" value="収録曲変更" class="btn btn-primary">
                    </form>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    <nav aria-label="Page navigation example">
        <ul class="pagination justify-content-end">
            <li class="page-item {{ $albums->currentPage() == 1 ? 'disabled' : '' }}">
                <a class="page-link" href="{{ $albums->previousPageUrl() }}" aria-label="Previous">
                    <span aria-hidden="true">Previous</span>
                </a>
            </li>
            @for ($i = 1; $i <= $albums->lastPage(); $i++)
                <li class="page-item {{ $albums->currentPage() == $i ? 'active' : '' }}">
                    <a class="page-link" href="{{ $albums->url($i) }}&keyword={{$input['keyword'] ?? ''}}">{{ $i }}</a>
                    
                </li>
            @endfor
            <li class="page-item {{ $albums->currentPage() == $albums->lastPage() ? 'disabled' : '' }}">
                <a class="page-link" href="{{ $albums->nextPageUrl() }}" aria-label="Next">
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
            <label for="inputname" class="form-label">ｱﾙﾊﾞﾑ名</label>
            <input class="form-control" type="text" placeholder="{{$album->name ?? ''}}" disabled>
        </div>
        <div class="col-sm">
            <label for="inputart_name" class="form-label">ｱｰﾃｨｽﾄ名</label>
            <input class="form-control" type="text" placeholder="{{$album->art_name ?? ''}}" disabled>
        </div>

        <label for="inputmusic" class="form-label">収録曲</label>

        <div class="row align-items-end">
            {{--変更フォーム--}}
            <span class="form-label" style="cursor: pointer; color: blue; text-decoration: underline;" onclick="toggleDetails_chg()">変更</span>
            
            <div id="detail_chg" style="display:  {{ $input['redirect_flg'] ? 'block' : 'none' }}">
                @foreach($album->music as $music)
                <div class="row">
                    <div class="col-sm-9 mb-2"> <!-- フォームの列 -->
                        <input type="text" name="name_{{$music->id}}" class="form-control" value="{{$music->name}}">
                    </div>
                    <div class="col-sm-3 mb-2"> <!-- ボタンの列 -->
                        <div class="d-sm-inline-flex"> <!-- スクリーン幅が小さいときにインラインフレックスにする -->
                            <input type="button" class="btn btn-primary me-2" value="更新" onclick="alb_detail_fnc('chg','{{$music->id}}');" >
                            <input type="button" class="btn btn-danger" value="削除" onclick="alb_detail_fnc('del','{{$music->id}}');" >
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            {{--追加フォーム--}}
            <span class="form-label"  style="cursor: pointer; color: blue; text-decoration: underline;" onclick="toggleDetails_add()">追加</span>

            <div id="detail_add" style="display: none;">
                <div class="row">
                    <div class="col-sm-9">
                        <input type="text" name="add_name" class="form-control" value="">
                    </div>
                    <div class="col-sm-3 mb-2">
                        <input type="button" class="btn btn-success" value="追加" onclick="alb_detail_fnc('add','');" >
                    </div>
                </div>
            </div>
        </div>
    </div>

{{--更新・削除・追加用フォーム--}}
<form name="detail_form" method="POST" action="{{ route('admin-album-chgdetail-fnc') }}">
    @csrf
    <input type="hidden" name="fnc" value="">
    <input type="hidden" name="alb_id" value="{{$album->id}}">
    <input type="hidden" name="mus_id" value="">
    <input type="hidden" name="name" value="">
</form>

@endif

<script>
    function alb_detail_fnc(fnc,mus_id){
        if (fnc === 'del') {
            var rtn = confirm('削除してもよろしいですか？');
            if (rtn === false) {
                return false;
            }
        }
        var trg = document.forms["detail_form"];
        trg.method="post";
        document.detail_form["fnc"].value     =fnc;
        if (fnc !== 'add') {
            document.detail_form["mus_id"].value  =mus_id;
            var chg_name =document.getElementsByName("name_" + mus_id)[0].value;
            document.detail_form["name"].value    =chg_name;

        }else if(fnc === 'add'){
            var add_name =document.getElementsByName("add_name")[0].value;
            document.detail_form["name"].value    =add_name;
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

