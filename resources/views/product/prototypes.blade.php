@extends('layouts.default', ['class' => ''])

@section('title', 'Product Prototypes')

@section('content')
    <div class="ui main container">
        <!-- Product / Prototypes -->
        <h1 class="ui left floated header">Product Prototypes</h1>
        <a href="/dashboard" class="ui right floated basic button">
            <i class="chevron left icon"></i>Back to Dashboard
        </a>
        <div class="ui clearing divider"></div>

        <prototypes-table
            :_prototypes="{{ json_encode($prototypes) }}"
            :_product_id="{{ $id }}"
        ></prototypes-table>
    </div>
@endsection
