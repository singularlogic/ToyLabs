@extends('layouts.default', ['class' => ''])

@section('title', 'Edit Profile')

@section('content')
        <div class="ui main container" id="app">
            <!-- Profile / General -->
            <h1 class="ui header">Edit Profile</h1>
            <div class="ui divider"></div>

            <profile
                :_countries="{{ json_encode($countries) }}"
                :_organizations="{{ json_encode($organizations) }}"
                :_personal="{{ json_encode($personal) }}"
                :_professional="{{ json_encode($professional) }}"
                :_types="{{ json_encode($org_types) }}"
            ></profile>
        </div>
@endsection
