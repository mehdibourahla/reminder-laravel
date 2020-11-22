@extends('layouts.app')

@section('content')
<div class="container">
    <explore-messages user="{{ $user }}"></explore-messages>
</div>
@endsection

@push('js')
<script>

</script>
@endpush