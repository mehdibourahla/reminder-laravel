@extends('layouts.app')

@section('content')
<profile-container profile-id="{{ $user->id }}" user="{{ $isAuthenticated }}">
</profile-container>
@endsection

@push('js')
<script>

</script>
@endpush