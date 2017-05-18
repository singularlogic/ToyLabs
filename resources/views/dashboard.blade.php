@extends('layouts.default', ['class' => ''])

@section('title', 'Dashboard')

@section('content')
        <!-- Dashboard -->
        <div class="ui main container" id="app">
            <div class="ui clearing">
                <h1 class="ui left floated header">My Projects</h1>
                <div class="ui right floated search">
                    <div class="ui icon input">
                        <input class="prompt" type="text" placeholder="Search..." />
                        <i class="search icon"></i>
                    </div>
                    <div class="results"></div>
                </div>
                <button class="circular ui right floated icon orange button" title="Create new project...">
                    <i class="icon plus"></i>
                </button>
            </div>

            <div class="ui clearing divider"></div>
            @include('partials.status')
        </div>
@endsection
