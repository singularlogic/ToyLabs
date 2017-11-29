@extends('layouts.default', ['class' => ''])

@section('title', 'Collaboration discussion')

@section('content')
        <!-- Collaborations/Contact -->
        <div class="ui main container">
            <h1 class="ui left floated header">
                [{{ ucfirst($type) }}] {{ $name }}
                <div class="sub header">Collaboration discusion with <strong>{{ $organization->name }}</strong></div>
            </h1>
            <div class="ui clearing divider"></div>

            <div class="ui icon negative message">
                <i class="warning icon"></i>
                <i class="close icon"></i>
                <div class="content">
                    <div class="header">Important</div>
                    <p>Before you add someone to your {{ $type }}, make sure you discuss and agree on the terms of your collaboration.  If needed, you can use the form below to attach and exchange documents (e.g. NDA agreements, contracts, etc) to help you reach an agreement.</p>
                </div>
            </div>


        </div>
@endsection
