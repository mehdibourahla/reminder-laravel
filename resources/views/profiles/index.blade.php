@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-3 p-5">
            <img src={{ $user->profile->profileImage() }} class=" round" style="height: 200px;" alt="">

        </div>
        <div class="col-9 pt-5">
            <div class="d-flex align-items-center">
                <div class="h4 mr-4">{{$user->username}}</div>
                <follow-button user-id="{{$user->id}}" follows="{{$follows}}"></follow-button>
            </div>
            <div class="d-flex">
                <div class="pr-3"><strong>{{$messagesCount}}</strong> messages </div>
                <div class="pr-3"><strong>{{$followersCount}}</strong> followers</div>
                <div class="pr-3"><strong>{{$followingCount}}</strong> following</div>
            </div>
            <div class="pt-4 font-weight-bold">{{$user->name}}</div>
            <div>{{$user->profile->bio}}</div>
            <div><a href="https://www.lipsum.com/">{{$user->profile->url}}</a></div>
        </div>
    </div>
    <div class="row">
        @foreach($user->messages as $message)
        <div class="col-lg-4 col-md-6 col-sm-12 d-flex align-items-stretch py-2">
            <div class="card">
                <div class="card-body">
                    <a href="/m/{{$message->id}}">
                        <h5 class="card-title">{{$message->caption}}</h5>
                    </a>
                    <h6 class="card-subtitle mb-2 text-muted">{{$message->created_at}}</h6>
                    <p class="card-text">
                        @if (strlen($message->description) > 180)
                        {{substr($message->description,0,100)}}
                        ...
                        @else
                        {{$message->description}}
                        @endif
                    </p>
                    <a href="/m/{{$message->id}}">More...</a>
                    <a href="#" class="card-link">Card link</a>
                    <a href="#" class="card-link">Another link</a>
                </div>
            </div>
        </div>
        @endforeach

    </div>
</div>
@endsection