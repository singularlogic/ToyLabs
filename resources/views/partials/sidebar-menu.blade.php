        <!-- Sidebar Menu -->
        <div class="ui vertical inverted sidebar menu">
            <a class="item{{ Request::is('/') ? ' active' : '' }}" href="/">Home</a>
            <a class="item{{ Request::is('about') ? ' active' : '' }}" href="/about">About</a>
@if(Auth::check())
@else
            <a class="item" href="/login">Login</a>
            <a class="item" href="/register">Register</a>
@endif
        </div>
