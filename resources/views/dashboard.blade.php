@extends('layouts.default', ['class' => ''])

@section('title', 'Dashboard')

@section('content')
        <!-- Dashboard -->
        <div class="ui main container" id="dashboard">
            @include('partials.status')

            <div class="ui orange pointing secondary menu">
                <a class="active item" data-tab="myproducts">My Products</a>
                <a class="item" data-tab="mycollaborations">My Collaborations</a>
                <div class="right menu">
@if($is_complete)
                    <div class="item" style="padding-right: 6px;">
                        <a class="ui labeled orange mini icon button" href="/product/create" id="newButton">
                            <i class="icon plus"></i>
                            New Product
                        </a>
                    </div>
@endif
                    <div class="ui category search item">
                        <div class="ui icon input">
                            <input type="text" placeholder="Search..." />
                            <i class="search link icon"></i>
                        </div>
                        <div class="results"></div>
                    </div>
                </div>
            </div>

            <div class="ui active tab basic segment" data-tab="myproducts">
                <product-list
                    :products="{{ json_encode($products) }}"
                ></product-list>
            </div>
            <div class="ui tab basic segment" data-tab="mycollaborations"></div>

        </div>
@endsection
