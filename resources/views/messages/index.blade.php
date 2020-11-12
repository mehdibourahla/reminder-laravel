@extends('layouts.app')

@section('content')
<div class="container">

    @foreach($messages as $message)
    <div class="row">
        <div class="col-lg-8 my-3 ">

            <message-component message="{{ $message }}" likes="{{ $likes }}" hides="{{ $hides }}"
                favourites="{{ $favourites }}"></message-component>
        </div>
    </div>
    @endforeach

    <div class="row">
        <div class="col-12 d-flex justify-content-center">
            {{$messages->links()}}
        </div>
    </div>

</div>
@endsection