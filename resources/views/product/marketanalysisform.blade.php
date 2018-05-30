@extends('layouts.default', ['class' => ''])

@section('title', $title)

@section('content')
        <div class="ui main container">
            <h1 class="ui left floated header">{{ $title }}</h1>
            <a href="/product/{{ $product_id }}/marketanalysis" class="ui right floated basic button">
                <i class="chevron left icon"></i>Back to Market Analysis
            </a>
            <div class="ui clearing divider"></div>
            @if ($analysis_type === App\Http\Controllers\ANALYSIS_TYPE::TREND)
            <market-analysis-trend
                    :_analysis="{{ json_encode($analysis) }}"
                    :_product_id="{{ $product_id }}"
            ></market-analysis-trend>
            @elseif ($analysis_type === App\Http\Controllers\ANALYSIS_TYPE::SOCIAL)
            <market-analysis-social
                    :_analysis="{{ json_encode($analysis) }}"
                    :_product_id="{{ $product_id }}"
            ></market-analysis-social>
            @endif
        </div>
@endsection
