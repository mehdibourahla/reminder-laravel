@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-lg-3 col-md-6 p-5">
            <img src={{ $user->profile->profileImage() }} class=" round" style="height: 200px;" alt="">

        </div>
        <div class="col-lg-9 col-md-6 pt-5">
            <div class="d-flex align-items-center">
                <div class="h4 mr-4">{{$user->username}}</div>
                <follow-button user-id="{{$user->id}}"></follow-button>
            </div>
            <div class="d-flex pt-3">
                <div class="pr-5"><strong>{{$messagesCount}}</strong> messages </div>
                <div class="pr-5"><strong>{{$followersCount}}</strong> followers</div>
                <div class="pr-5"><strong>{{$followingCount}}</strong> following</div>
            </div>
            <div class="pt-3 font-weight-bold">{{$user->name}}</div>
            <div>{{$user->profile->bio}}</div>
            <div><a href="https://www.lipsum.com/">{{$user->profile->url}}</a></div>
        </div>
    </div>
    <profile-messages user="{{ $isAuthenticated }}" profile-id="{{ $user->id }}"></profile-messages>
</div>
@endsection

@push('js')
<script>

</script>
@endpush