@extends('layouts.default', ['class' => ''])

@section('title')
    {{ $pagetitle or 'Organization Profile' }}
@endsection

@section('content')
        <!-- Organization -->
        <div class="ui main container" id="orgPage">
            @include('partials.status')
            <h1 class="ui header">{{ $pagetitle or 'Organization Profile' }}</h1>

@if($organization->publicProducts->count() + $organization->publicDesigns->count() + $organization->publicPrototypes->count() > 0)
            <div class="ui pointing secondary menu">
                <a class="item orange active" data-tab="about">About</a>
@if($organization->publicProducts->count() > 0)
                <a class="item orange" data-tab="products">Products</a>
@endif
@if($organization->publicDesigns->count() > 0)
                <a class="item orange" data-tab="designs">Designs</a>
@endif
@if($organization->publicPrototypes->count() > 0)
                <a class="item orange" data-tab="prototypes">Prototypes</a>
@endif
            </div>
@else
            <div class="ui divider"></div>
@endif

            <div class="ui tab active" data-tab="about">
                <div class="ui grid">
                    <div class="row">
                        <div class="five wide column">
                            <div class="ui secondary segment">
                                <h4 class="ui dividing header">Contact</h4>
                                <div class="ui list">
                                    <div class="item">
                                        <i class="marker icon"></i>
                                        <div class="content">
                                            @if($organization->address){{ $organization->address }},<br />@endif
                                            @if($organization->postal_code){{ $organization->postal_code }},@endif
                                            @if($organization->city){{ $organization->city }},@endif
                                            @if($organization->country){{ $organization->country->name }}@endif
                                        </div>
                                    </div>
@if($organization->phone)
                                    <div class="item">
                                        <i class="phone icon"></i>
                                        <div class="content">{{ $organization->phone }}</div>
                                    </div>
@endif
@if($organization->fax)
                                    <div class="item">
                                        <i class="fax icon"></i>
                                        <div class="content">{{ $organization->fax }}</div>
                                    </div>
@endif
@if($organization->website_url)
                                    <div class="item">
                                        <i class="linkify icon"></i>
                                        <a class="content" href="{{ $organization->website_url }}" target="_BLANK">Website</a>
                                    </div>
@endif
@if($organization->facebook)
                                    <div class="item">
                                        <i class="facebook icon"></i>
                                        <a class="content" href="{{ $organization->facebook }}" target="_BLANK">Facebook Page</a>
                                    </div>
@endif
@if($organization->twitter)
                                    <div class="item">
                                        <i class="twitter icon"></i>
                                        <a class="content" href="https://www.twitter.com/{{ $organization->twitter }}" target="_BLANK">
                                            {{ '@' . $organization->twitter }}
                                        </a>
                                    </div>
@endif
@if($organization->instagram)
                                    <div class="item">
                                        <i class="instagram icon"></i>
                                        <a class="content" href="https://www.instagram.com/{{ $organization->instagram }}" target="_BLANK">
                                            {{ '@' . $organization->instagram }}
                                        </a>
                                    </div>
@endif
                                </div>
                            </div>
                        </div>
                        <div class="eleven wide column">
                            {!! nl2br(e($organization->description)) !!}

                            <h3 class="ui dividing header">Competencies</h3>

                            <div class="ui two column stackable grid basic segment">
@foreach($competencies as $competency)
                                    <div class="column" style="padding: 5px 0">
                                        @if($organization->competencies->contains($competency))
                                        <i class="green checkmark icon"></i> <strong>{{ $competency->name }}</strong>
                                        @else
                                        <i class="remove icon" style="color: #999999"></i>
                                        <span style="color: #999999">{{ $competency->name }}</span>
                                        @endif
                                    </div>
@endforeach
                            </div>

                            <h3 class="ui dividing header">Certifications</h3>
                            <div class="ui list">
@foreach($organization->certifications as $certification)
                                <div class="item">
                                    <i class="certificate icon"></i>
                                    <div class="content">{{ $certification->name }}</div>
                                </div>
@endforeach
                            </div>

                            <h3 class="ui dividing header">Awards</h3>
                            <div class="ui list">
@foreach($organization->awards as $award)
                                <div class="item">
                                    <i class="trophy icon"></i>
                                    <div class="content">
                                        <strong>{{ date('d.m.Y', strtotime($award->pivot->awarded_at)) }}</strong> &mdash;
                                        <a href="{{ $award->url }}" target="_BLANK">{{ $award->name }}</a>
                                    </div>
                                </div>
@endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="ui tab" data-tab="products">
                <products-grid
                    size="three"
                    :detailed="false"
                    :target="'_BLANK'"
                    :products="{{ json_encode($organization->publicProducts) }}"
                ></products-grid>
            </div>

            <div class="ui tab" data-tab="designs">
                <products-grid
                    size="three"
                    :detailed="false"
                    :target="'_BLANK'"
                    :products="{{ json_encode($organization->publicDesigns) }}"
                ></products-grid>
            </div>

            <div class="ui tab" data-tab="prototypes">
                <products-grid
                    size="three"
                    :detailed="false"
                    :target="'_BLANK'"
                    :products="{{ json_encode($organization->publicPrototypes) }}"
                ></products-grid>
            </div>

        </div>
@endsection
