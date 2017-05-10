@extends('layouts.fullheight')

@section('title', 'Register')

@section('content')
<!-- Register -->
                <div class="row">
                    <form class="ui form">
                        <div class="field">
                            <div class="ui left icon input">
                                <input type="email" name="email" placeholder="Email" />
                                <i class="mail icon"></i>
                            </div>
                        </div>
                        <div class="two fields">
                            <div class="field">
                                <div class="ui left icon input">
                                    <input type="password" name="password" placeholder="Password" />
                                    <i class="lock icon"></i>
                                </div>
                            </div>
                            <div class="field">
                                <div class="ui left icon input">
                                    <input type="password" name="confirm_password" placeholder="Confirm Password" />
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
                        <div class="ui divider"></div>
                        <span style="float: left; padding-top: 10px;">Already have an account? Go to the <a href="/login">Login</a> page.</span>
                        <div class="ui orange submit right floated button">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Register&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div>
                    </form>
                </div>
@endsection
