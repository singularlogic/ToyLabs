@extends('layouts.default', ['class' => ''])

@section('title', 'Feedback')

@section('content')
        <!-- Collaborations/Feedback -->
        <div class="ui main container">
            <h1 class="ui left floated header">
                {{ __('Feedback') }}
                <div class="sub header">{{ ucfirst($back['type']) }}:  {{ $product->title }}</div>
            </h1>
            <a href="/product/{{ $back['id'] }}/{{ $back['type'] }}s" class="ui right floated basic button">
                <i class="chevron left icon"></i>Back to {{ $back['name'] }}
            </a>
            <div class="ui clearing divider"></div>

            <feedback-page
                :type="`{{ $back['type'] }}`"
                :id="{{ $id }}"
            ></feedback-page>
        </div>
@endsection
