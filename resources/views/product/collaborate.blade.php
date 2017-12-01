@extends('layouts.default', ['class' => ''])

@section('title', __('Collaborations'))

@section('content')
        <div class="ui main container">
            <!-- Product / Collaborate -->

            <h1 class="ui left floated header">
                {{ __('Collaborations') }}
                <div class="sub header">{{ ucfirst($back['type']) }}:  {{ $product->title }}</div>
            </h1>
            <a href="/product/{{ $back['id'] }}/{{ $back['type'] }}s" class="ui right floated basic button">
                <i class="chevron left icon"></i>Back to {{ $back['name'] }}
            </a>
            <div class="ui clearing divider"></div>

            <collaborations-page
                :roles="{{ json_encode($roles) }}"
                :payment-types="{{ json_encode($paymentTypes) }}"
                :competencies="{{ json_encode($competencies) }}"
                :back="{{ json_encode($back) }}"
                :id="{{ $id }}"
                :type="'{{ $type }}'"
            ></collaborations-page>
{{--             <Search
                :roles="{{ json_encode($roles) }}"
                :payment-types="{{ json_encode($paymentTypes) }}"
                :competencies="{{ json_encode($competencies) }}"
                :back="{{ json_encode($back) }}"
                :id="{{ $id }}"
                type="{{ $type }}"
            ></Search>
 --}}        </div>
@endsection
