{{-- resources/views/components/pagination.blade.php --}}

@php
    // デフォルト値を設定
    $defaultParams = [
        'paginator' => null,
        'additionalParams' => []
    ];
    // 引き渡されたデータをマージ
    $params = array_merge($defaultParams, get_defined_vars());
    $paginator = $params['paginator'];
    $additionalParams = $params['additionalParams'];
@endphp

@if($paginator)
    <nav aria-label="Page navigation example">
        <ul class="pagination justify-content-end">
            <li class="page-item {{ $paginator->currentPage() == 1 ? 'disabled' : '' }}">
                <a class="page-link" href="{{ $paginator->previousPageUrl() . '&' . http_build_query($additionalParams) }}" aria-label="Previous">
                    <span aria-hidden="true">Previous</span>
                </a>
            </li>
            @for ($i = 1; $i <= $paginator->lastPage(); $i++)
                <li class="page-item {{ $paginator->currentPage() == $i ? 'active' : '' }}">
                    <a class="page-link" href="{{ $paginator->url($i) . '&' . http_build_query($additionalParams) }}">{{ $i }}</a>
                </li>
            @endfor
            <li class="page-item {{ $paginator->currentPage() == $paginator->lastPage() ? 'disabled' : '' }}">
                <a class="page-link" href="{{ $paginator->nextPageUrl() . '&' . http_build_query($additionalParams) }}" aria-label="Next">
                    <span aria-hidden="true">Next</span>
                </a>
            </li>
        </ul>
    </nav>
@endif
