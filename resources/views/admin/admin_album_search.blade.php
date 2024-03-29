
{{-- アルバム情報更新処理 --}}
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
                @foreach($artists as $artist)
                <option value="{{ $artist->name }}" data-id="{{ $artist->id }}">{{ $artist->name }}</option>
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
                    <form method="POST" action="{{ route('admin-album-del') }}">
                        @csrf
                        <input type="hidden" name="id" value="{{$album->id}}">
                        <input type="hidden" name="aff_id" value="{{$album->aff_id}}">
                        <input type="hidden" name="keyword" value="{{$input['keyword'] ?? ''}}">
                        <input type="hidden" name="music_list" value="{{$album->music_list}}">
                        <input type="submit" value="削除" class="btn btn-danger">
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

<script>

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

