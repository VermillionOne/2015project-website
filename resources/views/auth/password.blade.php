@extends('layouts.master')

@section('content')

{{-- Session Message --}}
@include('includes.sessionStatus')

@if (count($errors) > 0)
	<div class="alert alert-danger">
		<strong>Whoops!</strong> There were some problems with your input.<br><br>
		<ul>
			@foreach ($errors->all() as $error)
				<li>{{ $error }}</li>
			@endforeach
		</ul>
	</div>
@endif
<div class="signup-body" ng-controller="FeedbackTabController">

  <div class = "container login-container">
    <div class = "row">

      <div class = "forgot-sect-txt col-xs-12">

        <div class="signup-welcome-title">RESET YOUR PASSWORD</div>
        <div class="forgot-pass-txt">Please enter your email address. We will send you an email for you to reset your password.</div>

					{!! ViewHelper::formOpen(['route' => 'password.doForgot', 'role' => 'form', 'name' => 'emailConf', 'class' => 'form-inline']) !!}

						{{-- <input type="hidden" name="_token" value="{{ csrf_token() }}"> --}}
  					<div class="form-group" ng-class="{ 'has-error' : emailConf.email.$invalid && !emailConf.email.$pristine }">

							<label class="control-label">E-Mail Address</label>

              <input type="email" class="form-control" required name="email" ng-model="user.email" placeholder="Enter Email Address" maxlength="100" required value="{{ old('email') }}" ng-pattern="userValidation.email">

							<button type="submit" ng-disabled="emailConf.$invalid" class="btn btn-primary">Send Password Reset Link</button>

						</div>

            {{-- Displays error messages for invalid email input --}}
            <p ng-show="submitted && emailConf.email.$error.required" class="help-valid">Required Field</p>
            <p ng-show="emailConf.email.$error.email" class="help-valid">Enter a valid email.</p>

					{!! Form::close() !!}

				</div>
			</div>

		</div>
	</div>

</div>
@endsection
