@extends('layouts.app')

<?//コンテンツ?>  
@section('content')
<a href="{{ url()->previous() }}" class="no-decoration">＜＜</a>
<div class="text-center ">
    <p class="card-text">フレンド：{{ $friend_profile->name }}</p>
</div>

<div class="d-flex overflow-auto contents_box">
    <ul class="nav nav-pills flex-nowrap">
        <li class="nav-item nav-item-red"><a class="nav-link nav-link-red active" onclick="openTab(event, 'songs')">曲</a></li>
        <li class="nav-item nav-item-red"><a class="nav-link nav-link-red" onclick="openTab(event, 'category')">カテゴリ別</a></li>
    </ul>
</div>
<br>

<?//テーブルリストは別ファイルで管理?>   
<div id="songs" class="tab-content active">
    @include('layouts.list_table', ['friend_table' => $favorite_list["mus"], 'table' => 'mus'])
</div>

<div id="category" class="tab-content">

</div>

@endsection

<script>
    // 初期表示設定
    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('songs').style.display = 'block';
    });
</script>

<style>
</style>
