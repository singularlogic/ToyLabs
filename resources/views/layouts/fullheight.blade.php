<!doctype html>
<html lang="{{ config('app.locale') }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta id="token" name="token" value="{{ csrf_token() }}">
        <title>{{ config('app.name') }} &bull; @yield('title')</title>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.2.10/semantic.min.css" />
        <link rel="stylesheet" href="{{ mix('/css/app.css') }}">
    </head>
    <body>
        <div class="ui middle aligned center aligned grid" id="app">
            <div class="column">
                <div class="row">
                    <h1 class="ui orange image header">
                        <a href="/" title="Home"><img src="/images/toylabs_logo_big_flat.svg" class="image" /></a>
                    </h1>
                </div>

                @yield('content')
            </div>
        </div>
    </body>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.2.10/semantic.min.js"></script>
    <script src="{{ mix('/js/app.js') }}"></script>
</html>
