        <!-- Hidden Menu -->
        <div class="ui large top fixed {{ $class or '' }} menu">
            <div class="ui container">
                <a class="item{{ Request::is('/') ? ' active' : '' }}" href="/">Home</a>
                <a class="item{{ Request::is('about') ? ' active' : '' }}" href="/about">About</a>
                <div class="right menu">
@if(Auth::check())
                    <div class="ui dropdown item">
                        <img class="ui avatar image" src="{{ Auth::user()->image }}" />
                        {{{ isset(Auth::user()->name) ? Auth::user()->name : Auth::user()->email }}}
                        <i class="dropdown icon"></i>
                        <div class="menu">
                            <a class="item" href="/profile/edit"><i class="user icon"></i> Edit Profile</a>
                            <a class="item" href="#"><i class="users icon"></i> Edit Organization</a>
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
