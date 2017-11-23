        <!-- Hidden Menu -->
        <div class="ui large top fixed {{ $class or '' }} menu">
            <div class="ui container">
                <a class="ui item image logo" href="{{ route('home') }}">
                    <img src="/images/toylabs_header_logo.svg" />
                </a>
@if(Auth::check())
                <a class="item{{ Request::is('dashboard') ? ' active' : '' }}" href="{{ route('dashboard') }}">Products</a>
@endif
                <a class="item{{ Request::is('about') ? ' active' : '' }}" href="{{ route('about') }}">About</a>
                <div class="right menu">
@if(Auth::check())
                    <notification-area></notification-area>
                    <div class="ui dropdown item">
                        <img class="ui avatar image" src="{{ Auth::user()->image }}" />
                        {{{ isset(Auth::user()->name) ? Auth::user()->name : Auth::user()->email }}}
                        <i class="dropdown icon"></i>
                        <div class="menu">
                            <a class="item" href="/profile/edit"><i class="pencil icon"></i> Edit Profile</a>
                            <div class="divider"></div>
                            <div class="header">Organizations</div>
@foreach(Auth::user()->organizations as $organization)
                            <div class="ui dropdown item">
                                {{ $organization->name }} <i class="dropdown icon"></i>
                                <div class="menu">
                                    <a class="item" href="/organization/{{ $organization->id }}"> View</a>
@if($organization->owner_id == Auth::user()->id)
                                    <a class="item" href="/organization/{{ $organization->id }}/edit"> Edit...</a>
@endif
                                </div>
                            </div>
@endforeach
                            <div class="divider"></div>
                            <a class="item" href="/logout"><i class="power icon"></i> Logout</a>
                        </div>
                    </div>
@else
                    <div class="item">
                        <a class="ui secondary button" href="/login">Login</a>
                    </div>
                    <div class="item">
                        <a class="ui orange button" href="/register">Register</a>
                    </div>
@endif
                </div>
            </div>
        </div>
