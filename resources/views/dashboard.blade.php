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
{{--         <div class="ui main container" id="dashboard">
            @include('partials.status')

            <div class="ui orange pointing secondary menu">
                <a class="active item" data-tab="myproducts">My Products</a>
                <a class="item" data-tab="mycollaborations">Active Collaborations</a>
                <a class="item" data-tab="archive">Archive</a>
                <div class="right menu">
@if($is_complete)
                    <div class="item" style="padding-right: 6px;">
                        <a class="ui labeled orange mini icon button" href="/product/create" id="newButton">
                            <i class="icon plus"></i>
                            New Product
                        </a>
                    </div>
@endif
                    <div class="ui category search item">
                        <div class="ui icon input">
                            <input type="text" placeholder="Search..." />
                            <i class="search link icon"></i>
                        </div>
                        <div class="results"></div>
                    </div>
                </div>
            </div>

            <div class="ui active tab basic segment" data-tab="myproducts">
                <product-list
                    :products="{{ json_encode($products) }}"
                ></product-list>
            </div>
            <div class="ui tab basic segment" data-tab="mycollaborations">
@if($items->count() > 0)
                <table class="ui sortable celled compact striped table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Product</th>
                            <th class="one wide center aligned">Version</th>
                            <th class="two wide center aligned">Last Modified</th>
                            <th class="one wide"></th>
                        </tr>
                    </thead>
                    <tbody>
@foreach($items as $item)
                        <tr>
                            <td>
                                @if($item->collaboratable->type == 'design')<i class="pencil icon"></i>@else<i class="cube icon"></i>@endif
                                <a href="/{{ $item->collaboratable->type }}/{{ $item->collaboratable->id }}" target="_BLANK">{{ $item->collaboratable->title }}</a>
                            </td>
                            <td>
                                <a href="/product/{{ $item->collaboratable->product->id }}" target="_BLANK">{{ $item->collaboratable->product->title }}</a>
                            </td>
                            <td class="center aligned">{{ $item->collaboratable->version }}</td>
                            <td class="center aligned">
                                {{ $item->collaboratable->updated_at->format('d.m.Y') }}
                            </td>
                            <td>
                                <div class="ui tiny basic icon buttons">
                                    <a class="ui button" href="/design/{{ $item->collaboratable->id }}/feedback" data-tooltip="Feedback" data-position="top center"data-inverted>
                                        <i class="comments outline icon"></i>
                                    </a>
                                    <a class="ui button" href="/design/{{ $item->collaboratable->id }}/negotiations" data-tooltip="Negotiations" data-position="top center"data-inverted>
                                        <i class="file text outline icon"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
@endforeach
                    </tbody>
                </table>
@else
                <div class="ui info message">
                    <p>No active collaborations found!</p>
                </div>
@endif
            </div>
            <div class="ui tab basic segment" data-tab="archive">
@if($archive->count() > 0)
                <table class="ui sortable celled compact striped table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Product</th>
                            <th class="one wide center aligned">Version</th>
                            <th class="two wide center aligned">Last Modified</th>
                            <th class="one wide"></th>
                        </tr>
                    </thead>
                    <tbody>
@foreach($archive as $item)
                        <tr>
                            <td>
                                @if($item->collaboratable->type == 'design')<i class="pencil icon"></i>@else<i class="cube icon"></i>@endif
                                <a href="/{{ $item->collaboratable->type }}/{{ $item->collaboratable->id }}" target="_BLANK">{{ $item->collaboratable->title }}</a>
                            </td>
                            <td>
                                <a href="/product/{{ $item->collaboratable->product->id }}" target="_BLANK">{{ $item->collaboratable->product->title }}</a>
                            </td>
                            <td class="center aligned">{{ $item->collaboratable->version }}</td>
                            <td class="center aligned">
                                {{ $item->collaboratable->updated_at->format('d.m.Y') }}
                            </td>
                            <td>
                                <div class="ui tiny basic icon buttons">
                                    <a class="ui button" href="/design/{{ $item->collaboratable->id }}/feedback" data-tooltip="Feedback" data-position="top center"data-inverted>
                                        <i class="comments outline icon"></i>
                                    </a>
                                    <a class="ui button" href="/design/{{ $item->collaboratable->id }}/negotiations" data-tooltip="Negotiations" data-position="top center"data-inverted>
                                        <i class="file text outline icon"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
@endforeach
                    </tbody>
                </table>
@else
                <div class="ui info message">
                    <p>Archive is empty!</p>
                </div>
@endif
            </div>
        </div>
 --}}@endsection
