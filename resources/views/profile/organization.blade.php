@extends('layouts.default', ['class' => ''])

@section('title')
    {{ $pagetitle or 'EditOrganization' }}
@endsection

@section('content')
        <!-- Organization -->
        <div class="ui main container" id="app">
            <h1 class="ui header">{{ $pagetitle or 'Edit Organization' }}</h1>
            <div class="ui divider"></div>

            @include('partials.status')

            <Organization
                :_countries="{{ json_encode($countries) }}"
                :_legal-forms="{{ json_encode($legalForms) }}"
            ></Organization>
        </div>
@endsection
