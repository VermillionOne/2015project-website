@extends('layouts.master')

@section('content')

{{-- User feedback --}}
@include('pages.feedback-tab')

  <div class="profile-container" data-ng-controller="SettingsController">
    <div class="container setting-sect">
      <div class="row">

        {{-- Session Message --}}
        @include('includes.sessionStatus')

        {{-- Left menu --}}
        <div class="col-md-3 col-sm-3" id="sidebar" role="navigation" ng-init="tab = 'account'">

          <ul class="nav nav-pills nav-stacked setting-navbar">

            <li role="presentation" ng-class="{active: tab === 'account'}">
              <a ng-click="tab = 'account' " data-role="tab-account">
                <span class="glyphicon glyphicon-user"></span>
                Account
              </a>
            </li>
            <li role="presentation" ng-class="{active: tab === 'security'}">
              <a ng-click="tab ='security' " data-role="tab-security">
                <i class="glyphicon glyphicon-lock">
                  </i> Security
              </a>
            </li>
            <li role="presentation" ng-class="{active: tab === 'billing'}">
              <a ng-click="tab = 'billing' " data-role="tab-billing">
                <i class="glyphicon glyphicon-credit-card">
                </i> Billing
                </a>
            </li>

            <li role="presentation" ng-class="{active: tab === 'notifications'}">
              <a ng-click="tab = 'notifications' " data-role="tab-notifications">
                <i class="fa fa-users">
                </i> Notifications
              </a>
            </li>

            <li role="presentation" ng-class="{active: tab === 'content-restrictions'}">
              <a ng-click="tab ='content-restrictions' " data-role="tab-content-restrictions">
                <i class="glyphicon glyphicon-alert">
                </i> Content Restrictions
              </a>
            </li>
          </ul>
        </div>

        {{-- Setting options --}}
        <div class="col-md-9 col-sm-8">

          {{-- Users account information --}}
          <div ng-show="tab === 'account' ">
            <div class="setting-well col-md-12">

              <h3 class="setting-title"> Account Settings </h3>

              {{-- Form for account settings --}}
              {!! ViewHelper::formModel($user, ['url' => action('AccountsController@doAccountSettings'), 'method' => 'PUT']) !!}

                <div class="row">
                  <div class="form-group col-sm-6">
                    <label class="control-label">First Name</label>
                    {!! Form::text('firstName', Input::old('firstName'), ['class' => 'form-control input-sm']) !!}
                  </div>
                  <div class="form-group col-sm-6">
                    <label class="control-label">Last Name</label>
                    {!! Form::text('lastName', Input::old('lastName'), ['class' => 'form-control input-sm']) !!}
                  </div>
                </div>

                {!! Form::hidden('id', $user['id']) !!}

                <div class="row">
                  <div class="form-group col-sm-10 sign-up-txt">
                    <label class="control-label">Email</label>
                    {!! Form::text('email', Input::old('email'), [ 'class' => 'form-control input-sm']) !!}
                  </div>
                </div>

                <div class="row">
                  <div class="form-group col-sm-3 sign-up-txt">
                    <label class="control-label">Gender</label>
                    {!! Form::select('gender', ['male' => 'male', 'female' => 'female'], Input::old('gender'), ['class' => 'form-control input-sm']) !!}
                  </div>
                </div>

                <div class="row">
                  <div class="col-sm-12 sign-up-txt form-group">
                    <label class="control-label">Birthdate</label>
                  </div>
                </div>

                <div class="row">
                  <div class="col-sm-3 form-group">
                    {!! Form::select('month', $months, Input::old('months'), ['class' => 'form-control input-sm']) !!}
                  </div>
                  <div class="col-sm-3 form-group">

                    {!! Form::selectRange('days', 1, 31, Input::old('days'), ['class' => 'form-control input-sm']); !!}

                  </div>
                  <div class="col-sm-3 form-group">
                    {!! Form::selectRange('year', 1915, 2015, Input::old('year'), ['class' => 'form-control input-sm']) !!}
                  </div>
                </div>

                <div class="row">
                  <div class="col-sm-12 setting-initial-save">
                    <button class="btn btn-suaray btn-suaray-primary pull-right">Save Changes</button>
                  </div>
                </div>
                {!! Form::token() !!}

              {!! Form::close() !!}

              <div class="setting-spacing">

                {{-- Form for account settings --}}
                {!! ViewHelper::formModel($user, ['url' => action('AccountsController@doAccountPassword'), 'method' => 'PUT']) !!}

                   <!-- Password edit -->
                  <div class="row">
                    <div class="col-sm-12 sign-up-txt form-group">
                      <label class="control-label">Change Password</label>
                    </div>
                  </div>

                  <div class="row">

                    {{-- Input for old password --}}
                    <div class="col-sm-4 form-group">
                      <label class="control-label">Current</label>
                      <input type="password" name="current_password" maxlength="50" class="form-control input-sm">
                    </div>

                    {{-- Input for new password --}}
                    <div class="col-sm-4 form-group @if ($errors->has('password')) has-error @endif }}">
                      {!! Form::label('password', 'New', ['class' => 'control-label']) !!}

                      {!! Form::password('password', ['class' => 'form-control input-sm']) !!}
                      {!! $errors->first('password', '<p class="help-block">:message</p>') !!}
                    </div>

                    {{-- Input for confirm password --}}
                    <div class="col-sm-4 form-group  @if ($errors->has('password_confirmation')) has-error @endif }}">
                      {!! Form::label('password_confirmation', 'Confirm', ['class' => 'control-label']) !!}

                      {!! Form::password('password_confirmation', ['class' => 'form-control input-sm']) !!}
                      {!! $errors->first('password_confirmation', '<p class="help-block">:message</p>') !!}
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-sm-12 setting-initial-save">
                      <button class="btn btn-suaray btn-suaray-primary pull-right" name="save-password">Save Changes</button>
                    </div>
                  </div>

                  {!! Form::token() !!}

                {!! Form::close() !!}

              </div>

            </div>
          </div>

          {{-- Security Settings --}}
          <div class="setting-well col-md-12" ng-show="tab === 'security' ">
            <div class="security-settings">

              <h3 class="setting-title"> Security Settings </h3>

              {{-- {{ dd($user) }} --}}

              {!! ViewHelper::formModel($meta, ['url' => action('AccountsController@doSecuritySettings'), 'class' => 'form-horizontal', 'method' => 'PUT']) !!}

                <div class="panel panel-default">
                  <div class="panel-heading row">

                    <div class="col-sm-8 sign-up-txt">
                      <label class="control-label">Verify login requests</label>
                    </div>

                    <div class="col-sm-4 sign-up-txt">
                      <label class="radio-inline">{!! Form::radio('loginrequest', true, false, ['autocomplete' => 'off', 'name' => 'loginrequest']) !!}Yes</label>
                      <label class="radio-inline">{!! Form::radio('loginrequest', 0, false, ['autocomplete' => 'off', 'name' => 'loginrequest']) !!}No</label>
                    </div>

                  </div>
                </div>

                <div class="panel panel-default">
                  <div class="panel-heading row">
                    <div class="col-sm-8 sign-up-txt">
                      <label class="control-label">Send login verification requests to my phone </label>
                    </div>
                    <div class="col-sm-4 sign-up-txt">
                      <label class="radio-inline">{!! Form::radio('loginverify', '1', Input::old('loginverify') === false ? true : false) !!}Yes</label>
                      <label class="radio-inline">{!! Form::radio('loginverify', '0', Input::old('loginverify') === false ? true : false) !!}No</label>
                    </div>
                  </div>
                </div>

                <div class="panel panel-default">
                  <div class="panel-heading row">
                    <div class="col-sm-8 sign-up-txt">
                      <label class="control-label">Require personal information to reset my password </label>
                    </div>
                    <div class="col-sm-4 sign-up-txt">
                      <label class="radio-inline">{!! Form::radio('personalinfo', '1', Input::old('personalinfo') === false ? true : false) !!}Yes</label>
                      <label class="radio-inline">{!! Form::radio('personalinfo', '0', Input::old('personalinfo') === false ? true : false) !!}No</label>
                    </div>
                  </div>
                </div>

                <div class="panel panel-default">
                  <div class="panel-heading row">
                    <div class="col-sm-8 sign-up-txt">
                      <label class="control-label">Let others find me</label>
                    </div>
                    <div class="col-sm-4 sign-up-txt">
                      <label class="radio-inline">{!! Form::radio('findme', '1', Input::old('findme') === false ? true : false) !!}Yes</label>
                      <label class="radio-inline">{!! Form::radio('findme', '0', Input::old('findme') === false ? true : false) !!}No</label>
                    </div>
                  </div>
                </div>

                <div class="row">
                  <div class="col-sm-12 setting-save-button-align">
                    <button class="btn btn-suaray btn-suaray-primary pull-right" name="save-security">Save Changes</button>
                  </div>
                </div>
                  {!! Form::token() !!}

              {!! Form::close() !!}
            </div>
          </div>

            <div ng-show="tab === 'billing' ">
              @include('events.billing.credit-card')
            </div>

          <div ng-show="tab === 'notifications' ">
            <div class="notification-settings setting-well col-md-12">
              <h3 class="setting-title"> Notification Settings </h3>
              <h4 class="setting-subtitle">Notify Me When</h4>

              {!! ViewHelper::formModel($meta, ['route' => ['notification.update'], 'class' => 'form-horizontal', 'method' => 'PUT']) !!}

                  <div class="panel panel-default">
                    <div class="panel-heading row">
                      <div class="col-sm-8 sign-up-txt">
                        <label class="control-label">a comment is left on my wall</label>
                      </div>
                      <div class="col-sm-4 sign-up-txt">
                       <label class="radio-inline">{!! Form::radio('wallcomments', '1', Input::old('wallcomments') === false ? true : false) !!}Yes</label>
                        <label class="radio-inline">{!! Form::radio('wallcomments', '0', Input::old('wallcomments') === false ? true : false) !!}No</label>
                      </div>
                    </div>
                  </div>

                  <div class="panel panel-default">
                    <div class="panel-heading row">
                      <div class="col-sm-8 sign-up-txt">
                        <label class="control-label">a comment is left on my events page</label>
                      </div>
                      <div class="col-sm-4 sign-up-txt">
                        <label class="radio-inline">{!! Form::radio('comments', '1', Input::old('comments') === false ? true : false) !!}Yes</label>
                        <label class="radio-inline">{!! Form::radio('comments', '0', Input::old('comments') === false ? true : false) !!}No</label>
                      </div>
                    </div>
                  </div>

                  <div class="panel panel-default">
                    <div class="panel-heading row">
                      <div class="col-sm-8 sign-up-txt">
                        <label class="control-label">login from unrecognized device</label>
                      </div>
                      <div class="col-sm-4 sign-up-txt">
                        <label class="radio-inline">{!! Form::radio('loginnotification', '1', Input::old('loginnotification') === false ? true : false) !!}Yes</label>
                        <label class="radio-inline">{!! Form::radio('loginnotification', '0', Input::old('loginnotification') === false ? true : false) !!}No</label>
                      </div>
                    </div>
                  </div>

                <h4 class="setting-subtitle">Notify My Guests When</h4>

                  <div class="panel panel-default">
                    <div class="panel-heading row">
                      <div class="col-sm-8 sign-up-txt">
                        <label class="control-label">event time changes</label>
                      </div>
                      <div class="col-sm-4 sign-up-txt">
                        <label class="radio-inline">{!! Form::radio('eventtimechanges', '1', Input::old('eventtimechanges') === false ? true : false) !!}Yes</label>
                        <label class="radio-inline">{!! Form::radio('eventtimechanges', '0', Input::old('eventtimechanges') === false ? true : false) !!}No</label>
                      </div>
                    </div>
                  </div>

                  <div class="panel panel-default">
                    <div class="panel-heading row">
                      <div class="col-sm-8 sign-up-txt">
                        <label class="control-label">event is cancelled</label>
                      </div>
                      <div class="col-sm-4 sign-up-txt">
                        <label class="radio-inline">{!! Form::radio('cancelledevent', '1', Input::old('cancelledevent') === false ? true : false) !!}Yes</label>
                        <label class="radio-inline">{!! Form::radio('cancelledevent', '0', Input::old('cancelledevent') === false ? true : false) !!}No</label>
                      </div>
                    </div>
                  </div>

                  <div class="panel panel-default">
                    <div class="panel-heading row">
                      <div class="col-sm-8 sign-up-txt">
                        <label class="control-label">event photos are added</label>
                      </div>
                      <div class="col-sm-4 sign-up-txt">
                        <label class="radio-inline">{!! Form::radio('uploadedphotos', '1', Input::old('uploadedphotos') === false ? true : false) !!}Yes</label>
                        <label class="radio-inline">{!! Form::radio('uploadedphotos', '0', Input::old('uploadedphotos') === false ? true : false) !!}No</label>
                      </div>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-sm-12 setting-save-button-align">
                      <button class="btn btn-suaray btn-suaray-primary pull-right" name="save-notifications">Save Changes</button>
                    </div>
                  </div>
                  {!! Form::token() !!}

              {!! Form::close() !!}

            </div>
          </div>

          <div ng-show="tab === 'content-restrictions' ">
            <div class="content-settings setting-well col-md-12">
              <h3 class="setting-title"> Content Settings </h3>

             {!! ViewHelper::formModel($user, ['route' => ['content-restriction.update'], 'class' => 'form-horizontal', 'method' => 'PUT']) !!}

                 <div class="panel panel-default">
                   <div class="panel-heading row">
                     <div class="col-sm-8 sign-up-txt">
                       <label class="control-label">Allow User Comments</label>
                     </div>
                     <div class="col-sm-4 sign-up-txt">
                        <label class="radio-inline">{!! Form::radio('allowusercomments', '1', Input::old('allowusercomments') === false ? true : false) !!}Yes</label>
                        <label class="radio-inline">{!! Form::radio('allowusercomments', '0', Input::old('allowusercomments') === false ? true : false) !!}No</label>
                     </div>
                   </div>
                 </div>

                 <div class="panel panel-default">
                   <div class="panel-heading row">
                     <div class="col-sm-8 sign-up-txt">
                       <label class="control-label">Preview Comments Before Posting</label>
                     </div>
                     <div class="col-sm-4 sign-up-txt">
                        <label class="radio-inline">{!! Form::radio('previewcomments', '1', Input::old('previewcomments') === false ? true : false) !!}Yes</label>
                        <label class="radio-inline">{!! Form::radio('previewcomments', '0', Input::old('previewcomments') === false ? true : false) !!}No</label>
                     </div>
                   </div>
                 </div>

                 <div class="row">
                   <div class="col-sm-12 setting-save-button-align">
                     <button class="btn btn-suaray btn-suaray-primary pull-right" name="save-content">Save Changes</button>
                   </div>
                 </div>
                  {!! Form::token() !!}

              {!! Form::close() !!}
            </div>
          </div>
      </div>

      </div>
    </div>
  </div>
@stop
