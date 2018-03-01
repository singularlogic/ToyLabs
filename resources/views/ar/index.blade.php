@extends('layouts.default', ['class' => ''])

@section('title', 'AR Models')

@section('content')
        <div class="ui main container">
            <!-- AR / Index -->
            <h1 class="ui left floated header">
                {{ __('Augmented Reality Models') }}
                <div class="sub header">{{ ucfirst($back['type']) }}:  {{ $product->title }}</div>
            </h1>
            <a href="/product/{{ $back['id'] }}/{{ $back['type'] }}s" class="ui right floated basic button">
                <i class="chevron left icon"></i>Back to {{ ucfirst($back['type']) . 's' }}
            </a>
            <div class="ui clearing divider"></div>

            <a-r-models-table
                :_type="'{{ $back['type'] }}'"
                :_id="{{ $product->id }}"
                :_models="{{ json_encode($models) }}"
            ></a-r-models-table>
        </div>
@endsection
