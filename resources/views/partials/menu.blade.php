                    <div class="ui large secondary inverted pointing menu">
                        <a class="toc item">
                            <i class="sidebar icon"></i>
                        </a>
                        <a class="item" href="/">Home</a>
                        <a class="item" href="/about">About</a>
@if(Auth::check())

@else
                        <div class="right item">
                            <a class="ui inverted button" href="/login">Login</a>
                            <a class="ui inverted orange button" href="/register">Register</a>
                        </div>
@endif
                    </div>
