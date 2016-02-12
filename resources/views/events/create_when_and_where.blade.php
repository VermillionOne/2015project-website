@extends('layouts.master')

@section('content')
<div class="container view-event-create-when_and_where" data-ng-controller="EventsCreateWhenAndWhereController">
  @include('events.create.step')
  <hr class="mt0">
  <div class="row">
    <div class="col-md-8 col-md-offset-2">
      {!! ViewHelper::formOpen(['url' => action('EventsController@doCreateWhenAndWhere', ['id' => $event['id']]), 'class' => 'form-horizontal', 'ng-click' => 'schedule.updateJson()']) !!}

        <h3>Event Schedule</h3>
        <hr>
        <div class="row">
          <div class="col-xs-8 col-xs-offset-2">
            {{ $errors->first('data', '<p class="help-block">:message</p>') }}
            <input type="text" class="hide" name="eventScheduleMeta" ng-model="schedule.jsonData">
            <script>scheduleJson = ""</script>
            <input type="text" class="hide" ng-model="schedule.data.tz_offset">
            <div class="form-group">
              <div class="radio radio-inline" ng-repeat="type in schedule.option.type">
                <input type="radio" id="schedule_type_@{{ type.id }}" ng-model="schedule.data.type" value="@{{ type.id }}">
                <label for="schedule_type_@{{ type.id }}">@{{ type.title }}</label>
              </div>
            </div>
            <div ng-show="schedule.data.type == 'one_time'">
              <div class="form-group">
                <label class="col-md-3 control-label">Event starts on</label>
                <div class="col-md-9">
                  <h4 class="form-inline m0">
                    <input type="text" class="form-control pull-left" size="9" ng-model="schedule.data.one_time_start" data-autoclose="1" placeholder="Start Date" bs-datepicker>
                    &nbsp;at&nbsp;
                    <input type="text" class="form-control" size="5" ng-model="schedule.data.one_time_start" data-time-format="HH:mm"
                      data-length="1" data-minute-step="1" data-arrow-behavior="picker" data-autoclose="1" bs-timepicker>
                  </h4>
                </div>
              </div>
              <div class="form-group">
                <label class="col-md-3 control-label">and will end on</label>
                <div class="col-md-9">
                  <h4 class="form-inline m0">
                    <input type="text" class="form-control pull-left" size="9" ng-model="schedule.data.one_time_end" data-autoclose="1" placeholder="Closing Date" bs-datepicker>
                    &nbsp;at&nbsp;
                    <input type="text" class="form-control" size="5" ng-model="schedule.data.one_time_end" data-time-format="HH:mm"
                      data-length="1" data-minute-step="1" data-arrow-behavior="picker" data-autoclose="1" bs-timepicker>
                  </h4>
                </div>
              </div>
            </div>
            <div ng-show="schedule.data.type == 'recurring'">
              <div class="form-group">
              <div class="form-group">
                <label class="col-md-3 control-label">Event starts on</label>
                <div class="col-md-9">
                  <h4 class="form-inline m0">
                    <input type="text" class="form-control pull-left" size="9" ng-model="schedule.data.recurring_start" data-autoclose="1" placeholder="Start Date" bs-datepicker>
                  </h4>
                </div>
              </div>

                <div>
                  <hr class="mt5 mb5">
                  <div class="radio radio-inline" ng-repeat="type in schedule.option.recurring_year_type" style="width: 30%;">
                    <input type="radio" id="schedule_recurring_year_type_@{{ type.id }}" ng-model="schedule.data.recurring_year_type" value="@{{ type.id }}">
                    <label for="schedule_recurring_year_type_@{{ type.id }}">@{{ type.title }}</label>
                  </div>
                  <div ng-show="schedule.data.recurring_year_type == 'only'" class="mt10">
                    <div class="checkbox checkbox-inline" ng-repeat="year in schedule.option.recurring_year">
                      <input type="checkbox" id="schedule_recurring_year_@{{ year }}" ng-model="schedule.data.recurring_year[year]" ng-true-value="@{{ year }}">
                      <label for="schedule_recurring_year_@{{ year }}">@{{ year }}</label>
                    </div>
                  </div>
                </div>
                <div ng-show="schedule.data.recurring_year_type">
                  <hr class="mt10 mb5">
                  <div class="radio radio-inline" ng-repeat="type in schedule.option.recurring_month_type" style="width: 30%;">
                    <input type="radio" id="schedule_month_type_@{{ type.id }}" ng-model="schedule.data.recurring_month_type" value="@{{ type.id }}">
                    <label for="schedule_month_type_@{{ type.id }}">@{{ type.title }}</label>
                  </div>
                  <div ng-show="schedule.data.recurring_month_type == 'only'" class="mt10">
                    <div class="checkbox checkbox-inline" ng-repeat-start="month in schedule.option.recurring_month" style="width: 12%;">
                      <input type="checkbox" id="schedule_recurring_month_@{{ month.id }}" ng-model="schedule.data.recurring_month[month.id]" ng-true-value="'@{{ month.id }}'">
                      <label for="schedule_recurring_month_@{{ month.id }}">@{{ month.title }}</label>
                    </div>
                    <br ng-if="month.id == 'june'" ng-repeat-end>
                  </div>
                </div>
                <div ng-show="schedule.data.recurring_month_type">
                  <hr class="mt5 mb5">
                  <div class="radio radio-inline" ng-repeat="type in schedule.option.recurring_day_type" style="width: 30%;">
                    <input type="radio" id="schedule_recurring_day_type_@{{ type.id }}" ng-model="schedule.data.recurring_day_type" value="@{{ type.id }}">
                    <label for="schedule_recurring_day_type_@{{ type.id }}">@{{ type.title }}</label>
                  </div>
                  <div ng-show="schedule.data.recurring_day_type == 'by_week_days'" class="mt10">
                    <div class="checkbox checkbox-inline" ng-repeat-start="week in schedule.option.recurring_week">
                      <input type="checkbox" id="schedule_recurring_week_@{{ week.id }}" ng-model="schedule.data.recurring_week[week.id]" ng-true-value="'@{{ week.id }}'">
                      <label for="schedule_recurring_week_@{{ week.id }}">@{{ week.title }}</label>
                    </div>
                    <br ng-if="week.id == 4" ng-repeat-end>
                    <div class="mb15 mt5"></div>
                    <div ng-show="schedule.data.recurring_week" class="checkbox checkbox-inline" ng-repeat="weekDay in schedule.option.recurring_week_day">
                      <input type="checkbox" id="schedule_recurring_week_day_@{{ weekDay.id }}" ng-model="schedule.data.recurring_week_day[weekDay.id]" ng-true-value="'@{{ weekDay.id }}'">
                      <label for="schedule_recurring_week_day_@{{ weekDay.id }}">@{{ weekDay.title }}</label>
                    </div>
                  </div>
                  <div ng-show="schedule.data.recurring_day_type == 'by_month_days'">
                    <div class="checkbox checkbox-inline" ng-repeat-start="monthDay in schedule.option.recurring_month_day" ng-style="monthDay.id !== 'last_day' && {width: '9%'}">
                      <input type="checkbox" id="schedule_recurring_month_day_@{{ monthDay.id }}" ng-model="schedule.data.recurring_month_day[monthDay.id]" ng-true-value="'@{{ monthDay.id }}'">
                      <label for="schedule_recurring_month_day_@{{ monthDay.id }}">@{{ monthDay.title }}</label>
                    </div>
                    <br ng-if="monthDay.id == 7 || monthDay.id == 14 || monthDay.id == 21 || monthDay.id == 28 || monthDay.id == 31" ng-repeat-end>
                  </div>
                </div>
                <div ng-show="schedule.data.recurring_day_type && (schedule.data.recurring_week_day || schedule.data.recurring_month_day)">
                  <hr class="mt20 mb20">
                  <h4 class="form-inline">
                    Opening at
                    <input type="text" class="form-control" size="5" ng-model="schedule.data.recurring_opening_time" data-time-format="HH:mm" data-time-type="string"
                      data-length="1" data-minute-step="1" data-arrow-behavior="picker" data-autoclose="1" name="time2" bs-timepicker>
                  </h4>
                </div>
                <div ng-show="schedule.data.recurring_day_type && (schedule.data.recurring_week_day || schedule.data.recurring_month_day)">
                  <hr class="mt20 mb20">
                  <h4>Closing</h4>
                  <div class="form-inline">
                    <div class="radio radio-inline" ng-repeat="type in schedule.option.recurring_closing_type">
                      <input type="radio" id="schedule_recurring_closing_type_@{{ type.id }}" ng-model="schedule.data.recurring_closing_type" value="@{{ type.id }}">
                      <label for="schedule_recurring_closing_type_@{{ type.id }}">@{{ type.title }}</label>
                    </div>
                  </div>
                  <h4 class="form-inline mt20" ng-show="schedule.data.recurring_closing_type">
                    On the
                    <span ng-show="schedule.data.recurring_closing_type == 'by_day'">
                      <select class="form-control" ng-model="schedule.data.recurring_closing_by_day" ng-options="opt.id as opt.title for opt in schedule.option.recurring_closing_by_day">
                      </select>
                    </span>
                    <span ng-show="schedule.data.recurring_closing_type == 'by_week_day'">
                      <select class="form-control" ng-model="schedule.data.recurring_closing_by_week_day_week" ng-options="opt.id as opt.title for opt in schedule.option.recurring_closing_by_week_day_week">
                      </select>
                      <span ng-show="schedule.data.recurring_closing_by_week_day_week">
                        day
                        <select class="form-control" ng-model="schedule.data.recurring_closing_by_week_day_day" ng-options="opt.id as opt.title for opt in schedule.option.recurring_closing_by_week_day_day">
                        </select>
                      </span>
                    </span>
                    <span ng-show="schedule.data.recurring_closing_type == 'by_month_day'">
                      <select class="form-control" ng-model="schedule.data.recurring_closing_by_month_day_month" ng-options="opt.id as opt.title for opt in schedule.option.recurring_closing_by_month_day_month">
                      </select>
                      <span ng-show="schedule.data.recurring_closing_by_month_day_month">
                        day
                        <select class="form-control" ng-model="schedule.data.recurring_closing_by_month_day_day" ng-options="opt.id as opt.title for opt in schedule.option.recurring_closing_by_month_day_day">
                        </select>
                      </span>
                    </span>
                    at
                    <input type="text" class="form-control" size="5" ng-model="schedule.data.recurring_closing_time" data-time-format="HH:mm" data-time-type="string"
                      data-length="1" data-minute-step="1" data-arrow-behavior="picker" data-autoclose="1" bs-timepicker>
                  </h4>
                </div>
              </div>
            </div>
          </div>
        </div>
        <hr>
        <h3>Where?</h3>
        <hr>
        <div>
          <script>var page_event_address = "{{ addslashes($event['address1']) }}"</script>
          <input type="text" name="location" class="form-control address-input" placeholder="Address" ng-model="address.input" ng-change="address.update()">
          <input type="text" name="address1" class="hide" ng-model="address.formatted">
          <!--input type="text" class="form-control" placeholder="Address" ng-autocomplete ng-model="address.autocomplete" options="address.options" details="address.details" bounds="address.bounds"-->
          <input type="text" name="addressMeta" class="hide" ng-model="address.json">
          <br/>
          <!--ui-gmap-google-map center='address.map.center' zoom='address.map.zoom' control="address.map"></ui-gmap-google-map-->
          <div class="map-canvas"></div>
        </div>
        <br/>
        {!! Form::token() !!}
        <div class="form-group text-right">
          <div class="col-md-9 col-md-offset-3">
            <button class="btn btn btn-primary" type="submit">Next <small class="glyphicon glyphicon-chevron-right"></small></button>
          </div>
        </div>
      {!! Form::close() !!}
    </div>
  </div>
</div>
@stop

