@extends('layouts.default', ['class' => ''])

@section('title', 'Market Trend Analysis')

@section('content')
        <div class="ui main container">
            <h1 class="ui left floated header">Market Trend Analysis</h1>
            <a href="/product/{{ $product_id }}/marketanalysis" class="ui right floated basic button">
                <i class="chevron left icon"></i>Back to Market Analysis
            </a>
            <div class="ui clearing divider"></div>
            <market-analysis-trend
                    :_analysis="{{ json_encode($analysis) }}"
                    :_product_id="{{ $product_id }}"
            ></market-analysis-trend>
        </div>
@endsection
