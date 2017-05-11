                    <div class="ui large secondary inverted pointing menu">
                        <a class="toc item">
                            <i class="sidebar icon"></i>
                        </a>
                        <a class="item{{ Request::is('/') ? ' active' : '' }}" href="/">Home</a>
                        <a class="item{{ Request::is('about') ? ' active' : '' }}" href="/about">About</a>
@if(Auth::check())
                        <div class="right menu">
                            <div class="ui dropdown">
                                <img class="ui avatar image" src="{{ Auth::user()->avatar }}" />
                                {{{ isset(Auth::user()->name) ? Auth::user()->name : Auth::user()->email }}}
                                <i class="dropdown icon"></i>
                                <div class="menu">
                                    <a class="item" href="#"><i class="user icon"></i> Edit Profile</a>
                                    <a class="item" href="#"><i class="users icon"></i> Edit Organization</a>
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
