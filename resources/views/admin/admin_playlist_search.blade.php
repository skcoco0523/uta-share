
{{-- プレイリスト情報更新処理 --}}
<form method="POST" action="{{ route('admin-playlist-chg') }}">
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
@if(isset($playlist))
    <table class="table table-striped table-hover table-bordered fs-6 ">
        <thead>
        <tr>
            <th scope="col" class="fw-light">#</th>
            <th scope="col" class="fw-light">ﾌﾟﾚｲﾘｽﾄ名</th>
            <th scope="col" class="fw-light">登録者</th>
            <th scope="col" class="fw-light">登録曲数</th>
            <th scope="col" class="fw-light">種別</th>
            <th scope="col" class="fw-light">データ登録日</th>
            <th scope="col" class="fw-light">データ更新日</th>
            <th scope="col" class="fw-light"></th>
        </tr>
        </thead>
        @foreach($playlist as $pl)
            <tr>
                <td class="fw-light">{{$pl->id}}</td>
                <td class="fw-light">{{$pl->name}}</td>
                <td class="fw-light">{{$pl->user_name}}</td>
                <td class="fw-light">{{$pl->mus_cnt}}</td>
                <td class="fw-light">
                    @if($pl->admin_flg === 0)     ユーザー
                    @elseif($pl->admin_flg === 1) 管理者
                    @endif
                </td>
                <td class="fw-light">{{$pl->created_at}}</td>
                <td class="fw-light">{{$pl->updated_at}}</td>
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

<script>
document.addEventListener('DOMContentLoaded', function() {
    // テーブルの各行にクリックイベントリスナーを追加
    document.querySelectorAll('table tr').forEach(row => {
        row.addEventListener('click', () => {
            // クリックされた行からデータを取得
            const cells = row.querySelectorAll('td');
            const id = cells[0].textContent;
            const name = cells[1].textContent;
            const user_name = cells[2].textContent;

            // フォームの対応するフィールドにデータを設定
            document.querySelector('input[name="id"]').value = id;
            document.querySelector('input[name="name"]').value = name;
            document.querySelector('input[name="user_name"]').value = user_name;
                    
        // その他(性別)の選択肢を設定
        const selectAdmin = document.querySelector('select[name="admin_flg"]');
        if (admin_flg === 'ユーザー')         selectAdmin.value = '0';
        else if (admin_flg === '管理者')    selectAdmin.value = '1';
        else  selectAdmin.value = ''; 
        });
    });
});
</script>