@extends('layouts.fullheight')

@section('title', 'New Password')

@section('content')
                <div class="row">
                    <div class="ui raised very padded container segment">
@if ($errors->all())
                        <div class="ui error message" style="text-align: left; margin-bottom: 30px;">
                            <div class="header">Error Resetting Password</div>
                            <ul class="list">
@foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
@endforeach
                            </ul>
                        </div>
@else
                        @if (session('status'))
                            <div class="ui info alert message" style="text-align: left; margin-bottom: 30px;">
                                <p>{{ session('status') }}</p>
                            </div>
                        @else
                        <p>Please enter the email address of your account, and create a new password</p>
                        @endif
@endif
                        <form class="ui form" method="POST" action="{{ route('password.request') }}">
                            {{ csrf_field() }}
                            <input type="hidden" name="token" value="{{ $token }}" />
                            <div class="field{{ $errors->has('email') ? ' error' : '' }}">
                                <div class="ui left icon input">
                                    <input type="email" name="email" placeholder="Email" value="{{ old('email') }}" required autofocus />
                                    <i class="mail icon"></i>
                                </div>
                            </div>
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

                            <button class="ui orange submit button" type="submit">Reset Password</button>
                        </form>

                    </div>
                </div>
@endsection
