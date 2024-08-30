@extends('layouts.app')

<?//コンテンツ?>  
@section('content')
<div class="card">
    <div class="card-header">{{ __('Request') }}</div>

    <div class="card-body">
        <form method="POST" action="{{ route('request-send') }}">
            @csrf
            <div class="row mb-3">
                <label for="type" class="col-md-4 col-form-label text-md-end">種別</label>

                <div class="col-md-6">
                    <select name="type" class="form-select" required>
                        <option value="0" {{ isset($input['type']) && $input['type'] === '0' ? 'selected' : '' }}>要望</option>
                        <option value="1" {{ isset($input['type']) && $input['type'] === '1' ? 'selected' : '' }}>問い合わせ</option>
                    </select>     

                </div>
            </div>

            <div class="row mb-3">
                <label for="message" class="col-md-4 col-form-label text-md-end">問い合わせ内容</label>

                <div class="col-md-6">
                    <textarea class="form-control" name="message" required>{{$input['message'] ?? ''}}</textarea>
                </div>
            </div>

            <div class="row mb-0">
                <div class="col-md-6 offset-md-4">
                    <button type="submit" class="btn btn-primary">
                        {{ __('Send') }}
                    </button>
                </div>
            </div>

        </form>
    </div>
</div>

<?//ェアポップアップモーダル?>  
@include('modals.share-modal')

@endsection
