@extends('layouts.default', ['class' => ''])

@section('title', $title)

@section('content')
        <div class="ui main container">
            <!-- Product / Create -->
            <h1 class="ui left floated header">{{ $title }}</h1>
            <a href="/dashboard" class="ui right floated basic button">
                <i class="chevron left icon"></i>Back to Dashboard
            </a>
            <div class="ui clearing divider"></div>

            <product-create
                :_product="{{ json_encode($product) }}"
                :_user="{{ json_encode($user) }}"
                :_organizations="{{ json_encode($organizations) }}"
                :_categories="{{ json_encode($categories) }}"
                :_ages="{{ json_encode($ages) }}"
            ></product-create>
        </div>
@endsection
