@extends('layouts.default', ['class' => ''])

@section('title', $title)

@section('content')
        <!-- Product / Production -->
        <div class="ui main container">
            <h1 class="ui left floated header">
                {{ $title }}
                <div class="sub header">Product: {{ $product->title }}</div>
            </h1>
            <a href="/dashboard" class="ui right floated basic button">
                <i class="chevron left icon"></i>Back to Dashboard
            </a>
            <div class="ui clearing divider"></div>

            <div class="ui info icon message">
                <i class="thumbs up outline icon"></i>
                <div class="content">
                    <div class="header">
                        Congratulations!
                    </div>
                    <p>Your product creation is now complete!</p>
                </div>
            </div>
        </div>
@endsection