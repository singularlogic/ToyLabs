@extends('layouts.fullheight')

@section('title', 'Login')

@section('content')
<!-- Login -->
                <div class="row">
                    <div class="ui two column top aligned relaxed stackable grid">
                        <div class="column" style="padding-top: 22px;">
                            <div class="ui form left aligned">
                                <div class="field">
                                    <div class="ui left icon input">
                                        <input type="text" placeholder="Email" />
                                        <i class="mail icon"></i>
                                    </div>
                                </div>
                                <div class="field">
                                    <div class="ui left icon input">
                                        <input type="password" placeholder="Password" />
                                        <i class="lock icon"></i>
                                    </div>
                                </div>
                                <div class="ui orange submit button">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Login&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div>
                            </div>
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

@endsection
