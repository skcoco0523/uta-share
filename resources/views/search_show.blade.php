@extends('layouts.app')

<div class="container">
    <div class="row justify-content-center">
    
    <div class="col-12 col-md-6">
        <?//コンテンツ?>  
        @section('content')

        <form action="{{ route('search-list-show') }}" method="GET" id="searchForm">
            <div class="input-group mb-3">
                <span class="input-group-text" id="basic-addon1"><i class="fa-solid fa-magnifying-glass"></i></span>
                <input type="search" id="searchInput" name="keyword" class="form-control" value="{{$input['keyword'] ?? ''}}" placeholder="曲、アーティスト、アルバムなど" aria-describedby="basic-addon1">
            </div>
        </form>
        
        {{-- 検索結果の補完候補 --}}
        <div id="searchSuggestions" style="display: none;">
            <table class="table table-borderless table-center">
                <tbody id="suggestionsList">
                    {{-- 検索結果をここに動的に追加 --}}
                </tbody>
            </table>
        </div>
        <?//入力時は以下を非表示にする?>
        <div id="historyList">
            <h4>履歴</h4>
            <ul>
                @foreach ($history as $his)
                    <li>{{ $his->search_query }} ({{ $his->created_at->format('Y-m-d H:i:s') }})</li>
                @endforeach
            </ul>
        </div>

        <?//ログインユーザーのみ表示させるナビ?>   
        @include('layouts.nav_menu')

    </div>
</div>
</div>
@endsection

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('searchInput');     //キーワード
        const searchForm = document.getElementById('searchForm');       // 検索フォーム
        const suggestionsList = document.getElementById('suggestionsList');
        const historyList = document.getElementById('historyList');

        function showSuggestions() {
            searchSuggestions.style.display = 'block';
            historyList.style.display = 'none'; // 履歴を非表示にする
        }

        function hideSuggestions() {
            searchSuggestions.style.display = 'none';
            historyList.style.display = 'block'; // 履歴を表示する
        }
        
        searchInput.addEventListener('input', function() {
            const query = searchInput.value.trim();
            if (query.length === 0) {
                hideSuggestions();
            } else {
                fetchSuggestions(query);
            }
        });

        function fetchSuggestions(query) {
            fetch(`{{ route('search-suggestions') }}?keyword=${query}`)
                .then(response => response.json())
                .then(data => {
                    suggestionsList.innerHTML = '';
                    if (data.length > 0) {
                        data.forEach(item => {
                            const suggestionItem = document.createElement('tr');
                            suggestionItem.innerHTML = `
                                <td class="col-12">
                                    <span>${item}</span>
                                    <i class="fa-solid fa-magnifying-glass" style="float: right;"></i>
                                </td>`;
                            suggestionItem.addEventListener('click', function() {
                                searchInput.value = item;
                                searchForm.submit(); // フォームを送信
                            });
                            suggestionsList.appendChild(suggestionItem);
                        });
                        showSuggestions();
                    } else {
                        suggestionsList.innerHTML = '<tr><td class="col-12">一致する結果がありません。</td></tr>';
                        showSuggestions();
                    }
                })
                .catch(error => {
                    console.error('Error fetching suggestions:', error);
                });
        }
    });
</script>

<style>
</style>
