
{{-- ミュージック情報更新処理 --}}
<form id="mus_search_form" method="POST" action="{{ route('admin-music-chg') }}">
    @csrf
    <div class="row g-3 align-items-end" >
        <input type="hidden" name="keyword" value="{{$input['keyword'] ?? ''}}">
        <input type="hidden" id="id" name="id" value="{{$select->id ?? ($input['id'] ?? '')}}">
        <input type="hidden" name="aff_id" value="{{$select->aff_id ?? ($input['aff_id'] ?? '')}}">
        <input type="hidden" name="page" value="{{request()->input('page') ?? $input['page'] ?? '' }}">
        <div class="col-sm">
            <label for="inputname" class="form-label">曲名</label>
            <input type="text" name="alb_name" class="form-control" placeholder="name" value="{{ $select->name ?? ($input['mus_name'] ?? '') }}">
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
        <div class="col-sm">
            <label for="inputlink" class="form-label">ﾘﾝｸ</label>
            <input type="text" name="link" class="form-control" placeholder="https://..." value="{{$input['link'] ?? ''}}">
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
<form method="GET" action="{{ route('admin-music-search') }}">

    <div class="row g-3 align-items-end">
    <div class="col-sm-6">
        <label for="keyword" class="visually-hidden">検索(曲名)</label>
        <input type="text" id="keyword" name="keyword" class="form-control" value="{{$input['keyword'] ?? ''}}" placeholder="検索(曲名)">
    </div>
    <div class="col-auto align-self-end">
        <button type="submit" class="btn btn-success">検索</button>
    </div>
</div>
</form>

{{--曲一覧--}}
@if(isset($musics))
    ｱﾌｨﾘｴｲﾄﾘﾝｸ(ｲﾒｰｼﾞ&ﾘﾝｸ)はアルバムの情報が優先されます
    {{--ﾊﾟﾗﾒｰﾀ--}}
    @php
        $additionalParams = ['keyword' => $input['keyword'] ?? '',];
    @endphp
    {{--ﾍﾟｰｼﾞｬｰ--}}
    @include('admin.layouts.pagination', ['paginator' => $musics,'additionalParams' => $additionalParams,])
    <table class="table table-striped table-hover table-bordered fs-6 ">
        <thead>
        <tr>
            <th scope="col" class="fw-light">#</th>
            <th scope="col" class="fw-light">曲名</th>
            <th scope="col" class="fw-light">ｱｰﾃｨｽﾄ名</th>
            <th scope="col" class="fw-light">アルバム名</th>
            <th scope="col" class="fw-light">ﾘﾘｰｽ</th>
            <th scope="col" class="fw-light">ﾘﾝｸ</th>
            <th scope="col" class="fw-light">データ登録日</th>
            <th scope="col" class="fw-light">データ更新日</th>
            <th scope="col" class="fw-light">ｲﾒｰｼﾞ&ﾘﾝｸ</th>
            <th scope="col" class="fw-light"></th>
        </tr>
        </thead>
        @foreach($musics as $music)
            <tr>
                <td class="fw-light">{{$music->id}}</td>
                <td class="fw-light">{{$music->name}}</td>
                <td class="fw-light">{{$music->art_name}}</td>
                <td class="fw-light">{{$music->alb_name}}</td>
                <td class="fw-light">{{$music->release_date}}</td>
                <td class="fw-light">{{$music->link}}</td>
                <td class="fw-light">{{$music->created_at}}</td>
                <td class="fw-light">{{$music->updated_at}}</td>
                <td><a href="{{ $music->href }}">
                    <img src="{{ $music->src }}" style="object-fit: cover; width: 100px; height: 100px;" alt="music_image">
                </a></td>
                <td class="fw-light">
                    <form method="POST" action="{{ route('admin-music-del') }}">
                        @csrf
                        <input type="hidden" name="id" value="{{$music->id}}">
                        <input type="hidden" name="name" value="{{$music->name}}">
                        <input type="hidden" name="aff_id" value="{{$music->aff_id}}">
                        <input type="hidden" name="keyword" value="{{$input['keyword'] ?? ''}}">
                        <input type="submit" value="削除" class="btn btn-danger">
                    </form>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    {{--ﾍﾟｰｼﾞｬｰ--}}
    @include('admin.layouts.pagination', ['paginator' => $musics,'additionalParams' => $additionalParams,])
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
            const link = cells[5].textContent;
            const aff_id_input = row.querySelector('input[name="aff_id"]');
            const aff_id_value = aff_id_input.value;

            // フォームの対応するフィールドにデータを設定
            document.querySelector('input[name="id"]').value = id;
            document.querySelector('input[name="alb_name"]').value = alb_name;
            document.querySelector('input[name="art_name"]').value = art_name;
            document.querySelector('input[name="release_date"]').value = release_date;
            document.querySelector('input[name="link"]').value = link;
            document.querySelector('input[name="aff_id"]').value = aff_id_value; // aff_idの値を設定
        });
    });
    
    //リストから選択時、art_idをpostできないため、再取得
    document.getElementById('mus_search_form').addEventListener('submit', function(event) {
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

