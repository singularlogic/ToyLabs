@extends('layouts.fullheight')

@section('title', 'Login')

@section('content')
<!-- Login -->
                <div class="row">
                    <div class="ui raised very padded container segment">
@if ($errors->all())
                        <div class="ui error message" style="text-align: left; margin-bottom: 30px;">
                            <div class="header">Login Failed</div>
                            <ul class="list">
@foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
@endforeach
                            </ul>
                        </div>
@endif
                        <div class="ui two column top aligned relaxed stackable grid">
                            <div class="column" style="padding-top: 22px;">
                                <form class="ui form left aligned" method="POST" action="{{ route('login.post') }}">
                                    {{ csrf_field() }}
                                    <div class="field{{ $errors->has('email') ? ' error' : '' }}">
                                        <div class="ui left icon input">
                                            <input type="email" id="email" name="email" placeholder="Email" value="{{ old('email') }}" required autofocus />
                                            <i class="mail icon"></i>
                                        </div>
                                    </div>
                                    <div class="field{{ $errors->has('password') ? ' has-error' : '' }}">
                                        <div class="ui left icon input">
                                            <input type="password" id="password" name="password" placeholder="Password" required />
                                            <i class="lock icon"></i>
                                        </div>
                                    </div>

                                    <a class="ui left floated button link" href="/password/reset">Forgot password?</a>
                                    <button class="ui orange submit right floated button" type="submit">&nbsp;&nbsp;&nbsp;Login&nbsp;&nbsp;&nbsp;</button>
                                </form>
                            </div>
                            <div class="v-divider">
                                <div class="ui vertical divider">Or</div>
                            </div>
                            <div class="center aligned column" style="padding-top: 22px;">
                                <a class="ui fluid facebook button" href="/login/facebook"><i class="facebook icon"></i>Facebook</a>
                                <a class="ui fluid google plus button" href="/login/google"><i class="google plus icon"></i>Google</a>
                                <div class="center aligned link">
                                    New user? <a href="/register">Register now</a>!
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

@endsection
