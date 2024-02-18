
{{-- アーティスト登録処理 --}}
<form method="POST" action="{{ route('admin-artist-reg') }}">
    @csrf
    <div class="row g-3 align-items-end" >
        <div class="col-sm">
            <label for="inputname" class="form-label">ｱｰﾃｨｽﾄ名(ﾒｲﾝ)</label>
            <input type="text" name="name" class="form-control" placeholder="name" value="{{$input['name'] ?? ''}}">
        </div>
        <div class="col-sm">
            <label for="inputname2" class="form-label">ｱｰﾃｨｽﾄ名(ｻﾌﾞ)</label>
            <input type="text" name="name2" class="form-control" placeholder="name" value="{{$input['name2'] ?? ''}}">
        </div>
        <div class="col-md-3">
            <label for="inputbirth" class="form-label">デビュー</label>
            <input type="date" name="debut" class="form-control" value="{{$input['debut'] ?? ''}}">
        </div>
        <div class="col-md-2">
            <label for="inputsex" class="form-label">その他</label>
            <select id="inputState" name="sex" class="form-select">
                <option value="0" {{ isset($input['sex']) && $input['sex'] === '0' ? 'selected' : '' }}>ｸﾞﾙｰﾌﾟ</option>
                <option value="1" {{ isset($input['sex']) && $input['sex'] === '1' ? 'selected' : '' }}>男性</option>
                <option value="2" {{ isset($input['sex']) && $input['sex'] === '2' ? 'selected' : '' }}>女性</option> 
            </select>     
        </div>
        <div class="col-md-2">
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

{{--アーティスト登録履歴--}}
@if(isset($artists))
    <table class="table table-striped table-hover table-bordered fs-6 ">
        <thead>
        <tr>
            <th scope="col" class="fw-light">ｱｰﾃｨｽﾄ名(ﾒｲﾝ)</th>
            <th scope="col" class="fw-light">ｱｰﾃｨｽﾄ名(ｻﾌﾞ)</th>
            <th scope="col" class="fw-light">ﾃﾞﾋﾞｭｰ日</th>
            <th scope="col" class="fw-light">その他</th>
            <th scope="col" class="fw-light">データ登録日</th>
            <th scope="col" class="fw-light">データ更新日</th>
        </tr>
        </thead>
        @foreach($artists as $artist)
            <tr>
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
            </tr>
        @endforeach
        </tbody>
    </table>
@endif
