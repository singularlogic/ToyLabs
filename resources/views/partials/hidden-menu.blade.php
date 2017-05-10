        <!-- Hidden Menu -->
        <div class="ui large top fixed {{ $class or '' }} menu">
            <div class="ui container">
                <a class="item" href="/">Home</a>
                <a class="item" href="/about">About</a>
                <div class="right menu">
@if(Auth::check())
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
