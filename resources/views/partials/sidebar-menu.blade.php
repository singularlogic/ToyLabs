        <!-- Sidebar Menu -->
        <div class="ui vertical inverted sidebar menu">
            <a class="item" href="/">Home</a>
            <a class="item" href="/about">About</a>
@if(Auth::check())
@else
            <a class="item" href="/login">Login</a>
            <a class="item" href="/register">Register</a>
@endif
        </div>
