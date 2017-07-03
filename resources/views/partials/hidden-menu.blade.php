        <!-- Hidden Menu -->
        <div class="ui large top fixed {{ $class or '' }} menu">
            <div class="ui container">
                <a class="ui item image logo" href="{{ route('home') }}">
                    <img src="/images/toylabs_header_logo.svg" />
                </a>
                {{-- <a class="item{{ Request::is('/') ? ' active' : '' }}" href="{{ route('home') }}">Home</a> --}}
@if(Auth::check())
                <a class="item{{ Request::is('dashboard') ? ' active' : '' }}" href="{{ route('dashboard') }}">Projects</a>
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
                            <a class="item" href="/profile/edit"><i class="user icon"></i> Edit Profile</a>
@if(Auth::user()->hasOrganization)
                            <a class="item" href="/organization/edit"><i class="users icon"></i> Edit Organization</a>
@endif
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
