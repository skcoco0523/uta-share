{{-- アルバム登録処理 --}}
<form id="alb_reg_form" method="POST" action="{{ route('admin-album-reg') }}">
    @csrf
    <div class="row g-3 align-items-end" >
        <div class="col-sm">
            <label for="inputname" class="form-label">ｱﾙﾊﾞﾑ名</label>
            <input type="text" name="alb_name" class="form-control" placeholder="name" value="{{$input['alb_name'] ?? ''}}">
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
            <label for="inputrelease_date" class="form-label">ﾘﾘｰｽ</label>
            <input type="date" name="release_date" class="form-control" value="{{$input['release_date'] ?? ''}}">
        </div>
    </div>

    <div class="row mt-3 align-items-stretch">
        {{--曲一覧--}}
        <div class="col-sm">
            <label for="inputart_music_list" class="form-label">楽曲リスト(改行込みで一括登録)</label>
            <textarea class="form-control" name="music_list">{{$input['music_list'] ?? ''}}</textarea>
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
            <input type="submit" value="登録" class="btn btn-primary">
        </div>
    </div>


</form>

{{--エラー--}}
@if(isset($msg))
    <div class="alert alert-danger">
        {!! nl2br(e($msg)) !!}
    </div>
@endif

{{--アルバム登録履歴--}}
@if(isset($albums))
    
    <label class="form-label">最近追加されたアルバム</label>
    <table class="table table-striped table-hover table-bordered fs-6 ">
        <thead>
        <tr>
            <th scope="col" class="fw-light">ｱﾙﾊﾞﾑ名</th>
            <th scope="col" class="fw-light">ｱｰﾃｨｽﾄ名</th>
            <th scope="col" class="fw-light">収録数</th>
            <th scope="col" class="fw-light">ﾘﾘｰｽ</th>
            <th scope="col" class="fw-light">ﾃﾞｰﾀ登録日</th>
            <th scope="col" class="fw-light">ﾃﾞｰﾀ更新日</th>
            <th scope="col" class="fw-light">ｲﾒｰｼﾞ&ﾘﾝｸ</th>
        </tr>
        </thead>
        @foreach($albums as $album)
            <tr>
                <td class="fw-light">{{$album->name}}</td>
                <td class="fw-light">{{$album->art_name}}</td>
                <td class="fw-light">{{$album->mus_cnt}}</td>
                <td class="fw-light">{{$album->release_date}}</td>
                <td class="fw-light">{{$album->created_at}}</td>
                <td class="fw-light">{{$album->updated_at}}</td>
                <td><a href="{{ $album->href }}">
                    <img src="{{ $album->src }}" style="object-fit: cover; width: 100px; height: 100px;" alt="album_image">
                </a></td>
                
            </tr>
        @endforeach
        </tbody>
    </table>
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
    //リストから選択時、art_idをpostできないため、再取得
    document.getElementById('alb_reg_form').addEventListener('submit', function(event) {
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
