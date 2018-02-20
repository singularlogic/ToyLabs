@extends('layouts.default', ['class' => ''])

@section('title', $title)

@section('content')
        <div class="ui main container">
            <h2 class="ui left floated header">{{ $title }}</h2>
            <likes class="ui right floated"
                :likes="{{ $meta['likeCount'] }}"
                :id="{{ $analysis_id }}"
                liked="{{ $meta['liked'] }}"
                type="analysis"
            ></likes>
            <div class="ui clearing divider"></div>
            <analysis
                    :analysis="{{ json_encode($analysis) }}"
                    :chart_data="{{ json_encode($chart_data) }}"
                    :analysis_id="{{ $analysis_id }}"
            ></analysis>


            <div class="ui hidden clearing divider"></div>

            <comments
                :comments="{{ json_encode($meta['comments']) }}"
                :model="{{ json_encode($model) }}"
            ></comments>
        </div>
@endsection
