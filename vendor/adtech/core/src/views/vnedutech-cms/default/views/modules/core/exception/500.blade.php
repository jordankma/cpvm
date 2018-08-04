@extends('layouts.default')

{{-- Page content --}}
@section('content')

    <div class="hgroup">
        <h1>Thats an error</h1>
        <h2>It seems that page you are looking for no longer exists.</h2>
        <a href="{{ route('backend.homepage') }}">
            <button type="button" class="btn btn-responsive button-alignment">Home</button>
        </a>
    </div>

@stop