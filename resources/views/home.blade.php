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

            <div class="ui vertical stripe segment container">
                <h1 class="ui header">Projects</h1>
                <div class="ui divider"></div>

                <div class="ui three cards">
@for ($i = 0; $i < 6; $i++)
                    <div class="card">
                        <div class="image">
                            <img src="http://lorempixel.com/300/150/" />
                        </div>
                        <div class="content">
                            <div class="header"><a href="/project/1">Lorem Ipsum</a></div>
                            <div class="meta"><a href="javascript:void(0)">Dolls</a></div>
                            <div class="description">Lorem ipsum dolor sit amet, consectetur adipiscing elit.</div>
                        </div>
                        <div class="extra content">
                            <span class="right floated"><i class="heart icon"></i> 13 Likes</span>
                            <span><i class="comments icon"></i>4 Comments</span>
                        </div>
                    </div>
@endfor
                </div>
            </div>
        </div>
@endsection
