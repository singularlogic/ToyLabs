@extends('layouts.default', ['class' => ''])

@section('title', $title)

@section('content')
        <!-- Product / Design -->
        <div class="ui main container">
            <h2 class="ui left floated header">{{ $title }}</h2>
            <likes class="ui right floated"
                :likes="{{ $design['likeCount'] }}"
                :id="{{ $design['id'] }}"
                liked="{{ $design['liked'] }}"
                type="design"
            ></likes>
            <div class="ui clearing divider"></div>

            <div class="ui basic segment">
                <img class="ui large left bordered floated image" src="{{ $design['image'] }}" />

                {!! nl2br(e($design['description'])) !!}
            </div>

            <div class="ui hidden clearing divider"></div>
@if(count($design['images']))
            <div class="ui dividing header">Images ({{ count($design['images']) }})</div>

            <gallery
                :images="{{ json_encode($design['images']) }}"
            ></gallery>
@endif

@if(count($design['prototypes']))
            <h3 class="ui dividing header">Prototypes ({{ count($design['prototypes']) }})</h3>

            <products-grid
                :products="{{ json_encode($design['prototypes']) }}"
                :detailed=false
                size="four"
            ></products-grid>
@endif

            <comments
                :comments="{{ json_encode($design['comments']) }}"
                :model="{{ json_encode($model) }}"
            ></comments>
        </div>
@endsection
