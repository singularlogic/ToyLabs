@extends('layouts.default', ['class' => ''])

@section('title')
    {{ __('Members') }}
@endsection

@section('content')
        <!-- Profile/Organizations -->
        <div class="ui main container">
            <h1 class="ui dividing header">{{ __('Members') }}</h1>

            <div class="ui very relaxed list">
                <div class="ui two column grid">
@foreach($organizations as $organization)
                    <div class="item column">
@if($organization->country)
                        <i class="{{ strtolower($organization->country->sortcode) }} flag"></i>
@else
                        <i class="eu flag"></i>
@endif
                        <div class="content">
                            <div class="header">
@if ($organization->is_verified)
                                <i class="green check circle icon"></i>
@endif
                                <a href="/organization/{{ $organization->id }}">{{ $organization->name }}</a>
                                ({{ $organization->organizationType->name }})
                            </div>
                            <div class="description" style="padding-top: 4px;">
                                <em>Member since:</em>
                                <strong>{{ date('F, Y', strtotime($organization->created_at)) }}</strong>
                            </div>
                        </div>
                    </div>
@endforeach
                </div>
            </div>
        </div>
@endsection
