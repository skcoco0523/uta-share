<?//カスタムカテゴリの更新　music_showで使用?>
@if(isset($custom_category) && isset($detail_id))
<div class="d-flex overflow-auto contents_box">
    <ul class="nav nav-pills">
        @foreach ($custom_category as $key => $category)
        <li class="nav-item nav-item-red">
            <a bit_num="{{ $category->bit_num }}" class="nav-link-red {{ $category->status ? 'active' : '' }}" onclick="chgToCustomCategory({{ $detail_id }}, {{ $category->bit_num }})">
                {{$category->name}}
            </a>
        </li>
        @endforeach
    </ul>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // カスタムカテゴリ状態初期値を定義
        <?php if(isset($custom_category)){ ?>
            <?php foreach ($custom_category as $category)  { ?>
                setCustomCategoryActions({{$category->bit_num }}, @json($category->status));
            <?php } ?>
        <?php } ?>

    });

</script>
@endif

<?//カスタムカテゴリ毎の表示切替　favorite_show、friend_showで使用?>
@if(isset($custom_category_list))
<div class="d-flex overflow-auto contents_box">
    <ul class="nav nav-pills">
        @foreach ($custom_category_list as $key => $category)
        <li class="nav-item nav-item-red">
            <a class="nav-link nav-link-red {{ $bit_num==$category->bit_num ? 'active' : '' }}" onclick="redirectToFavoriteShow('category','{{$category->bit_num}}')">
                {{$category->name}}
            </a>
        </li>
        @endforeach
    </ul>
</div>

<script>

</script>
@endif

