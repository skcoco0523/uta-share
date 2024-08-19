{{--検索--}}
<form method="GET" action="{{ route('admin-recommend-search') }}">

    検索条件
    <div class="row g-3 align-items-end">
    <div class="col-sm-12">
        <input type="text" id="keyword" name="keyword" class="form-control" value="{{$input['keyword'] ?? ''}}" placeholder="検索(登録名)">
    </div>
    <div class="col-md-12">
            <select id="inputState" name="category" class="form-select" placeholder="カテゴリ">
                <option value="" {{ ($input['category'] ?? '') == '' ? 'selected' : '' }}>-- 選択してください --</option>
                <option value="0" {{ ($input['category'] ?? '') == '0' ? 'selected' : '' }}>曲</option>
                <option value="1" {{ ($input['category'] ?? '') == '1' ? 'selected' : '' }}>アーティスト</option>
                <option value="2" {{ ($input['category'] ?? '') == '2' ? 'selected' : '' }}>アルバム</option>
                <option value="3" {{ ($input['category'] ?? '') == '3' ? 'selected' : '' }}>プレイリスト</option>
            </select>
    </div>
    <div class="d-flex justify-content-center">
        <button type="submit" class="btn btn-success">検索</button>
    </div>
</div>
</form>