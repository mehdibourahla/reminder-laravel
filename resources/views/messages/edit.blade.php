@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Edit Message</div>

                <div class="card-body">
                    <form method="post" id="edit_message" enctype="multipart/form-data" action="/m/{{$message->id}}">
                        @csrf
                        @method('PATCH')

                        <div class="form-group row">
                            <label for="caption" class="col-md-4 col-form-label">Message caption</label>

                            <input id="caption" name="caption" type="text"
                                class="form-control @error('caption') is-invalid @enderror"
                                value="{{ old('caption') ?? $message->caption }}" autocomplete="caption" autofocus>

                            @error('caption')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                        <div class="form-group row">
                            <label for="description" class="col-md-4 col-form-label">Message description</label>

                            <textarea id="description" name="description" rows="10" form="edit_message"
                                class="form-control @error('description') is-invalid @enderror"
                                description="description" autocomplete="description"
                                autofocus>{{ old('description') ?? $message->description }}</textarea>

                            @error('description')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>

                        <div class="form-group row pt-4">
                            <button class="btn btn-primary">Save Message</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection