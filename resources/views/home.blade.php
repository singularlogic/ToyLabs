@extends('layouts.default', ['class' => 'hidden'])

@section('title', 'Home')

@section('content')
        <!-- Home -->
        <div class="pusher" id="app">
            <div class="ui inverted vertical masthead center aligned segment">
                <div class="ui container">
                    @include('partials.menu')

                    <div class="ui text container">
                         <h1 class="ui inverted header">Welcome to ToyLabs</h1>
                         <h2>New methodology to accelerate innovation in the player sector</h2>
                    </div>
                </div>

            </div>
        </div>
@endsection
