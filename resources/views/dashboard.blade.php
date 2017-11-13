@extends('layouts.default', ['class' => ''])

@section('title', 'Dashboard')

@section('content')
        <!-- Dashboard -->
        <div class="ui main container">
            <div class="ui clearing">
                <h1 class="ui left floated header">Products</h1>
                <div class="ui right floated search">
                    <div class="ui icon input">
                        <input class="prompt" type="text" placeholder="Search..." />
                        <i class="search icon"></i>
                    </div>
                    <div class="results"></div>
                </div>
@if($is_complete)
                <a class="circular ui right floated icon orange button" data-tooltip="Create a new Product" data-position="left center" data-inverted href="/product/create">
                    <i class="icon plus"></i>
                </a>
@endif
            </div>

            <div class="ui clearing divider"></div>
            @include('partials.status')

            <product-list
                :products="{{ json_encode($products) }}"
            ></product-list>

        </div>
@endsection
