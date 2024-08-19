{{--検索--}}
<form method="GET" action="{{ route('admin-music-search') }}">

    検索条件
    <div class="row g-3 align-items-end">
        <div class="col-sm-12">
            <input type="text" id="keyword" name="keyword" class="form-control" value="{{$input['keyword'] ?? ''}}" placeholder="検索(曲名)">
        </div>
        <div class="d-flex justify-content-center">
            <button type="submit" class="btn btn-success">検索</button>
        </div>
    </div>
</form>
