@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Edit Profile</div>

                <div class="card-body">
                    <form method="post" enctype="multipart/form-data" action="/profile/{{$user->id}}">
                        @csrf
                        @method('PATCH')

                        <div class="form-g roup row">
                            <label for="username" class="col-md-4 col-form-label text-md-right"> Username</label>

                            <div class="col-md-6">
                                <input id="username" type="username"
                                    class="form-control @error('username') is-invalid @enderror" name="username"
                                    value="{{old('username') ?? $user->username }}" autocomplete="username">

                                @error('username')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row pt-3">
                            <label for="picture" class="col-md-4 col-form-label text-md-right"> Picture</label>

                            <div class="col-md-6">
                                <input type="file" name="picture" id="picture" class="form-control-file">

                                @error('picture')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="bio" class="col-md-4 col-form-label text-md-right"> Bio</label>

                            <div class="col-md-6">
                                <input id="bio" type="bio" class="form-control @error('bio') is-invalid @enderror"
                                    name="bio" value="{{old('bio') ?? $user->profile->bio }}" autocomplete="bio">

                                @error('bio')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="url" class="col-md-4 col-form-label text-md-right"> URL</label>

                            <div class="col-md-6">
                                <input id="url" type="url" class="form-control @error('url') is-invalid @enderror"
                                    name="url" value="{{ old('url') ?? $user->profile->url }}" autocomplete="url">

                                @error('url')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    Save profile
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection