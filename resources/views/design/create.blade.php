@extends('layouts.default', ['class' => ''])

@section('title', $title)

@section('content')
    <div class="ui main container">
        <!-- Design / Create -->
        <h1 class="ui left floated header">{{ $title }}</h1>
        <a href="/product/{{ $product_id }}/designs" class="ui right floated basic button">
            <i class="chevron left icon"></i>Back to Designs
        </a>
        <div class="ui clearing divider"></div>

        <design-create
            :_design="{{ json_encode($design) }}"
            :_product_id="{{ $product_id }}"
        ></design-create>
    </div>
@endsection
