@extends('layouts.default', ['class' => ''])

@section('title', $title)

@section('content')
        <!-- Product / Details -->
        <div class="ui main container">
            <h2 class="ui left floated header">{{ $title }}</h2>
            <likes class="ui right floated"
                :likes="{{ $product['likeCount'] }}"
                :id="{{ $product['id'] }}"
                liked="{{ $product['liked'] }}"
                type="product"
            ></likes>
            <div class="ui clearing divider"></div>

            <div class="ui horizontal divided list">
                <div class="item">
                    <strong>Ages</strong>: {{ $product['ages'] }}
                </div>
                <div class="item">
                    <strong>Category</strong>: {{ $product['category']['title'] }}
                </div>
                <div class="item">
                    <strong>Product Owner</strong>: {{ $product['owner']['name'] }}
                </div>
                <div class="item">
                    <strong>Status</strong>: {{ ucfirst($product['status']) }}
                </div>
            </div>

            <div class="ui basic segment">
                <img class="ui large left bordered floated image" src="/images/placeholder.jpg" />

                {!! nl2br(e($product['description'])) !!}
            </div>

            <div class="ui hidden clearing divider"></div>

@if(count($product['designs']))
            <h3 class="ui dividing header">Designs ({{ count($product['designs']) }})</h3>

            <products-grid
                :products="{{ json_encode($product['designs']) }}"
                :detailed=false
                size="four"
            ></products-grid>
@endif

@if(count($product['prototypes']))
            <h3 class="ui dividing header">Prototypes ({{ count($product['prototypes']) }})</h3>

            <products-grid
                :products="{{ json_encode($product['prototypes']) }}"
                :detailed=false
                size="four"
            ></products-grid>
@endif

            <comments
                :comments="{{ json_encode($product['comments']) }}"
                :model="{{ json_encode($model) }}"
            ></comments>
        </div>
@endsection
