@extends('layouts.app')

@section('content')
<div class="container">

    <form action="/m" enctype="multipart/form-data" method="post" id="create_message">
        @csrf
        <div class="row">
            <div class="col-8 offset-2">
                <div class="row">
                    <h1>Add New Message</h1>
                </div>
                <div class="form-group row">
                    <label for="caption" class="col-md-4 col-form-label">Message caption</label>

                    <input id="caption" name="caption" type="text"
                        class="form-control @error('caption') is-invalid @enderror" value="{{ old('caption') }}"
                        autocomplete="caption" autofocus>

                    @error('caption')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>

                <div class="form-group row">
                    <label for="description" class="col-md-4 col-form-label">Message description</label>

                    <textarea id="description" name="description" rows="10" form="create_message"
                        class="form-control @error('description') is-invalid @enderror" description="description"
                        autocomplete="description" autofocus>{{ old('description') }}</textarea>

                    @error('description')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
                <div class="form-group row pt-4">
                    <button class="btn btn-primary">Add New Message</button>
                </div>
            </div>
        </div>

    </form>



</div>
@endsection