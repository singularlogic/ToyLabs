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
        <script>
            window.Laravel = {!! json_encode([
                'user'           => Auth::user(),
                'csrfToken'      => csrf_token(),
                'vapidPublicKey' => config('webpush.vapid.public_key'),
                'pusher'         => [
                    'key'     => config('broadcasting.connections.pusher.key'),
                    'cluster' => config('broadcasting.connections.pusher.options.cluster'),
                ]
            ]) !!};
        </script>
        <link rel="stylesheet" type="text/css" href="//cdnjs.cloudflare.com/ajax/libs/cookieconsent2/3.1.0/cookieconsent.min.css" />
        <script src="//cdnjs.cloudflare.com/ajax/libs/cookieconsent2/3.1.0/cookieconsent.min.js"></script>
        <script>
            window.addEventListener("load", function(){
            window.cookieconsent.initialise({
                "palette": {
                    "popup": {
                        "background": "#000000"
                    },
                    "button": {
                        "background": "#e37737",
                        "text": "#ffffff"
                    }
                },
                "theme": "classic",
                "position": "top",
                "content": {
                    "message": "We use cookies to ensure that we give you the best experience on our website. If you continue to use this site we will assume that you are happy with it.",
                    "link": ""
                }
            })});
        </script>
    </head>
    <body>
        <div id="app" v-cloak>
            @include('partials.hidden-menu', ['class' => $class])

            @include('partials.sidebar-menu')

            <div class="pusher">
                @yield('content')
            </div>

            <div class="ui hidden clearing divider"></div>

            <div class="ui inverted vertical footer segment">
                <div class="ui container">
                    <div class="ui stackable inverted divided equal height stackable grid">
                        <div class="eight wide middle aligned column">
                            <span>Copyright &copy; 2017-2018 &nbsp; <a href="http://toylabs.eu/node/62" target="_BLANK">ToyLabs Consortium</a></span>
                        </div>
                        <div class="eight wide middle aligned column">
                            <img class="ui left floated tiny image" src="/images/eu.png" />
                            <span>The ToyLabs project has received funding from the European Union’s Horizon 2020 research and innovation programme under grant agreement No 732559.</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.2.10/semantic.min.js"></script>
    <script src="{{ mix('/js/app.js') }}"></script>
</html>
