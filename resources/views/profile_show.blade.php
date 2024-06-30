@extends('layouts.app')

<?//コンテンツ?>  
@section('content')
<div class="card">
    <div class="card-header">{{ __('Profile') }}</div>

    <div class="card-body">
        <form method="POST" action="{{ route('profile-change') }}">
            @csrf

            <div class="row mb-3">
                <label for="email" class="col-md-4 col-form-label text-md-end">{{ __('Name') }}</label>

                <div class="col-md-6">
                    <input id="name" type="text" class="form-control" name="name" value="{{ $profile->name }}" required autocomplete="name" autofocus>

                </div>
            </div>

            <div class="row mb-3">
                <label for="email" class="col-md-4 col-form-label text-md-end">{{ __('Email Address') }}</label>

                <div class="col-md-6">
                    <input id="email" type="email" class="form-control" name="email" value="{{ $profile->email}}" required autocomplete="email">
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-4" style="display: flex; align-items: center;">
                    <label for="friend_code" class="col-form-label text-md-end">{{ __('Friend_code') }}　</label>
                    <p onclick="openShareModal('{{ route('friendlist-show', ['friend_code' => $profile->friend_code]) }}')" class="mb-0">
                        <i class="fa-regular fa-share-from-square icon-20"></i>
                    </p>
                </div>
                <div class="col-md-6">
                    <input id="friend_code" type="text" class="form-control" name="friend_code" value="{{ $profile->friend_code }}" disabled autocomplete="friend_code" autofocus>
                </div>
            </div>

            <div class="row mb-3">
                <label for="password" class="col-md-4 col-form-label text-md-end">{{ __('Birthdate') }}</label>

                <div class="col-md-6">
                    <input id="birthdate" type="date" class="form-control" name="birthdate" value="{{ \Carbon\Carbon::parse($profile->birthdate)->format('Y-m-d')  }}"  required autocomplete="birthdate" autofocus>

                </div>
            </div>
            
            <div class="row mb-3">
                <div class="col-md-6 offset-md-4">
                    <div class="form-check">
                        <input type="hidden" name="release_flag" value="0"> <!-- デフォルト値 -->
                        <input class="form-check-input" type="checkbox" name="release_flag" value="1" {{ $profile->release_flag ? 'checked' : '' }}>

                        <label class="form-check-label" for="release_flag">
                            {{ __('Restrict disclosure to friends') }}
                        </label>
                    </div>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6 offset-md-4">
                    <div class="form-check">
                        <input type="hidden" name="mail_flag" value="0"> <!-- デフォルト値 -->
                        <input class="form-check-input" type="checkbox" name="mail_flag" value="1" {{ $profile->mail_flag ? 'checked' : '' }}>

                        <label class="form-check-label" for="mail_flag">
                            {{ __('Restricting email delivery') }}
                        </label>
                    </div>
                </div>
            </div>

            <div class="row mb-0">
                <div class="col-md-6 offset-md-4">
                    <button type="submit" class="btn btn-primary">
                        {{ __('Update') }}
                    </button>
                </div>
            </div>

        </form>
    </div>
</div>

<?//ェアポップアップモーダル?>  
@include('modals.share-modal')

@endsection
