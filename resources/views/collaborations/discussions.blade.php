@extends('layouts.default', ['class' => ''])

@section('title', 'Discussions')

@section('content')
        <!-- Collaborations/Discussions -->
        <div class="ui main container">
            <h1 class="ui left floated header">Discussions</h1>
            <a href="/product/{{ $back['id'] }}/{{ $back['type'] }}s" class="ui right floated basic button">
                <i class="chevron left icon"></i>Back to {{ $back['name'] }}
            </a>
            <div class="ui clearing divider"></div>

            <table class="ui compact celled striped table">
                <thead>
                    <tr>
                        <th class="ten wide">{{ ucfirst($back['type']) }} Name</th>
                        <th class="four wide">Partner</th>
                        <th class="two wide">Last Message</th>
                    </tr>
                </thead>
            </table>
        </div>
@endsection
