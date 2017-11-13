@extends('layouts.default', ['class' => ''])

@section('title', $title)

@section('content')
        <!-- Product / Prototype -->
        <div class="ui main container">
            <h2 class="ui left floated header">{{ $title }}</h2>
            <likes class="ui right floated"
                :likes="{{ $prototype['likeCount'] }}"
                :id="{{ $prototype['id'] }}"
                liked="{{ $prototype['liked'] }}"
                type="prototype"
            ></likes>
            <div class="ui clearing divider"></div>

            <div class="ui basic segment">
                <img class="ui large left bordered floated image" src="{{ $prototype['image'] }}" />

                {!! nl2br(e($prototype['description'])) !!}
            </div>

            <div class="ui hidden clearing divider"></div>

@if(count($prototype['images']))
            <div class="ui dividing header">Images ({{ count($prototype['images']) }})</div>

            <gallery
                :images="{{ json_encode($prototype['images']) }}"
            ></gallery>
@endif

            <comments
                :comments="{{ json_encode($prototype['comments']) }}"
                :model="{{ json_encode($model) }}"
            ></comments>
        </div>
@endsection
