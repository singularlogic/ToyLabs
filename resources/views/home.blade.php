@extends('layouts.default', ['class' => 'hidden'])

@section('title', 'Home')

@section('content')
        <!-- Home -->
        <div class="pusher">
            <div class="ui inverted vertical masthead center aligned segment" style="background-image: url('images/bg-1.jpg'); background-size: cover;">
                <div class="ui container">
                    @include('partials.menu')

                    <div class="ui text container">
                         <h1 class="ui inverted header">
                            <img class="ui image" src="/images/toylabs_logo_big_flat.svg" />
                            <div class="content">Welcome to ToyLabs</div>
                            <div class="sub header">New methodology to accelerate innovation in the toy sector</div>
                        </h1>
                    </div>
                </div>
            </div>

            <products-grid class="stripe"
                size="three"
                :detailed="true"
                :products="{{ json_encode($products) }}"
            ></products-grid>
        </div>
@endsection
