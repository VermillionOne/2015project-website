@extends('layouts.master')

@section('content')
<div class="signup-body">

	{{-- Session Message --}}
  @include('includes.sessionStatus')

  <div class = "container login-container">
    <div class = "row">

      <div class = "forgot-sect-txt col-xs-12">

        <div class="reset-welcome-title">RESET PASSWORD FOR <BR> {{ $email }}</div>

					{!! ViewHelper::formOpen(['route' => ['password.updateReset', 'token' => $token], 'role' => 'form', 'class' => 'form-horizontal', 'method' => 'POST']) !!}

						<div class="form-group">
							<label class="col-md-4 control-label">Password</label>
							<div class="col-md-6">
								<input type="password" class="form-control password-align" name="password" required>
							</div>
						</div>

						<div class="form-group">
							<label class="col-md-4 control-label">Confirm Password</label>
							<div class="col-md-6">
								<input type="password" class="form-control password-align" name="password_confirmation" required>
							</div>
						</div>

						<div class="form-group">
						  <div class="col-sm-10">
						  	<input type="hidden" name="from" value="web" required>
								<button type="submit" class="btn btn-primary password-reset-btn">
									Reset Password
								</button>
							</div>
						</div>

					{!! Form::close() !!}

				</div>
			</div>
		</div>
	</div>
</div>
@endsection
