                    <div class="ui large secondary inverted pointing menu">
                        <a class="toc item">
                            <i class="sidebar icon"></i>
                        </a>
                        <a class="item{{ Request::is('/') ? ' active' : '' }}" href="{{ route('home') }}">Home</a>
@if(Auth::check())
                        <a class="item{{ Request::is('dashboard') ? ' active' : '' }}" href="{{ route('dashboard') }}">Projects</a>
@endif
                        <a class="item{{ Request::is('about') ? ' active' : '' }}" href="{{ route('about') }}">About</a>
@if(Auth::check())
                        <div class="right menu">
                            <div class="ui dropdown">
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
                        </div>
@else
                        <div class="right item">
                            <a class="ui inverted button" href="/login">Login</a>
                            <a class="ui inverted orange button" href="/register">Register</a>
                        </div>
@endif
                    </div>
