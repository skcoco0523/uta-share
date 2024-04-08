
{{-- アルバム情報更新処理 --}}
@if(!(isset($album_detail)))
    <form id="alb_search_form" method="POST" action="{{ route('admin-album-chg') }}">
        @csrf
        <div class="row g-3 align-items-end" >
            <input type="hidden" name="keyword" value="{{$input['keyword'] ?? ''}}">
            <input type="hidden" id="id" name="id" value="{{$select->id ?? ($input['id'] ?? '')}}">
            <input type="hidden" name="aff_id" value="{{$select->aff_id ?? ($input['aff_id'] ?? '')}}">
            <div class="col-sm">
                <label for="inputname" class="form-label">ｱﾙﾊﾞﾑ名</label>
                <input type="text" name="alb_name" class="form-control" placeholder="name" value="{{ $select->name ?? ($input['alb_name'] ?? '') }}">
            </div>
            <div class="col-sm">
                <label for="inputart_name" class="form-label">ｱｰﾃｨｽﾄ名</label>
                <input class="form-control" list="artistSelect" name="art_name" placeholder="Artist to search..." value="{{$input['art_name'] ?? ''}}" autocomplete="off">
                <input type="hidden" id="selectedArtistId" name="art_id">
                <datalist id="artistSelect">
                    @foreach($artist as $art)
                    <option value="{{ $art->name }}" data-id="{{ $art->id }}">{{ $art->name }}</option>
                    @endforeach
                </datalist>
            </div>
            <div class="col-md-3">
                <label for="inputbirth" class="form-label">ﾘﾘｰｽ</label>
                <input type="date" name="release_date" class="form-control" value="{{$select->release_date ?? ($input['release_date'] ?? '') }}">
            </div>
        </div>

        <div class="row mt-3 align-items-stretch">
            {{--曲一覧--}}
            <div class="col-sm">
            <span class="link-like-text">楽曲リスト(詳細変更から更新)</span>
                <textarea class="form-control" name="music_list">{{$select->music_list ?? ($input['music_list'] ?? '') }}</textarea>
            </div>
            <div class="col-sm">
                <label for="affiliate-link" class="form-label">アフィリエイトリンク</label>
                <a href="https://affiliate.rakuten.co.jp/?l-id=af_header_logo" target="_blank" rel="noopener noreferrer" class="form-label">取得元URL</a>
                <textarea class="form-control" id="affiliate-link" name="aff_link">{{$input['aff_link'] ?? ''}}</textarea>
                
                <div class="form-group mt-3">
                    <label class="form-label">イメージ(上記リンクのイメージが表示)</label>
                </div>
                <div id="affiliate-preview">
                    {{--入力されたリンクが表示される部分--}}
                </div>
            </div>
            <div class="col-md-2 align-self-end mt-3">
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
<form method="GET" action="{{ route('admin-album-search') }}">

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
@if(isset($album))
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
            <th scope="col" class="fw-light">詳細変更
                
            </th>
            <th scope="col" class="fw-light"></th>
        </tr>
        </thead>
        @foreach($album as $alb)
            <tr>
                <td class="fw-light">{{$alb->id}}</td>
                <td class="fw-light">{{$alb->name}}</td>
                <td class="fw-light">{{$alb->art_name}}</td>
                <td class="fw-light">{{$alb->mus_cnt}}</td>
                <td class="fw-light">{{$alb->release_date}}</td>
                <td class="fw-light">{{$alb->created_at}}</td>
                <td class="fw-light">{{$alb->updated_at}}</td>
                <td><a href="{{ $alb->href }}">
                    <img src="{{ $alb->src }}" style="object-fit: cover; width: 100px; height: 100px;" alt="album_image">
                </a></td>
                <td class="fw-light">
                    <form method="GET" action="{{ route('admin-album-chgdetail') }}">
                        <input type="hidden" name="id" value="{{$alb->id}}">
                        <input type="submit" value="収録曲変更" class="btn btn-primary">
                    </form>
                </td>
                <td class="fw-light">
                    <form method="POST" action="{{ route('admin-album-del') }}">
                        @csrf
                        <input type="hidden" name="id" value="{{$alb->id}}">
                        <input type="hidden" name="aff_id" value="{{$alb->aff_id}}">
                        <input type="hidden" name="keyword" value="{{$input['keyword'] ?? ''}}">
                        <input type="hidden" name="music_list" value="{{$alb->music_list}}">
                        <input type="submit" value="削除" class="btn btn-danger">
                    </form>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    <nav aria-label="Page navigation example">
        <ul class="pagination justify-content-end">
            <li class="page-item {{ $album->currentPage() == 1 ? 'disabled' : '' }}">
                <a class="page-link" href="{{ $album->previousPageUrl() }}" aria-label="Previous">
                    <span aria-hidden="true">Previous</span>
                </a>
            </li>
            @for ($i = 1; $i <= $album->lastPage(); $i++)
                <li class="page-item {{ $album->currentPage() == $i ? 'active' : '' }}">
                    <a class="page-link" href="{{ $album->url($i) }}&keyword={{$input['keyword'] ?? ''}}">{{ $i }}</a>
                    
                </li>
            @endfor
            <li class="page-item {{ $album->currentPage() == $album->lastPage() ? 'disabled' : '' }}">
                <a class="page-link" href="{{ $album->nextPageUrl() }}" aria-label="Next">
                    <span aria-hidden="true">Next</span>
                </a>
            </li>
        </ul>
    </nav>
@endif

{{--収録曲変更--}}
@if(isset($album_detail))
    <div class="row g-3 align-items-end">
        <div class="col-sm">
            <label for="inputname" class="form-label">アルバム名</label>
            <input class="form-control" type="text" placeholder="{{$album_detail->name ?? ''}}" disabled>
        </div>
        <div class="col-sm">
            <label for="inputart_name" class="form-label">アーティスト名</label>
            <input class="form-control" type="text" placeholder="{{$album_detail->art_name ?? ''}}" disabled>
        </div>

        <label for="inputmusic" class="form-label">収録曲</label>

        <div class="row align-items-end">
            {{--変更フォーム--}}
            <span class="form-label" style="cursor: pointer; color: blue; text-decoration: underline;" onclick="toggleDetails_chg()">変更</span>
            
            <div id="detail_chg">
                @foreach($album_detail->music as $music)
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
        <input type="hidden" name="alb_id" value="{{$album_detail->id}}">
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
    
document.addEventListener('DOMContentLoaded', function () {

    var affiliateLinkInput = document.getElementById('affiliate-link');
    var affiliatePreview = document.getElementById('affiliate-preview');

    affiliateLinkInput.addEventListener('input', function () {
        var link = affiliateLinkInput.value.trim();
        if (link !== '') {
            // 入力されたリンクをそのまま表示する
            affiliatePreview.innerHTML = link;
        } else {
            // クリア
            affiliatePreview.innerHTML = '';
        }
    });

    // テーブルの各行にクリックイベントリスナーを追加
    document.querySelectorAll('table tr').forEach(row => {
        row.addEventListener('click', () => {
            // クリックされた行からデータを取得
            const cells = row.querySelectorAll('td');
            const id = cells[0].textContent;
            const alb_name = cells[1].textContent;
            const art_name = cells[2].textContent;
            const release_date = cells[4].textContent;
            const aff_id_input = row.querySelector('input[name="aff_id"]');
            const music_list_input = row.querySelector('input[name="music_list"]');
            //const music_list_input = document.querySelector('input[name="music_list"]');
            const aff_id_value = aff_id_input.value;
            const music_list_value = music_list_input.value;

            // フォームの対応するフィールドにデータを設定
            document.querySelector('input[name="id"]').value = id;
            document.querySelector('input[name="alb_name"]').value = alb_name;
            document.querySelector('input[name="art_name"]').value = art_name;
            document.querySelector('input[name="release_date"]').value = release_date;
            document.querySelector('input[name="aff_id"]').value = aff_id_value; // aff_idの値を設定
            document.querySelector('textarea[name="music_list"]').value = music_list_value; // alb_musの値を設定
        });
    });
    
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
});
</script>

