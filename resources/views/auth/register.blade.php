@extends('layouts.fullheight')

@section('title', 'Register')

@section('content')
<!-- Register -->
                <div class="row">
                    <div class="ui raised very padded container segment">
@if ($errors->all())
                        <div class="ui error message" style="text-align: left; margin-bottom: 30px;">
                            <div class="header">Registration Failed</div>
                            <ul class="list">
@foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
@endforeach
                            </ul>
                        </div>
@endif
                        <form class="ui form" method="POST" action="{{ route('register.post') }}">
                            {{ csrf_field() }}
                            <div class="field{{ $errors->has('name') ? ' error' : '' }}">
                                <div class="ui left icon input">
                                    <input type="text" name="name" placeholder="Name" value="{{ old('name') }}" required autofocus />
                                    <i class="user icon"></i>
                                </div>
                            </div>
                            <div class="field{{ $errors->has('email') ? ' error' : '' }}">
                                <div class="ui left icon input">
                                    <input type="email" name="email" placeholder="Email" value="{{ old('email') }}" required />
                                    <i class="mail icon"></i>
                                </div>
                            </div>
                            <div class="two fields">
                                <div class="field{{ $errors->has('password') ? ' error' : '' }}">
                                    <div class="ui left icon input">
                                        <input type="password" name="password" placeholder="Password" required />
                                        <i class="lock icon"></i>
                                    </div>
                                </div>
                                <div class="field">
                                    <div class="ui left icon input">
                                        <input type="password" name="password_confirmation" placeholder="Confirm Password" required />
                                        <i class="lock icon"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="field" style="text-align: right;">
                                <div class="ui checkbox">
                                    <input type="checkbox" />
                                    <label>I agree to the Terms and Conditions</label>
                                </div>
                            </div>
                            <div class="ui divider clearing"></div>
                            <span style="float: left; padding-top: 10px;" class="link">Already have an account? Go to the <a href="/login">Login</a> page.</span>
                            <button class="ui orange submit right floated button" type="submit">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Register&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</button>
                        </form>
                        <div style="clear: both;"></div>
                    </div>
                </div>
@endsection
