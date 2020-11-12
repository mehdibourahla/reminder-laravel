@extends('layouts.app')

@section('content')
<div class="container">

    {{$message->caption}}
    {{$message->user->username}}

</div>
@endsection