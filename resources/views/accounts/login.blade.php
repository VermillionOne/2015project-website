@extends('layouts.master')

@section('loginTitle')
Login
@stop

@section('content')
<div class="signup-body">

  {{-- Session Message --}}
  @include('includes.sessionStatus')

  <div class = "container login-container">
    <div class = "row">
      <div class = "signgup-sect-txt col-md-8 col-sm-6 col-xs-12">
        <span class = "signup-welcome-title">WELCOME TO SUARAY</span><br/>
        Sell Custom Tickets = Monetize: Social Media Followings<br/>
        Easily Manage Views / Sales / Content<br/>
        Actively Engage your Audience<br/>
        Featured / Sponsored Event Page Badges<br/>
        Polls, 360 Video, etc.<br/>
        Targeted Audience Advertising for Events
      </div>
      <div class = "col-md-4 col-sm-6 col-xs-12">
        <div class = "sign-up-login-feeds">
          <div class = "sign-up-header-txt">SIGN IN TO YOUR SUARAY.COM ACCOUNT</div>

            {!! ViewHelper::formOpen(['url' => 'account/login']) !!}

              {!! $errors->first('email', '<div class="text-danger"><br/>:message</div>') !!}
              <div class = "signu-up-user-field">
                <input type="email" name="email" value="{{ Input::old('email') }}" class="form-control" placeholder="Email address" maxlength="100" required autofocus>
              </div>
              {!! $errors->first('password', '<div class="text-danger"><br/>:message</div>') !!}
              <div class = "signu-up-user-field">
                <input type="password" name="password" maxlength="50" class="form-control" placeholder="Password" required>
                {!! Form::token() !!}
              </div>
              <div class = "signu-up-user-field">
                <button class="btn btn-primary btn-lg btn-block" type="submit">
                  Sign in <small class="glyphicon glyphicon-log-in"></small>
                </button>
              </div>
            </div>

            {{-- If the user came from an area he was asked to login --}}
            @if(Session::has('warning_message'))
              {!! Form::hidden('slug', Session::get('previous_page')) !!}
            @endif

            {!! Form::close() !!}
            <div class = "sign-up-social-login">
              <div class = "signu-up-user-field">
                <a href="{{ url(route('register')) }}">
                  <div class="btn btn-success btn-lg btn-block">Create Account</div>
                </a>
              </div>
              <div class = "login-social-txt">OR SIGN UP WITH YOUR SOCIAL ACCOUNT</div>
              <div class = "social-btns-sect">
                <div class="sign-up-social social-btns-lineup mb10">
                  <a href="{{ url('account/login/facebook') }}" class="btn-lg btn-block btn-social-icon btn-facebook">
                    <i class="fa fa-facebook"></i>
                  </a>
                </div>
                <div class="sign-up-social social-btns-lineup b10">
                  <a href="{{ url('account/login/twitter') }}" class="btn-lg btn-block btn-social-icon btn-twitter">
                    <i class="fa fa-twitter"></i>
                  </a>
                </div>
                <div class="sign-up-social social-btns-lineup mb10">
                  <a href="{{ url('account/login/google') }}" class="btn-lg btn-block btn-social-icon btn-google-plus">
                    <i class="fa fa-google-plus"></i>
                  </a>
                </div>
                <div class="sign-up-social social-btns-lineup mb10">
                  <a href="{{ url('account/login/instagram') }}" class="btn-lg btn-block btn-social-icon btn-instagram">
                    <i class="fa fa-instagram"></i>
                  </a>
                </div>

              </div>
            </div>
            <div class="forgot-password-btn">
              <a class="btn btn-default" href="{{ route('password.forgot') }}">Forgot Password <i class="fa fa-question-circle"></i> </a>
            </div>
        </div>
      </div>
    </div>
  </div>
</div>
@stop
