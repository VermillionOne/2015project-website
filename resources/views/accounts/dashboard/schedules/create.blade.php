@extends('layouts.master')
@section('content')

<div class="tickets-bg" ng-controller="DashboardController">

  {{-- Session Message --}}
  @include('includes.sessionStatus')

  <div class="results-body">
    <div class="container">

      <div class="dashboard-titles">

        <div class="row">

          {{-- Add new schedule header --}}
          <div class="col-md-8 col-sm-6 col-xs-10">

            <h2 class="results-title-txt">Add New Schedule</h2>

          </div>

          {{-- Back to schedules link --}}
          <div class="col-md-4 col-sm-6 col-xs-2">

              <a href="{{ route('schedules.index', ['id' => $event['id']]) }}" class="hidden-xs clear-form search-clear">Back To Schedules</a>

          </div>

        </div>

      </div>

      <div class="row">
            <div class="schedule-card">
              {{-- Schedules update section --}}
              <div class = "event-detail-tickets-col padding-ticket">
                <div class="row">

                  {!! ViewHelper::formOpen(['route' => ['schedules.create', 'id' => $event['id']], 'ng-click' => 'schedule.updateJson()']) !!}

                    <div class = "col-sm-12">

                      {{-- Start Date --}}
                      <div class="form-group col-sm-5 col-sm-offset-1">

                        <div class = "ticket-type-new">
                        {{-- START DATE Tag --}}
                        {!! Form::label('startDate', 'Start Date', ['class' => 'clearfix control-label']) !!}

                        {{-- Start Date Picker --}}
                          <div class="inner-addon right-addon input-group-lg create-event-detail-date-picker">
                          <i class="glyphicon create-event-detail glyphicon-calendar"></i>

                          {!! Form::text('startDate', Input::old('startDate'), ['class' => 'form-control date-pick datepicker', 'placeholder' => 'MM/DD/YYY', 'data-role' => 'date-picker', 'ng-model' => 'startDate', 'id' => 'txtFromDate', 'data-date-format' => 'mm/dd/yyyy', 'required' => 'required']) !!}

                          </div>
                        </div>
                        {{ $errors->first('startDate', '<p class="help-block">:message</p>') }}

                        <div class="row">
                          {{-- Start Hour / Minute Input --}}
                          <div class="form-group col-sm-6 create-event-time-input">
                            {!! Form::label('startTime', 'Start Time') !!}

                            <div class="inner-addon right-addon input-group-lg create-event-detail-time-picker">
                            {!! Form::select('startTime', $timeList, 'HH:MM', ['class' => 'form-control', 'required' => 'required']) !!}
                              <i class="glyphicon create-event-detail glyphicon-time"></i>

                            </div>

                            <small class="text-danger">{{ $errors->first('startTime') }}</small>

                          </div>

                          {{-- Creates AM/PM Button Group --}}
                          <div class="col-sm-6 create-event-time-button">

                            <div class="btn-group pull-right" data-toggle="buttons">

                            {{-- AM Radio Input --}}
                            <span class="btn btn-suaray btn-suaray-primary control-label" ng-click="startAmPm = 'am'">
                              {!! Form::radio('startMeridian', 'am', Input::old('startMeridian'), ['class' => 'form-control', 'ng-model' => 'event.startMeridian', 'required' => 'required', 'ng-init' => 'event.startMeridian=true']) !!}
                            AM </span>

                            {{-- PM Radio Input --}}
                            <span class="btn btn-suaray btn-suaray-primary control-label" ng-click="startAmPm = 'pm'">
                              {!! Form::radio('startMeridian', 'pm', null, ['class' => 'form-control', 'ng-model' => 'event.startMeridian', 'required' => 'required']) !!}
                            PM </span>

                            </div>

                          </div>

                        </div>

                      </div>

                      {{-- Event End Date --}}
                      <div class="form-group col-sm-5">{{-- END DATE Tag --}}
                        {!! Form::label('endDate', 'End Date', ['class' => 'clearfix control-label']) !!}

                        {{-- End Date Picker --}}
                        <div class="inner-addon right-addon input-group-lg create-event-detail-date-picker">
                          <i class="glyphicon create-event-detail glyphicon-calendar"></i>

                          {!! Form::text('endDate', null, ['class' => 'form-control datepicker date-pick', 'data-role' => 'date-picker', 'placeholder' => 'MM/DD/YYYY', 'ng-model' => 'endDate', 'id' => 'txtToDate', 'data-date-format' => 'mm/dd/yyyy', 'required' => 'required']) !!}
                        </div>

                        <div class="row">

                          {{-- End Hour / Minute Input --}}
                          <div class="form-group col-sm-6 create-event-time-input">
                            {!! Form::label('endTime', 'End Time') !!}

                            <div class="inner-addon right-addon input-group-lg create-event-detail-time-picker">
                              <i class="glyphicon create-event-detail glyphicon-time"></i>
                                {!! Form::select('endTime', $timeList, 'HH:MM', ['class' => 'form-control', 'required' => 'required']) !!}

                            </div>

                            <small class="text-danger">{{ $errors->first('endTime') }}</small>
                          </div>

                          {{-- Creates AM/PM Button Group --}}
                          <div class="col-sm-6 create-event-time-button">

                            <div class="btn-group pull-right" data-toggle="buttons">

                                {{-- AM Radio Input --}}
                                <span class="btn btn-suaray btn-suaray-primary control-label" ng-click="endAmPm = 'am'">
                                  {!! Form::radio('endMeridian', 'am', null, ['class' => 'form-control', 'ng-model' => 'event.endMeridian', 'required' => 'required', 'ng-init' => 'event.endMeridian=true']) !!}
                                AM </span>

                                {{-- PM Radio Input --}}
                                <span class="btn btn-suaray btn-suaray-primary control-label" ng-click="endAmPm = 'pm'">
                                  {!! Form::radio('endMeridian', 'pm', null, ['class' => 'form-control', 'ng-model' => 'event.endMeridian', 'required' => 'required']) !!}
                                PM </span>

                            </div>

                          </div>

                        </div>

                      </div>

                      {{-- User to choose time zone for event --}}
                      <div class="form-group col-sm-10 col-sm-offset-1">
                        {!! Form::label('timeZoneId', 'Time Zone', ['class' => 'clearfix control-label']) !!}

                        {!! Form::select('timeZoneId', $timezoneList, \Config::get('api.defaults.timeZoneId'), ['class' => 'form-control', 'placeholder' => 'Select Time Zone', 'required']) !!}
                      </div>

                      <div class="form-group col-sm-3 col-sm-offset-1">

                        {{-- Recurring Checkbox --}}
                        <div class="create-event-recurring-checkbox">
                          <label class="control-label" >{!! Form::checkbox('recurring', 'recurring', null, ['ng-model' => 'event.repeat.enabled', 'data-role' => 'recurringSchedule', 'ng-init' => 'checked=false']) !!}&nbsp;&nbsp;Recurring</label>
                        </div>

                      </div>

                      <div class="" ng-show="event.repeat.enabled">

                        <div class="col-sm-10 col-sm-offset-1">

                          {{-- Recurring Checkbox --}}
                          <div class="col-sm-4 final-date-end">

                            <div class="recurring-select-dropdown">

                              {{-- User clicks checkbox to display age appropriate options --}}
                              <input type="checkbox" class="age-select"/>

                              <label class="final-date-select">Final Recurring Date
                                <span class="caret age-caret"></span>
                              </label>

                              {{-- Begin dropdown checkbox age options --}}
                              <section class="age-select">

                                  <div class="age-select">
                                    <input type="checkbox" value="1" name="finalDateSet" ng-model="selectFinal" class="age-select-checkbox" />
                                    <label class="age-select radGroup1">Set Date</label>
                                  </div>

                              </section>

                            </div>

                          </div>

                          {{-- Repeats Input Field --}}
                          <div class="form-group col-sm-2" >
                            {!! Form::label('repeats', 'Repeats', ['class' => 'clearfix control-label']) !!}
                            {!! Form::select('repeats',$repeatList, 'repeat', ['class' => 'form-control', 'data-role' => 'repeats', 'ng-model' => 'event.repeat.repeats']) !!}
                            {{ $errors->first('repeats', '<p class="help-block">:message</p>') }}
                          </div>

                          {{-- 'Repeats Every' Input Field --}}
                          <div class="form-group col-sm-2" >
                            {!! Form::label('repeatEvery', 'Every', ['class' => 'clearfix control-label']) !!}

                            {!! Form::selectRange('repeatEvery', 1, 30, '1', ['class' => 'form-control', 'ng-model' => 'event.repeat.every']) !!}

                            {{ $errors->first('repeatEvery', '<p class="help-block">:message</p>') }}
                          </div>

                          {{-- Shows if Daily is picked --}}
                          <div class="form-group col-sm-2 create-event-repeat-every-interim" ng-show="event.repeat.repeats === 'daily'">
                            <label class="clearfix control-label">Day<span ng-show="event.repeat.every > 1">s</span></label>
                          </div>

                          {{-- Shows if Weekly is picked --}}
                          <div class="form-group col-sm-2 create-event-repeat-every-interim" ng-show="event.repeat.repeats === 'weekly'">
                            <label class="clearfix control-label">Week<span ng-show="event.repeat.every > 1">s</span></label>
                          </div>

                          {{-- Shows if Monthly is picked --}}
                          <div class="form-group col-sm-2 create-event-repeat-every-interim" ng-show="event.repeat.repeats === 'monthly'">
                            <label class="clearfix control-label">Month<span ng-show="event.repeat.every > 1">s</span></label>
                          </div>

                          {{-- Shows if Yearly is picked --}}
                          <div class="form-group col-sm-2 create-event-repeat-every-interim" ng-show="event.repeat.repeats === 'yearly'">
                            <label class="clearfix control-label">Year<span ng-show="event.repeat.every > 1">s</span></label>
                          </div>

                        </div>

                        <div class="form-group col-sm-10 col-sm-offset-1" ng-show="event.repeat.repeats === 'weekly'">

                          <label class="control-label col-sm-12" >Repeats on:</label>

                          <ul class="create-event-recurring-days-of-week">
                            <li><label class="control-label" >{!! Form::checkbox('weekdays', 'sun', null, ['data-role' => 'day-of-week-sun']) !!}&nbsp;&nbsp;Su</label></li>
                            <li><label class="control-label" >{!! Form::checkbox('weekdays', 'mon', null, ['data-role' => 'day-of-week-mon']) !!}&nbsp;&nbsp;Mo</label></li>
                            <li><label class="control-label" >{!! Form::checkbox('weekdays', 'tue', null, ['data-role' => 'day-of-week-tue']) !!}&nbsp;&nbsp;Tu</label></li>
                            <li><label class="control-label" >{!! Form::checkbox('weekdays', 'wed', null, ['data-role' => 'day-of-week-wed']) !!}&nbsp;&nbsp;We</label></li>
                            <li><label class="control-label" >{!! Form::checkbox('weekdays', 'thu', null, ['data-role' => 'day-of-week-thu']) !!}&nbsp;&nbsp;Th</label></li>
                            <li><label class="control-label" >{!! Form::checkbox('weekdays', 'fri', null, ['data-role' => 'day-of-week-fri']) !!}&nbsp;&nbsp;Fr</label></li>
                            <li><label class="control-label" >{!! Form::checkbox('weekdays', 'sat', null, ['data-role' => 'day-of-week-sat']) !!}&nbsp;&nbsp;Sa</label></li>
                          </ul>

                          {!! Form::hidden('daysOfWeek', '', ['data-role' => 'daysOfWeek']) !!}

                        </div>

                        {{--  <div class="form-group col-sm-10 col-sm-offset-1" ng-show="event.repeat.repeats === 'monthly'">

                          <label class="control-label col-sm-12" >Repeats Monthly</label>

                          <ul class="create-event-recurring-repeat-by">
                            <li><label class="control-label" >{!! Form::radio('repeatsBy', 'dayOfMonth', null, ['ng-model' => 'event.repeat.months', 'ng-init' => 'checked=false']) !!}&nbsp;&nbsp;Day of the Month</label></li>
                            <li><label class="control-label" >{!! Form::radio('repeatsBy', 'dayOfWeek', null, ['ng-model' => 'event.repeat.months', 'ng-init' => 'checked=false']) !!}&nbsp;&nbsp;Day of the Week</label></li>
                          </ul>

                        </div> --}}

                        <div class="form-group col-sm-10 col-sm-offset-1" ng-show="selectFinal">

                          <div class="form-group col-sm-6" >

                          {!! Form::label('endFinalDate', 'Final Date', ['class' => 'clearfix control-label']) !!}<sup class="text-danger">&#42;</sup>

                          {{-- Final Date Picker --}}
                          <div class="inner-addon right-addon input-group-lg create-event-detail-date-picker">

                            <i class="glyphicon create-event-detail glyphicon-calendar"></i>
                            {!! Form::text('endFinalDate', '', ['class' => 'form-control datepicker date-pick', 'data-role' => 'date-picker', 'id' => 'txtFinalDate', 'data-date-format' => 'mm/dd/yyyy']) !!}

                          </div>

                          {{ $errors->first('endFinalDate', '<p class="help-block">:message</p>') }}

                          </div>

                          {{-- Event End Date --}}
                          <div class="form-group col-sm-5">{{-- END DATE Tag --}}

                            {{-- End Hour / Minute Input --}}
                            <div class="form-group create-final-time-input">
                              {!! Form::label('endFinalTime', 'End Time') !!}<sup class="text-danger">&#42;</sup>

                              <div class="inner-addon right-addon input-group-lg create-event-detail-time-picker">
                                <i class="glyphicon create-event-detail glyphicon-time"></i>
                                {!! Form::select('endFinalTime', $timeList, '1:00', ['class' => 'form-control']) !!}

                              </div>

                            </div>

                            <small class="text-danger">{{ $errors->first('endTime') }}</small>

                            {{-- Creates AM/PM Button Group --}}
                            <div class="create-final-time-btn">

                              <div class="btn-group pull-right" data-toggle="buttons">

                                {{-- AM Radio Input --}}
                                <span class="btn btn-suaray btn-suaray-primary control-label" ng-click="endAmPm = 'am'">
                                  {!! Form::radio('endFinalMeridian', 'am', false, ['class' => 'form-control']) !!}
                                AM </span>

                                {{-- PM Radio Input --}}
                                <span class="btn btn-suaray btn-suaray-primary control-label" ng-click="endAmPm = 'pm'">
                                  {!! Form::radio('endFinalMeridian', 'pm', true, ['class' => 'form-control']) !!}
                                PM </span>

                              </div>

                            </div>

                          </div>

                        </div>

                      </div>


                      {{-- Commenting out Pagination for now --}}
                      <div class="row">
                        <div class="form-group text-right">

                          {{-- Adds new dates for specific event --}}
                          <div class="col-sm-3 col-sm-offset-8">

                            <button class="btn btn-suaray btn-sm btn-block btn-suaray-positive pull-right">Submit</button>

                          </div>

                        </div>
                      </div>

                    </div> <!-- close row -->

                  {!! Form::close() !!}

                </div>

              </div>
            </div>
          </div>
        </div>

  </div>
</div>
@stop
