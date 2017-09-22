@extends('layouts.default', ['class' => ''])

@section('title')
    {{ $pagetitle or 'EditOrganization' }}
@endsection

@section('content')
        <!-- Organization -->
        <div class="ui main container">
            <h1 class="ui header">{{ $pagetitle or 'Edit Organization Profile' }}</h1>
            <div class="ui divider"></div>

            @include('partials.status')

            <Organization
                :_countries="{{ json_encode($countries) }}"
                :_legal-forms="{{ json_encode($legalForms) }}"
                :_id="{{ $id }}"
                :_organization="{{ json_encode($organization) }}"
                :_facilities="{{ json_encode($facilities) }}"
                :_competencies="{{ json_encode($competencies) }}"
                :_markets="{{ json_encode($markets) }}"
                :_categories="{{ json_encode($categories) }}"
                :_services="{{ json_encode($services) }}"
            ></Organization>
        </div>
@endsection
