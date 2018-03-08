@extends('layouts.default', ['class' => ''])

@section('title', $title)

@section('content')
    <div class="ui main container">
        <!-- AR / Create -->
        <h1 class="ui left floated header">{{ $title }}</h1>
        <a href="/{{ $back['type'] }}/{{ $back['id'] }}/ar-models" class="ui right floated basic button">
            <i class="chevron left icon"></i>Back to AR Models
        </a>
        <div class="ui clearing divider"></div>

        <a-r-model-create
            :_model="{{ json_encode($model) }}"
            :_parent_id="{{ $back['id'] }}"
            _parent_type="{{ $back['type'] }}"
        ></a-r-model-create>
    </div>
@endsection
