@extends('layouts.app')

@section('content')
<div class="container">
    @if(Auth::user()->role === 'reporter')
        @include('reporter.dashboard')
    @elseif(Auth::user()->role === 'designated')
        @include('designated.dashboard')
    @elseif(Auth::user()->role === 'rescue')
        @include('rescue.dashboard')
    @elseif(Auth::user()->role === 'pnp')
        @include('pnp.dashboard')
    @elseif(Auth::user()->role === 'bfp')
        @include('bfp.dashboard')
    @endif
</div>
@endsection
