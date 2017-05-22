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
                <button class="circular ui right floated icon orange button" data-tooltip="Create a new Project" data-position="left center" data-inverted>
                    <i class="icon plus"></i>
                </button>
            </div>

            <div class="ui clearing divider"></div>
            @include('partials.status')

            <div class="ui ordered six mini steps">
                <a class="completed step">
                    <div class="content">
                        <div class="title">Analysing</div>
                    </div>
                </a>
                <a class="completed step">
                    <div class="content">
                        <div class="title">Partner Matching</div>
                        <div class="description"></div>
                    </div>
                </a>
                <a class="active step">
                    <div class="content">
                        <div class="title">Designing</div>
                        <div class="description"></div>
                    </div>
                </a>
                <a class="disabled step">
                    <div class="content">
                        <div class="title">Prototyping</div>
                        <div class="description"></div>
                    </div>
                </a>
                <a class="disabled step">
                    <div class="content">
                        <div class="title">Certifying</div>
                        <div class="description"></div>
                    </div>
                </a>
                <a class="disabled step">
                    <div class="content">
                        <div class="title">Release</div>
                        <div class="description"></div>
                    </div>
                </a>
            </div>
        </div>
@endsection
