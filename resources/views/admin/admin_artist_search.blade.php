
{{-- アーティスト情報更新処理 --}}
<form method="POST" action="{{ route('admin-artist-chg') }}">
    @csrf
    <div class="row g-3 align-items-end" >
        <input type="hidden" name="keyword" value="{{$input['keyword'] ?? ''}}">
        <input type="hidden" name="id" value="{{$select->id ?? ''}}">
        <div class="col-sm">
            <label for="inputname" class="form-label">ｱｰﾃｨｽﾄ名(ﾒｲﾝ)</label>
            <input type="text" name="name" class="form-control" placeholder="name" value="{{$select->name ?? ''}}">
        </div>
        <div class="col-sm">
            <label for="inputname2" class="form-label">ｱｰﾃｨｽﾄ名(ｻﾌﾞ)</label>
            <input type="text" name="name2" class="form-control" placeholder="name" value="{{$select->name2 ?? ''}}">
        </div>
        <div class="col-md-3">
            <label for="inputbirth" class="form-label">デビュー</label>
            <input type="date" name="debut" class="form-control" value="{{$select->debut ?? ''}}">
        </div>
        <div class="col-md-2">
            <label for="inputsex" class="form-label">その他</label>
            <select id="inputState" name="sex" class="form-select">
                <option value="0" {{ ($select->sex ?? '') == '0' ? 'selected' : '' }}>ｸﾞﾙｰﾌﾟ</option>
                <option value="1" {{ ($select->sex ?? '') == '1' ? 'selected' : '' }}>男性</option>
                <option value="2" {{ ($select->sex ?? '') == '2' ? 'selected' : '' }}>女性</option>
            </select>
        </div>
        <div class="col-md-2">
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
<form method="GET" action="{{ route('admin-artist-search') }}">

    <div class="row g-3 align-items-end">
    <div class="col-sm-6">
        <label for="keyword" class="visually-hidden">検索(アーティスト名)</label>
        <input type="text" id="keyword" name="keyword" class="form-control" value="{{$input['keyword'] ?? ''}}" placeholder="検索(アーティスト名)">
    </div>
    <div class="col-auto align-self-end">
        <button type="submit" class="btn btn-success">検索</button>
    </div>
</div>
</form>

{{--アーティスト一覧--}}
@if(isset($artists))
    <table class="table table-striped table-hover table-bordered fs-6 ">
        <thead>
        <tr>
            <th scope="col" class="fw-light">#</th>
            <th scope="col" class="fw-light">ｱｰﾃｨｽﾄ名(ﾒｲﾝ)</th>
            <th scope="col" class="fw-light">ｱｰﾃｨｽﾄ名(ｻﾌﾞ)</th>
            <th scope="col" class="fw-light">ﾃﾞﾋﾞｭｰ日</th>
            <th scope="col" class="fw-light">その他</th>
            <th scope="col" class="fw-light">データ登録日</th>
            <th scope="col" class="fw-light">データ更新日</th>
            <th scope="col" class="fw-light"></th>
        </tr>
        </thead>
        @foreach($artists as $artist)
            <tr>
                <td class="fw-light">{{$artist->id}}</td>
                <td class="fw-light">{{$artist->name}}</td>
                <td class="fw-light">{{$artist->name2}}</td>
                <td class="fw-light">{{$artist->debut}}</td>
                <td class="fw-light">
                    @if($artist->sex === 0)     ｸﾞﾙｰﾌﾟ
                    @elseif($artist->sex === 1) 男性
                    @elseif($artist->sex === 2) 女性
                    @endif
                </td>
                <td class="fw-light">{{$artist->created_at}}</td>
                <td class="fw-light">{{$artist->updated_at}}</td>
                <td class="fw-light">
                    <form method="POST" action="{{ route('admin-artist-del') }}">
                        @csrf
                        <input type="hidden" name="id" value="{{$artist->id}}">
                        <input type="hidden" name="keyword" value="{{$input['keyword'] ?? ''}}">
                        <input type="submit" value="削除" class="btn btn-danger">
                    </form>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    <nav aria-label="Page navigation example">
        <ul class="pagination justify-content-end">
            <li class="page-item {{ $artists->currentPage() == 1 ? 'disabled' : '' }}">
                <a class="page-link" href="{{ $artists->previousPageUrl() }}" aria-label="Previous">
                    <span aria-hidden="true">Previous</span>
                </a>
            </li>
            @for ($i = 1; $i <= $artists->lastPage(); $i++)
                <li class="page-item {{ $artists->currentPage() == $i ? 'active' : '' }}">
                    <a class="page-link" href="{{ $artists->url($i) }}&keyword={{$input['keyword'] ?? ''}}">{{ $i }}</a>
                    
                </li>
            @endfor
            <li class="page-item {{ $artists->currentPage() == $artists->lastPage() ? 'disabled' : '' }}">
                <a class="page-link" href="{{ $artists->nextPageUrl() }}" aria-label="Next">
                    <span aria-hidden="true">Next</span>
                </a>
            </li>
        </ul>
    </nav>
@endif

<script>
document.addEventListener('DOMContentLoaded', function() {
    // テーブルの各行にクリックイベントリスナーを追加
    document.querySelectorAll('table tr').forEach(row => {
        row.addEventListener('click', () => {
            // クリックされた行からデータを取得
            const cells = row.querySelectorAll('td');
            const id = cells[0].textContent;
            const name = cells[1].textContent;
            const name2 = cells[2].textContent;
            const debut = cells[3].textContent;
            const sex = cells[4].textContent.trim();

            // フォームの対応するフィールドにデータを設定
            document.querySelector('input[name="id"]').value = id;
            document.querySelector('input[name="name"]').value = name;
            document.querySelector('input[name="name2"]').value = name2;
            document.querySelector('input[name="debut"]').value = debut;
                    
        // その他(性別)の選択肢を設定
        const selectSex = document.querySelector('select[name="sex"]');
        if (sex === 'ｸﾞﾙｰﾌﾟ')         selectSex.value = '0';
        else if (sex === '男性')    selectSex.value = '1';
        else if (sex === '女性')    selectSex.value = '2';
        else  selectSex.value = ''; // その他の場合、空の値にする
        });
    });
});
</script>