@extends('layouts.master')

@section('javascript-includes')
  <script src="{{ ViewHelper::asset('assets/dist/lib-payment.js') }}"></script>
@stop

@section('content')

{{-- Session Message --}}
@include('includes.sessionStatus')

<div class="payment-body" ng-controller="PaymentController">

  <div class="container">

    <div class="row">

      <div class="col-sm-12">

        <p class="payment-header">Payment Account</p>

      </div>

      {{-- <div class="col-sm-3">

        <div class="payment-container">
          <div class="payment-title">Transfer Options</div>

          <div class="payment-sidebar">

            <!-- List of accounts available for the user -->
            <ul class="nav nav-pills nav-stacked" id="myTab">

              <li role="presentation">
                <a ng-click="showDirectDeposit()"><i class="ion-cash"></i> direct deposit</a>
              </li>

            </ul>

          </div>
        </div>

        <div class="payment-container">

          @if (isset($warning_message) && ! empty($warning_message))

            <!-- Show missing required title -->
            <div class="payment-title">Required Missing Fields</div>

          @else

            <!-- Show Accounts Title -->
            @if ($account['bank_accounts']['total_count'] == 0)
              <div class="payment-title">Accounts ( 0 )</div>
            @else
              <div class="payment-title">Accounts ( {{ $account['bank_accounts']['total_count'] }} )</div>
            @endif

          @endif

          <div class="payment-sidebar">
            <ul class="nav nav-pills nav-stacked">

              @if (isset($warning_message) && ! empty($warning_message))

                <!-- Show missing required fields -->
                @foreach ($warning_message as $required)
                  <p class="alert-warning">{{ ucwords(str_replace("tos acceptance","", str_replace("date","", str_replace("ip","", str_replace("."," ", str_replace("_"," ", str_replace("legal_entity.","", $required))))))) }}</p>
                @endforeach

              @else

                @if($account['bank_accounts']['total_count'] != 0)

                  @foreach($account['bank_accounts']['data'] as $bankAccount)

                    <!-- Show Accounts list -->
                    <li role="presentation active">
                      <a ng-click="showDirectDeposit()"><i class="ion-cash"></i> <small>{{ $bankAccount['bank_name'] }}<br>ending in ...{{ $bankAccount['last4'] }}</small></a>
                    </li>

                  @endforeach

                @endif

              @endif

            </ul>
          </div>
        </div>
      </div> --}}

      {{-- <div class="tab-content"> --}}

        <div class="col-sm-12">

          <div class="payment-container">

            <div class="payment-fields">

              @if(isset($account['transfers_enabled']) && $account['transfers_enabled'] == true)

                <div ng-show="paymentAccountView === 'direct-deposit-summary'">

                  <div class="form-group col-xs-12 col-sm-12 alert-success form-success-notification show-then-slide-up">
                    <h5>Your account is now able to receive transfers</h5>
                  </div>

                  <div class="form-group col-xs-10 col-sm-10 col-sm-offset-1">
                    <h3 align="center">Your account with email {{$account['email']}} is now connected</h3>
                  </div>

                  @if (isset($account['legal_entity']))

                    <div class="form-group col-xs-10 col-sm-10 col-sm-offset-1 ">
                      <h3 >Personal Information</h3>
                      <button class="pull-right btn btn-sm btn-suaray btn-suaray-discreet edit-payment-account-button" ng-click="showDirectDeposit()">Edit Account Information</button>
                    </div>

                    {{-- Name Legal Entity Array --}}
                    <div class="form-group col-xs-12 col-sm-5 col-sm-offset-1 ">
                      {!! Form::label('legal_entity[first_name]', 'First Name', ['class' => 'clearfix control-label']) !!}
                      <p class="col-cs-12 col-sm-12 col-sm-offset-1 payment-account-summary-element" >
                        {{ $account['legal_entity']['first_name'] }}
                      </p>
                    </div>

                    <div class="form-group col-xs-12 col-sm-5 ">
                      {!! Form::label('legal_entity[last_name]', 'Last Name', ['class' => 'clearfix control-label']) !!}
                      <p class="col-cs-12 col-sm-12 col-sm-offset-1 payment-account-summary-element" >
                        {{ $account['legal_entity']['last_name'] }}
                      </p>
                    </div>

                    {{-- Birthday Legal Entity Array --}}
                    <div class="form-group col-xs-12 col-sm-5 col-sm-offset-1">
                      {!! Form::label('legal_entity[dob][month]', 'Date of Birth', ['class' => 'clearfix control-label']) !!}
                      <p class="col-cs-12 col-sm-12 col-sm-offset-1 payment-account-summary-element" >
                        {{$account['legal_entity']['dob']['month']}}/{{$account['legal_entity']['dob']['day']}}/{{$account['legal_entity']['dob']['year']}}
                      </p>
                    </div>

                    <div class="form-group col-xs-12 col-sm-5 ">
                      {{-- Disable the form if email is already on record, we dont want to update it thus yet --}}
                      {!! Form::label('email', 'Email', ['class' => 'clearfix control-label']) !!}
                      <p class="col-cs-12 col-sm-12 col-sm-offset-1 payment-account-summary-element" >
                        {{$account['email']}}
                      </p>
                    </div>

                    <div class="form-group col-xs-12 col-sm-10 col-sm-offset-1">
                      <h3>Address</h3>
                    </div>

                    <div class="form-group col-xs-12 col-sm-10 col-sm-offset-1">
                      <p class="col-cs-12 col-sm-12 col-sm-offset-1 payment-account-summary-element" >
                        {{$account['legal_entity']['address']['line1']}}
                      </p>
                    </div>

                    <div class="form-group col-xs-12 col-sm-3 col-sm-offset-1">
                      <p class="col-cs-12 col-sm-12 col-sm-offset-1 payment-account-summary-element" >
                        {{$account['legal_entity']['address']['city']}}
                      </p>
                    </div>

                    <div class="form-group col-xs-12 col-sm-4">
                      <p class="col-cs-12 col-sm-12 col-sm-offset-1 payment-account-summary-element" >
                        {{$account['legal_entity']['address']['state']}}
                      </p>
                    </div>

                    <div class="form-group col-xs-12 col-sm-3">
                      <p class="col-cs-12 col-sm-12 col-sm-offset-1 payment-account-summary-element" >
                        {{$account['legal_entity']['address']['postal_code']}}
                      </p>
                    </div>

                    <div class="form-group col-xs-12 col-sm-10 col-sm-offset-1">
                      <h3>Account Information</h3>
                    </div>

                    {{-- Bank Account Array to create BA token --}}
                    <div class="form-group col-xs-12 col-sm-5 col-sm-offset-1">
                      {!! Form::label('bankName', 'Name of Bank', ['class' => 'clearfix control-label']) !!}
                      <p class="col-cs-12 col-sm-12 col-sm-offset-1 payment-account-summary-element" >
                        {{$account['bank_accounts']['data'][0]['bank_name']}}
                      </p>
                    </div>

                    <div class="form-group col-xs-12 col-sm-5">
                      {!! Form::label('bank_account[account_number]', 'Account Number Last Four', ['class' => 'clearfix control-label']) !!}
                      <p class="col-cs-12 col-sm-12 col-sm-offset-1 payment-account-summary-element" >
                        {{$account['bank_accounts']['data'][0]['last4']}}
                      </p>
                    </div>

                    <div class="form-group col-xs-12 col-sm-5 col-sm-offset-1">
                      {!! Form::label('legal_entity[type]', 'Account Type', ['class' => 'clearfix control-label']) !!}
                      <p class="col-cs-12 col-sm-12 col-sm-offset-1 payment-account-summary-element" >
                        {{$account['legal_entity']['type']}}
                      </p>
                    </div>

                    <div class="form-group col-xs-12 col-sm-5 ">
                      {!! Form::label('bank_account[country]', 'Country (2 Letters)', ['class' => 'clearfix control-label']) !!}
                      <p class="col-cs-12 col-sm-12 col-sm-offset-1 payment-account-summary-element" >
                        {{$account['country']}}
                      </p>
                    </div>
                  @endif
                </div>

                <div ng-show="paymentAccountView === 'direct-deposit'">
                  {!! ViewHelper::formOpen(['route' => ['payment.update', isset($accountId) ? $accountId : 'no-account'], 'name' => 'paymentForm']) !!}
                    <div class="form-group col-xs-10 col-sm-10 col-sm-offset-1 ">
                      <h3>Account Holder</h3>
                    </div>

                    {{-- Name Legal Entity Array --}}
                    <div class="form-group col-xs-12 col-sm-5 col-sm-offset-1 ">
                      {!! Form::label('legal_entity[first_name]', 'First Name', ['class' => 'clearfix control-label']) !!} <span class="text-danger">&#42;</span>
                      {!! Form::text('legal_entity[first_name]', Input::old('legal_entity[first_name'),  ['class' => 'form-control payment-name', 'placeholder' => 'First Name...', 'maxlength' => '30', 'required', 'data-role' => 'paymentAccount-firstName', 'tabindex' => "1"]) !!}
                    </div>

                    <div class="form-group col-xs-12 col-sm-5 ">
                      {!! Form::label('legal_entity[last_name]', 'Last Name', ['class' => 'clearfix control-label']) !!} <span class="text-danger">&#42;</span>
                      {!! Form::text('legal_entity[last_name]', Input::old('legal_entity[last_name]'),  ['class' => 'form-control payment-name', 'placeholder' => 'Last Name...', 'maxlength' => '50', 'required', 'data-role' => 'paymentAccount-lastName', 'tabindex' => "2"]) !!}
                    </div>

                    {{-- Birthday Legal Entity Array --}}
                    <div class="form-group col-xs-12 col-sm-5 col-sm-offset-1">
                      <p class="payment-dob-label">{!! Form::label('legal_entity[dob][month]', 'Date of Birth', ['class' => 'clearfix control-label']) !!} <span class="text-danger">&#42;</span></p>
                      {!! Form::text('legal_entity[dob][month]', Input::old('legal_entity[dob][month]'),  ['class' => 'form-control payment-mob num-only', 'placeholder' => 'Month', 'maxlength' => '2', 'required', 'data-role' => 'paymentAccount-dobMonth', 'tabindex' => "3"]) !!}

                      {!! Form::text('legal_entity[dob][day]', Input::old('legal_entity[dob][day]'),  ['class' => 'form-control payment-dob num-only', 'placeholder' => 'Day', 'maxlength' => '2', 'required', 'data-role' => 'paymentAccount-dobDay', 'tabindex' => "4"]) !!}

                      {!! Form::text('legal_entity[dob][year]', Input::old('legal_entity[dob][year]'),  ['class' => 'form-control payment-yob num-only', 'placeholder' => 'Year', 'maxlength' => '4', 'required', 'data-role' => 'paymentAccount-dobYear', 'tabindex' => "5"]) !!}
                    </div>

                    <div class="form-group col-xs-12 col-sm-5 ">
                      {{-- Disable the form if email is already on record, we dont want to update it thus yet --}}
                      {!! Form::label('email', 'Email', ['class' => 'clearfix control-label']) !!} <span class="text-danger">&#42;</span>
                      {!! Form::text('email',  is_null($account['email']) ? Input::old('email') : $account['email'], ['class' => 'form-control', 'placeholder' => 'Email', 'data-role' => 'paymentAccount-email', 'tabindex' => "6", 'tabIndex' => '6']) !!}
                    </div>

                    <div class="form-group col-xs-12 col-sm-10 col-sm-offset-1">
                      <h3>Address</h3>
                    </div>

                    <div class="form-group col-xs-12 col-sm-10 col-sm-offset-1">
                      {!! Form::text('legal_entity[address][line1]', Input::old('legal_entity[address][line1]'), ['class' => 'form-control', 'placeholder' => 'Address Line 1', 'maxlength' => '50', 'required']) !!}
                    </div>

                    <div class="form-group col-xs-12 col-sm-3 col-sm-offset-1">
                      {!! Form::text('legal_entity[address][city]', Input::old('legal_entity[address][city]'), ['class' => 'form-control', 'placeholder' => 'City', 'maxlength' => '50', 'required']) !!}
                    </div>

                    <div class="form-group col-xs-12 col-sm-4">
                      {!! Form::text('legal_entity[address][state]', Input::old('legal_entity[address][state]'), ['class' => 'form-control', 'placeholder' => 'State', 'maxlength' => '50', 'required']) !!}
                    </div>

                    <div class="form-group col-xs-12 col-sm-3">
                      {!! Form::text('legal_entity[address][postal_code]', Input::old('legal_entity[address][postal_code]'), ['class' => 'form-control', 'placeholder' => 'Postal Code', 'maxlength' => '50', 'required']) !!}
                    </div>

                    <div class="form-group col-xs-12 col-sm-10 col-sm-offset-1">
                      <h3>Account Information</h3>
                    </div>

                    {{-- Bank Account Array to create BA token --}}
                    <div class="form-group col-xs-12 col-sm-5 col-sm-offset-1">
                      {!! Form::label('bank_account[routing_number]', 'Routing Number', ['class' => 'clearfix control-label']) !!} <span class="text-danger">&#42;</span>
                      {!! Form::text('bank_account[routing_number]', Input::old('bank_account[routing_number]'), ['class' => 'form-control num-only', 'data-role' => 'paymentAccount-routingNumber', 'placeholder' => 'Routing Number', 'required', 'tabIndex' => '7']) !!}
                    </div>

                    <div class="form-group col-xs-12 col-sm-5">
                      {!! Form::label('bank_account[account_number]', 'Account Number', ['class' => 'clearfix control-label']) !!} <span class="text-danger">&#42;</span>
                      {!! Form::text('bank_account[account_number]', Input::old('bank_account[account_number]'), ['class' => 'form-control num-only', 'data-role' => 'paymentAccount-accountNumber', 'placeholder' => 'Account Number', 'required', 'tabIndex' => '8']) !!}
                    </div>

                    <div class="form-group col-xs-12 col-sm-3 col-sm-offset-1">
                      {!! Form::label('legal_entity[type]', 'Account Type', ['class' => 'clearfix control-label']) !!} <span class="text-danger">&#42;</span>
                      {!! Form::select('legal_entity[type]', ['null' => 'Account Legal Entity Type', 'individual' => 'Individual', 'company' => 'Company'], Input::old('legal_entity[type]'), ['class' => 'form-control', 'data-role' => 'paymentAccount-accountType', 'placeholder' => 'Account Type ... "Individual" or "Company" ', 'maxlength' => '100', 'required', 'tabIndex' => '9']) !!}
                    </div>

                    <div class="form-group col-xs-12 col-sm-4">
                      {!! Form::label('legal_entity[ssn_last_4]', 'SSN Last4', ['class' => 'clearfix control-label']) !!} <span class="text-danger">&#42;</span>
                      {!! Form::text('legal_entity[ssn_last_4]', Input::old('legal_entity[ssn_last_4]'), ['class' => 'form-control num-only', 'data-role' => 'last4', 'placeholder' => 'SSN Last4', 'maxlength' => '4', 'required']) !!}
                    </div>

                    <div class="form-group col-xs-12 col-sm-3">
                      {!! Form::label('bank_account[country]', 'Country (2 Letters)', ['class' => 'clearfix control-label']) !!} <span class="text-danger">&#42;</span>
                      {!! Form::text('bank_account[country]', Input::old('bank_account[country]'), ['class' => 'form-control num-only payment-name', 'data-role' => 'paymentAccount-country', 'placeholder' => '2 Letter Country', 'maxlength' => '2', 'required', 'tabIndex' => '10']) !!}
                    </div>

                    <div class="row">

                      <div class="col-xs-12 col-sm-6 col-sm-offset-1">

                        {{-- TOS snippit --}}
                        <small>By clicking, you are agreeing to the Suaray Purchase Policy, Privacy Policy, and <a href="https://stripe.com/connect/account-terms" target="_blank">Stripe Connected Account Agreement</a>.</small>

                      </div>

                      <div class="col-sm-4 ">

                        {{-- TOS required submit --}}
                        {!! Form::hidden('tos_acceptance[date]', time()) !!}
                        {!! Form::hidden('tos_acceptance[ip]', $_SERVER['REMOTE_ADDR']) !!}
                        {!! Form::hidden('userId', \Auth::user()->id) !!}
                        <button class="btn btn-suaray btn-suaray-positive btn-lg pull-right" type="submit" tabindex="11">Save</button>

                      </div>

                    </div>

                  {!! Form::close() !!}

                </div>

              @else

                {{-- Start the form to for the user to add their account infomaation --}}
                {!! ViewHelper::formOpen(['route' => ['payment.update', isset($accountId) ? $accountId : 'no-account'], 'name' => 'paymentForm']) !!}

                  <div class="form-group col-xs-10 col-sm-10 col-sm-offset-1 ">
                    <h3>Account Holder</h3>
                  </div>

                  {{-- Name Legal Entity Array --}}
                  <div class="form-group col-xs-12 col-sm-5 col-sm-offset-1 ">
                    {!! Form::label('legal_entity[first_name]', 'First Name', ['class' => 'clearfix control-label']) !!} <span class="text-danger">&#42;</span>
                    {!! Form::text('legal_entity[first_name]', Input::old('legal_entity[first_name'),  ['class' => 'form-control payment-name', 'placeholder' => 'First Name...', 'maxlength' => '30', 'required', 'data-role' => 'paymentAccount-firstName', 'tabindex' => "1"]) !!}
                  </div>

                  <div class="form-group col-xs-12 col-sm-5 ">
                    {!! Form::label('legal_entity[last_name]', 'Last Name', ['class' => 'clearfix control-label']) !!} <span class="text-danger">&#42;</span>
                    {!! Form::text('legal_entity[last_name]', Input::old('legal_entity[last_name]'),  ['class' => 'form-control payment-name', 'placeholder' => 'Last Name...', 'maxlength' => '50', 'required', 'data-role' => 'paymentAccount-lastName', 'tabindex' => "2"]) !!}
                  </div>

                  {{-- Birthday Legal Entity Array --}}
                  <div class="form-group col-xs-12 col-sm-5 col-sm-offset-1">
                    <p class="payment-dob-label">{!! Form::label('legal_entity[dob][month]', 'Date of Birth', ['class' => 'clearfix control-label']) !!} <span class="text-danger">&#42;</span></p>
                    {!! Form::text('legal_entity[dob][month]', Input::old('legal_entity[dob][month]'),  ['class' => 'form-control payment-mob num-only', 'placeholder' => 'Month', 'maxlength' => '2', 'required', 'data-role' => 'paymentAccount-dobMonth', 'tabindex' => "3"]) !!}

                    {!! Form::text('legal_entity[dob][day]', Input::old('legal_entity[dob][day]'),  ['class' => 'form-control payment-dob num-only', 'placeholder' => 'Day', 'maxlength' => '2', 'required', 'data-role' => 'paymentAccount-dobDay', 'tabindex' => "4"]) !!}

                    {!! Form::text('legal_entity[dob][year]', Input::old('legal_entity[dob][year]'),  ['class' => 'form-control payment-yob num-only', 'placeholder' => 'Year', 'maxlength' => '4', 'required', 'data-role' => 'paymentAccount-dobYear', 'tabindex' => "5"]) !!}
                  </div>

                  <div class="form-group col-xs-12 col-sm-5 ">
                    {{-- Disable the form if email is already on record, we dont want to update it thus yet --}}
                    {!! Form::label('email', 'Email', ['class' => 'clearfix control-label']) !!} <span class="text-danger">&#42;</span>
                    {!! Form::text('email',  isset($account['email']) ? $account['email'] : Input::old('email'), ['class' => 'form-control', 'placeholder' => 'Email', 'data-role' => 'paymentAccount-email', 'tabindex' => "6", 'tabIndex' => '6']) !!}
                  </div>

                  <div class="form-group col-xs-12 col-sm-10 col-sm-offset-1">
                    <h3>Address</h3>
                  </div>

                  <div class="form-group col-xs-12 col-sm-10 col-sm-offset-1">
                    {!! Form::text('legal_entity[address][line1]', Input::old('legal_entity[address][line1]'), ['class' => 'form-control', 'placeholder' => 'Address Line 1', 'maxlength' => '50', 'required']) !!}
                  </div>

                  <div class="form-group col-xs-12 col-sm-3 col-sm-offset-1">
                    {!! Form::text('legal_entity[address][city]', Input::old('legal_entity[address][city]'), ['class' => 'form-control', 'placeholder' => 'City', 'maxlength' => '50', 'required']) !!}
                  </div>

                  <div class="form-group col-xs-12 col-sm-4">
                    {!! Form::text('legal_entity[address][state]', Input::old('legal_entity[address][state]'), ['class' => 'form-control', 'placeholder' => 'State', 'maxlength' => '50', 'required']) !!}
                  </div>

                  <div class="form-group col-xs-12 col-sm-3">
                    {!! Form::text('legal_entity[address][postal_code]', Input::old('legal_entity[address][postal_code]'), ['class' => 'form-control', 'placeholder' => 'Postal Code', 'maxlength' => '50', 'required']) !!}
                  </div>

                  <div class="form-group col-xs-12 col-sm-10 col-sm-offset-1">
                    <h3>Account Information</h3>
                  </div>

                  {{-- Bank Account Array to create BA token --}}
                  <div class="form-group col-xs-12 col-sm-5 col-sm-offset-1">
                    {!! Form::label('bank_account[routing_number]', 'Routing Number', ['class' => 'clearfix control-label']) !!} <span class="text-danger">&#42;</span>
                    {!! Form::text('bank_account[routing_number]', Input::old('bank_account[routing_number]'), ['class' => 'form-control num-only', 'data-role' => 'paymentAccount-routingNumber', 'placeholder' => 'Routing Number', 'required', 'tabIndex' => '7']) !!}
                  </div>

                  <div class="form-group col-xs-12 col-sm-5">
                    {!! Form::label('bank_account[account_number]', 'Account Number', ['class' => 'clearfix control-label']) !!} <span class="text-danger">&#42;</span>
                    {!! Form::text('bank_account[account_number]', Input::old('bank_account[account_number]'), ['class' => 'form-control num-only', 'data-role' => 'paymentAccount-accountNumber', 'placeholder' => 'Account Number', 'required', 'tabIndex' => '8']) !!}
                  </div>

                  <div class="form-group col-xs-12 col-sm-3 col-sm-offset-1">
                    {!! Form::label('legal_entity[type]', 'Account Type', ['class' => 'clearfix control-label']) !!} <span class="text-danger">&#42;</span>
                    {!! Form::select('legal_entity[type]', ['null' => 'Account Legal Entity Type', 'individual' => 'Individual', 'company' => 'Company'], Input::old('legal_entity[type]'), ['class' => 'form-control', 'data-role' => 'paymentAccount-accountType', 'placeholder' => 'Account Type ... "Individual" or "Company" ', 'maxlength' => '100', 'required', 'tabIndex' => '9']) !!}
                  </div>

                  <div class="form-group col-xs-12 col-sm-4">
                    {!! Form::label('legal_entity[ssn_last_4]', 'SSN Last4', ['class' => 'clearfix control-label']) !!} <span class="text-danger">&#42;</span>
                    {!! Form::text('legal_entity[ssn_last_4]', Input::old('legal_entity[ssn_last_4]'), ['class' => 'form-control num-only', 'data-role' => 'last4', 'placeholder' => 'SSN Last4', 'maxlength' => '4', 'required']) !!}
                  </div>

                  <div class="form-group col-xs-12 col-sm-3">
                    {!! Form::label('bank_account[country]', 'Country', ['class' => 'clearfix control-label']) !!} <span class="text-danger">&#42;</span>
                    {!! Form::text('bank_account[country]', Input::old('bank_account[country]'), ['class' => 'form-control num-only payment-name', 'data-role' => 'paymentAccount-country', 'placeholder' => '2 Letter Country', 'maxlength' => '2', 'required', 'tabIndex' => '10']) !!}
                  </div>

                  <div class="row">

                    <div class="col-xs-12 col-sm-6 col-sm-offset-1">

                      {{-- TOS snippit --}}
                      <small>By clicking, you are agreeing to the Suaray Purchase Policy, Privacy Policy, and <a href="https://stripe.com/connect/account-terms" target="_blank">Stripe Connected Account Agreement</a>.</small>

                    </div>

                    <div class="col-sm-4 ">

                      {{-- TOS required submit --}}
                      {!! Form::hidden('tos_acceptance[date]', time()) !!}
                      {!! Form::hidden('tos_acceptance[ip]', $_SERVER['REMOTE_ADDR']) !!}
                      {!! Form::hidden('userId', \Auth::user()->id) !!}
                      <button class="btn btn-suaray btn-suaray-positive btn-lg pull-right" type="submit" tabindex="11">Save</button>

                    </div>

                  </div>

                {!! Form::close() !!}

              @endif

            </div>

          </div>

        </div>

      {{-- </div> --}}

    </div>

  </div>

</div>

@stop
