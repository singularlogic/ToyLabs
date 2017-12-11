@extends('layouts.default', ['class' => ''])

@section('title', 'Dashboard')

@section('content')
        <!-- Dashboard -->
        @include('partials.status')

        <dashboard-page
            :is-profile-complete="{{ $is_complete }}"
            :products="{{ json_encode($products) }}"
            :active-collaborations="{{ json_encode($items) }}"
            :archived-collaborations="{{ json_encode($archive) }}"
        ></dashboard-page>
@endsection
