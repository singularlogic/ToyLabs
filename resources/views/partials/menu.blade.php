                    <div class="ui large secondary inverted pointing menu">
                        <a class="toc item">
                            <i class="sidebar icon"></i>
                        </a>
                        <a class="item{{ Request::is('/') ? ' active' : '' }}" href="{{ route('home') }}">Home</a>
@if(Auth::check())
                        <a class="item{{ Request::is('dashboard') ? ' active' : '' }}" href="{{ route('dashboard') }}">Dashboard</a>
@endif
                        <a class="item{{ Request::is('organizations') ? 'active' : '' }}" href="{{ route('organizations') }}">Members</a>
                        <a class="item{{ Request::is('about') ? ' active' : '' }}" href="{{ route('about') }}">About</a>
@if(Auth::check())
                        <div class="right menu">
                            <div class="ui dropdown">
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
                        </div>
@else
                        <div class="right item">
                            <a class="ui inverted button" href="/login">Login</a>
                            <a class="ui inverted orange button" href="/register">Register</a>
                        </div>
@endif
                    </div>
