@extends('layouts.app')

<?//コンテンツ?>  
@section('content')
<a href="{{ url()->previous() }}" class="no-decoration">＜＜</a>
<div class="d-flex overflow-auto contents_box">
    <ul class="nav nav-pills flex-nowrap">
        <li class="nav-item nav-item-red"><a class="nav-link nav-link-red active" onclick="openTab(event, 'accepted')">フレンド</a></li>
        <li class="nav-item nav-item-red"><a class="nav-link nav-link-red" onclick="openTab(event, 'search')">検索</a></li>
        <li class="nav-item nav-item-red"><a class="nav-link nav-link-red" onclick="openTab(event, 'pending')">承認待ち</a></li>
        <li class="nav-item nav-item-red"><a class="nav-link nav-link-red" onclick="openTab(event, 'request')">リクエスト</a></li>
        <li class="nav-item nav-item-red"><a class="nav-link nav-link-red" onclick="openTab(event, 'declined')">申請拒否</a></li>
    </ul>
</div>
<br>

<?//テーブルリストは別ファイルで管理?>   
<div id="accepted" class="tab-content">
    <h3>フレンド</h3>
    @include('layouts.friend_table', ['friendlist_table' => $friendlist["accepted"], 'status' => 'accepted'])
</div>
<div id="search" class="tab-content">
    <h3>検索</h3>
    <form action="{{ route('friendlist-show') }}" method="GET">
        <div class="input-group mb-3">
            <span class="input-group-text"><i class="fa-solid fa-magnifying-glass"></i></span>
            <input type="search" name="friend_code" class="form-control" placeholder="フレンドコード">
        </div>
    </form>
    @if(count($search_user))
        @include('layouts.friend_table', ['friendlist_table' => $search_user, 'status' => $search_user[0]->status])
    @endif
</div>

<div id="pending" class="tab-content">
    <h3>承認待ち</h3> 
    @include('layouts.friend_table', ['friendlist_table' => $friendlist["pending"], 'status' => 'pending'])
</div>

<div id="request" class="tab-content">
    <h3>リクエスト</h3>
    @include('layouts.friend_table', ['friendlist_table' => $friendlist["request"], 'status' => 'request'])
</div>

<div id="declined" class="tab-content">
    <h3>申請拒否</h3>
    @include('layouts.friend_table', ['friendlist_table' => $friendlist["declined"], 'status' => 'declined'])
</div>


@endsection

<script>
    // 初期表示設定
    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('accepted').style.display = 'block';
    });

</script>

<style>
</style>
