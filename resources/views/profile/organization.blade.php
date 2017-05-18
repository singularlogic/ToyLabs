@extends('layouts.default', ['class' => ''])

@section('title', $pagetitle or 'Edit Organization')

@section('content')
        <!-- Organization -->
        <div class="ui main container" id="app">
            <h1 class="ui header">{{ $pagetitle or 'Edit Organization' }}</h1>
            <div class="ui divider"></div>

            @include('partials.status')
        </div>
@endsection
