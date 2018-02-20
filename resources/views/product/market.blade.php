@extends('layouts.default', ['class' => ''])

@section('title', 'Market Analysis')

@section('content')
        <div class="ui main container">
            <h1 class="ui left floated header">Market Analysis</h1>
            <a href="/dashboard" class="ui right floated basic button">
                <i class="chevron left icon"></i>Back to Dashboard
            </a>
            <div class="ui clearing divider"></div>
            <market-analysis
                    :_project="{{ json_encode($project) }}"
                    :_analyses="{{ json_encode($analyses) }}"
                    :_product_id="{{ $product_id }}"
            ></market-analysis>
        </div>
@endsection
