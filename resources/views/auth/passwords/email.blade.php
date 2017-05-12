@extends('layouts.fullheight')

@section('title', 'Password Reset')

@section('content')
                <div class="row">
                    <div class="ui raised very padded container segment">
@if ($errors->all())
                        <div class="ui error message" style="text-align: left; margin-bottom: 30px;">
                            <div class="header">Error Sending Password Reset Link</div>
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
                        <p>Please enter the email address of your account, to receive the password reset link</p>
                        @endif
@endif
                        <form class="ui form" method="POST" action="{{ route('password.email') }}">
                            {{ csrf_field() }}
                            <div class="field{{ $errors->has('email') ? ' error' : '' }}">
                                <div class="ui left icon action input">
                                    <input type="email" name="email" placeholder="Email" value="{{ old('email') }}" required autofocus />
                                    <i class="mail icon"></i>
                                    <button class="ui orange submit button" type="submit">Send Reset Link</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
@endsection
