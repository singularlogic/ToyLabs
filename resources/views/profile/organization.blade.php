@extends('layouts.default', ['class' => ''])

@section('title')
    {{ $pagetitle or 'Edit Organization' }}
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
                :_payment-types="{{ json_encode($paymentTypes) }}"
                :_services="{{ json_encode($services) }}"
                :_awardTypes="{{ json_encode($awardTypes) }}"
                :_certificationTypes="{{ json_encode($certificationTypes) }}"
            ></Organization>
        </div>
@endsection
