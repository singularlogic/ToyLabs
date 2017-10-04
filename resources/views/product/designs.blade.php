@extends('layouts.default', ['class' => ''])

@section('title', 'Product Designs')

@section('content')
        <div class="ui main container">
            <!-- Product / Designs -->
            <h1 class="ui left floated header">Product Designs</h1>
            <a href="/dashboard" class="ui right floated basic button">
                <i class="chevron left icon"></i>Back to Dashboard
            </a>
            <div class="ui clearing divider"></div>

            <designs-table
                :_designs="{{ json_encode($designs) }}"
                :_product_id="{{ $id }}"
            ></designs-table>
        </div>
@endsection
