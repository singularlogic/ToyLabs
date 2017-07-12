@extends('layouts.default', ['class' => ''])

@section('title', $title)

@section('content')
        <div class="ui main container">
            <!-- Product / Create -->
            <h1 class="ui header">{{ $title }}</h1>
            <div class="ui divider"></div>

            <product-create
                :_product="{{ json_encode($product) }}"
                :_user="{{ json_encode($user) }}"
                :_organizations="{{ json_encode($organizations) }}"
                :_categories="{{ json_encode($categories) }}"
            ></product-create>
        </div>
@endsection
