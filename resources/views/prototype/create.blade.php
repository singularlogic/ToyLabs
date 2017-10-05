@extends('layouts.default', ['class' => ''])

@section('title', $title)

@section('content')
    <div class="ui main container">
        <!-- Prototype / Create -->
        <h1 class="ui left floated header">{{ $title }}</h1>
        <a href="/product/{{ $product_id }}/prototypes" class="ui right floated basic button">
            <i class="chevron left icon"></i>Back to Prototypes
        </a>
        <div class="ui clearing divider"></div>

        <prototype-create
            :_prototype="{{ json_encode($prototype) }}"
            :_product_id="{{ $product_id }}"
        ></prototype-create>
    </div>
@endsection
