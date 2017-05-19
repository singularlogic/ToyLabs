@extends('layouts.default', ['class' => 'hidden'])

@section('title', 'Home')

@section('content')
        <!-- Home -->
        <div class="pusher" id="app">
            <div class="ui inverted vertical masthead center aligned segment">
                <div class="ui container">
                    @include('partials.menu')

                    <div class="ui text container">
                         <h1 class="ui inverted header">
                            <img class="ui image" src="/images/toylabs_logo_big_flat.svg" />
                            <div class="content">Welcome to ToyLabs</div>
                            <div class="sub header">New methodology to accelerate innovation in the player sector</div>
                        </h1>
                    </div>
                </div>

            </div>
        </div>
@endsection
