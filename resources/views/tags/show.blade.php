@extends('layouts.app')

@section('content')
<div class="container">

    <tag-messages tag="{{ $tag }}"></tag-messages>

</div>
@endsection

@push('js')
<script>

</script>
@endpush