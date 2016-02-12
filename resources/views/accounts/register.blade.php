@extends('layouts.master')

@section('loginTitle')
Register
@stop

@section('content')
<div class="signup-body">
  <div class="container">

    {{-- Api errors --}}
    @if ($errors->has('api'))

      <div class="col-md-9 col-md-offset-3">
        <ul class="text-danger pl10">

          {{-- For every error per input field --}}
          @foreach ($errors->get('api') as $error)
            <li><b>{!! $error !!}</b></l>
          @endforeach

        </ul>
        <br>
      </div>

    @endif

    <div class="row">
      <div class="col-xs-12 col-sm-6">
        <h1 class="accounts-register-title">WELCOME TO SUARAY</h1>

        <ul class="signgup-sect-txt list-unstyled" style="line-height: 2">
          <li><span class="fa fa-check text-success"></span> Post events </li>
          <li><span class="fa fa-check text-success"></span> Personalized experience across the site </li>
          <li><span class="fa fa-check text-success"></span> See the history of events you RSVP</li>
          <li><span class="fa fa-check text-success"></span> Save events to your favorites</li>
          <li><span class="fa fa-check text-success"></span> and more...</li>
        </ul>

      </div>

      <div class = "col-xs-12 col-sm-6" ng-controller="FeedbackTabController">

        <div class = "sign-up-login-feeds">

          <h2 class = "sign-up-header-txt">Create Your SUARAY Account</h2>
          {{-- Registration form --}}
          {!! ViewHelper::formOpen(['url' => 'account/register', 'class' => 'form-horizontal']) !!}

            {{-- time_zone_id - TODO: make this a dropdown --}}
            {!! Form::hidden('time_zone_id', 6) !!}

            @if (Cookie::get('suarayrequestfrom') !== null)
              {!! Form::hidden('suarayrequestfrom', Cookie::get('suarayrequestfrom')) !!}
            @endif

            {{-- first_name --}}
            <div class="form-group @if ($errors->has('first_name')) has-error @endif }}">
              {!! Form::label('first_name', 'First Name', ['class' => 'col-md-3 control-label']) !!}
              <div class="col-md-9">
                {!! Form::text('first_name', null, ['class' => 'form-control feedback-name', 'placeholder' => 'John']) !!}
                {!! $errors->first('first_name', '<p class="help-block">:message</p>') !!}
              </div>
            </div>

            {{-- last_name --}}
            <div class="form-group @if ($errors->has('last_name')) has-error @endif }}">
              {!! Form::label('last_name', 'Last Name', ['class' => 'col-md-3 control-label']) !!}
              <div class="col-md-9">
                {!! Form::text('last_name', null, ['class' => 'form-control feedback-name', 'placeholder' => 'Doe']) !!}
                {!! $errors->first('last_name', '<p class="help-block">:message</p>') !!}
              </div>
            </div>

            {{-- email --}}
            <div class="form-group @if ($errors->has('email')) has-error @endif }}">
              {!! Form::label('email', 'Email', ['class' => 'col-md-3 control-label']) !!}
              <div class="col-md-9">
                {!! Form::email('email', null, ['class' => 'form-control', 'maxlength' => '100', 'placeholder' => 'example@example.com']) !!}
                {!! $errors->first('email', '<p class="help-block">:message</p>') !!}
              </div>
            </div>

            {{-- password --}}
            <div class="form-group @if ($errors->has('password')) has-error @endif }}">
              {!! Form::label('password', 'Password', ['class' => 'col-md-3 control-label']) !!}
              <div class="col-md-9">
                {!! Form::password('password', ['class' => 'form-control', 'placeholder' => 'Choose a password...']) !!}
                {!! $errors->first('password', '<p class="help-block">:message</p>') !!}
              </div>
            </div>

            {{-- password_confirmation --}}
            <div class="form-group @if ($errors->has('password_confirmation')) has-error @endif }}">
              {!! Form::label('password_confirmation', 'Confirm', ['class' => 'col-md-3 control-label']) !!}
              <div class="col-md-9">
                {!! Form::password('password_confirmation', ['class' => 'form-control', 'placeholder' => 'Confirm password...']) !!}
                {!! $errors->first('password_confirmation', '<p class="help-block">:message</p>') !!}
              </div>
            </div>

            {{-- gender --}}
            <div class="form-group @if ($errors->has('gender')) has-error @endif }}">
              {!! Form::label('gender', 'Gender', ['class' => 'col-md-3 control-label']) !!}
              <div class="col-md-9">
                <div class="pull-left">
                  {!! Form::select('gender', ['male' => 'Male', 'female' => 'Female'], null, ['class' => 'form-control']) !!}
                  {!! $errors->first('gender', '<p class="help-block">:message</p>') !!}
                </div>
              </div>
            </div>

            {{-- dob --}}
            <div class="form-group @if ($errors->has('birth_day') or $errors->has('birth_month') or $errors->has('birth_year')) has-error @endif }}">
              {!! Form::label('birth_day', 'Birth Date', ['class' => 'col-md-3 control-label']) !!}
              <div class="col-md-9">
                <div class="pull-left mr5">
                  {!! Form::selectRange('birth_day', 1, 31, date('d'), ['class' => 'form-control']) !!}
                  {!! $errors->first('birth_day', '<p class="help-block">:message</p>') !!}
                </div>
                <div class="pull-left mr5">
                  {!! Form::selectMonth('birth_month', date('m'), ['class' => 'form-control']) !!}
                  {!! $errors->first('birth_month', '<p class="help-block">:message</p>') !!}
                </div>
                <div class="pull-left">
                  {!! Form::selectYear('birth_year', date('Y') - 100, date('Y') - 10, date('Y'), ['class' => 'form-control']) !!}
                  {!! $errors->first('birth_year', '<p class="help-block">:message</p>') !!}
                </div>
              </div>
            </div>

            {!! Form::token() !!}

            {{-- submit button --}}
            <div class="form-group">
              <div class="col-md-9 col-md-offset-3">
                <button class="btn btn-success btn-lg btn-block" type="submit">Sign Up</button>
              </div>
            </div>
          {!! Form::close() !!}

          <p class="register-sign-in-link">Already a Member? <a href="{{ url(route('login')) }}">Sign In</a></p>
        </div>

        <div class = "sign-up-social-login">
          <p class = "sign-up-social-txt col-md-4 col-sm-4 col-xs-12">OR SIGN UP WITH YOUR SOCIAL ACCOUNT</p>
          <div class="sign-up-social-btns col-xs-2 mb10">
            <a href="{{ url('account/login/facebook') }}" class="btn-lg btn-block btn-social-icon btn-facebook">
              <i class="fa fa-facebook"></i>
            </a>
          </div>
          <div class="sign-up-social-btns col-md-2 col-xs-2 b10">
            <a href="{{ url('account/login/twitter') }}" class="btn-lg btn-block btn-social-icon btn-twitter">
              <i class="fa fa-twitter"></i>
            </a>
          </div>
          <div class="sign-up-social-btns col-md-2 col-xs-2 mb10">
            <a href="{{ url('account/login/google') }}" class="btn-lg btn-block btn-social-icon btn-google-plus">
              <i class="fa fa-google-plus"></i>
            </a>
          </div>
          <div class="sign-up-social-btns col-md-2 col-xs-2 mb10">
            <a href="{{ url('account/login/instagram') }}" class="btn-lg btn-block btn-social-icon btn-instagram">
              <i class="fa fa-instagram"></i>
            </a>
          </div>

        </div>
      </div>
    </div>
  </div>
</div>
@stop
