<?php if (!isset($details)) $details = 'create_event_details'; ?>
<?php if (!isset($detailsLocked)) $detailsLocked = true; ?>

  <div class="create-event-form-box">

    <div class="row">

      {{-- BEGIN Event Details input Fields --}}
      <h3 class="col-sm-12 create-event-form-main-title">Event Details</h3>

      {{-- Event Title --}}

      <div class="form-group col-sm-10 col-sm-offset-1">
        {!! Form::label('title', 'Title', ['class' => 'clearfix control-label']) !!} <sup class="text-danger">&#42;</sup>
        <i class="fa fa-question-circle fa-lg suaray-popover" tabindex="0" role="button" data-toggle="suaray-popover" data-trigger="focus" title="Event Title" data-content="Create a title for your event."></i>
        @if(Input::old('title'))
          {!! Form::text('title', Input::old('title'), ['class' => 'form-control ng-dirty', 'data-role' => 'eventTitle', 'placeholder' => 'Event Title...', 'required']) !!}
        @else
          {!! Form::text('title', Input::old('title'), ['class' => 'form-control', 'data-role' => 'eventTitle', 'placeholder' => 'Event Title...', 'required']) !!}
        @endif

        @if(Session::has('title'))
          <p class="edit-tickets-warn-text">{{ Session::get('title') }}</p>
        @endif

      </div>

      {{-- Event Description | Free --}}
      <div class="form-group col-sm-10 col-sm-offset-1">
        {!! Form::label('description', 'Description', ['class' => 'clearfix control-label']) !!} <sup class="text-danger">&#42;</sup>
        <i class="fa fa-question-circle fa-lg suaray-popover" tabindex="0" role="button" data-toggle="suaray-popover" data-trigger="focus" title="Event Description" data-content="Tell us what your event is about. The more info, the better. Feel free to give date, time, and location in here as well."></i>
        @if(Input::old('description'))
          {!! Form::textarea('description', Input::old('description'), ['class' => 'form-control ng-dirty icon-size', 'data-role' => 'eventDescription', 'rows' => '6', 'placeholder' => 'Add some information about your event...', 'required']) !!}
        @else
          {!! Form::textarea('description', Input::old('description'), ['class' => 'form-control icon-size', 'data-role' => 'eventDescription', 'rows' => '6', 'placeholder' => 'Add some information about your event...', 'required']) !!}
        @endif
      </div>

      {{-- Event Time Zone --}}
      <div class="form-group col-sm-10 col-sm-offset-1">
        {!! Form::label('timeZoneId', 'Time Zone', ['class' => 'clearfix control-label']) !!}<sup class="text-danger">&#42;</sup>
        <i class="fa fa-question-circle fa-lg suaray-popover" tabindex="0" role="button" data-toggle="suaray-popover" data-trigger="focus" title="Event Timezone" data-content="This is your event's timezone. This helps us make sure we're giving your attendees accurate time and date information."></i>
        @if(Input::old('timeZoneId'))
          {!! Form::select('timeZoneId', $timezoneList, Input::old('timeZoneId'), ['class' => 'form-control ng-dirty selected-timezone', 'data-role' => 'timeZoneId', 'required']) !!}
        @else
          {!! Form::select('timeZoneId', $timezoneList, \Config::get('api.defaults.timeZoneId'), ['class' => 'form-control selected-timezone', 'data-role' => 'timeZoneId', 'required']) !!}
        @endif
      </div>

      {{-- Event Start Date --}}
      <div class="form-group col-sm-5 col-sm-offset-1">

        {{-- START DATE Tag --}}
        {!! Form::label('startDate', 'Start Date', ['class' => 'clearfix control-label']) !!}<sup class="text-danger">&#42;</sup>
        <i class="fa fa-question-circle fa-lg suaray-popover" tabindex="0" role="button" data-toggle="suaray-popover" data-trigger="focus" title="Event Start Date" data-content="What is the first date your event starts on?"></i>

        {{-- Start Date Picker --}}
        <div class="inner-addon right-addon input-group-lg create-event-detail-date-picker">
          <i class="glyphicon create-event-detail glyphicon-calendar"></i>
          @if(Input::old('startDate'))
            {!! Form::text('startDate', Input::old('startDate'), ['class' => 'form-control ng-dirty date-pick datepicker', 'data-role' => 'date-picker', 'id' => 'txtFromDate', 'data-date-format' => 'mm/dd/yyyy', 'required']) !!}
          @else
            {!! Form::text('startDate', Input::old('startDate'), ['class' => 'form-control date-pick datepicker', 'data-role' => 'date-picker', 'id' => 'txtFromDate', 'data-date-format' => 'mm/dd/yyyy', 'required']) !!}
          @endif
        </div>

        {{ $errors->first('startDate', '<p class="help-block">:message</p>') }}

        <div class="row">

          {{-- Start Hour / Minute Input --}}
          <div class="form-group col-sm-6 create-event-time-input">
            {!! Form::label('startTime', 'Start Time') !!}</small><sup class="text-danger">&#42;</sup>
            <i class="fa fa-question-circle fa-lg suaray-popover" tabindex="0" role="button" data-toggle="suaray-popover" data-trigger="focus" title="Event Start Time" data-content="What time does your event start?"></i>

            <div class="inner-addon right-addon input-group-lg create-event-detail-time-picker">
              <i class="glyphicon create-event-detail glyphicon-time"></i>
              @if(Input::old('startTime'))
                {!! Form::select('startTime', $timeList, Input::old('startTime'), ['class' => 'ng-dirty form-control', 'data-role' => 'startTime']) !!}
              @else
                {!! Form::select('startTime', $timeList, 'Select a Time', ['class' => 'form-control', 'data-role' => 'startTime']) !!}
              @endif
            </div>

            <small class="text-danger">{{ $errors->first('startTime') }}</small>

          </div>

          {{-- Creates AM/PM Button Group --}}
          <div class="col-sm-6 create-event-time-button">

            <div class="btn-group pull-right" data-toggle="buttons">

              {{-- AM Radio Input --}}
              @if(Input::old('startMeridian') === 'am')
                <span class="btn btn-suaray btn-suaray-primary control-label" data-role="amStartMeridian" ng-click="setStartMeridian('am')">
                  {!! Form::radio('startMeridian', 'am', false, ['class' => 'ng-dirty form-control', 'data-role' => 'amStart', 'checked' => 'checked', 'required']) !!}AM
                </span>
              @elseif(Input::old('startMeridian') === 'pm')
                <span class="btn btn-suaray btn-suaray-primary control-label" data-role="amStartMeridian" ng-click="setStartMeridian('am')">
                  {!! Form::radio('startMeridian', 'am', false, ['class' => 'ng-dirty form-control', 'data-role' => 'amStart', 'required']) !!}AM
                </span>
              @else
                <span class="btn btn-suaray btn-suaray-primary control-label" ng-disabled="timeSelected === undefined" data-role="amStartMeridian" ng-click="setStartMeridian('am')">
                  {!! Form::radio('startMeridian', 'am', false, ['class' => 'ng-dirty form-control', 'data-role' => 'amStart', 'required']) !!}AM
                </span>
              @endif

              {{-- PM Radio Input --}}
              @if(Input::old('startMeridian') === 'pm')
                <span class="btn btn-suaray btn-suaray-primary control-label" data-role="pmStartMeridian" ng-click="setStartMeridian('pm')">
                  {!! Form::radio('startMeridian', 'pm', Input::old('startMeridian'), ['class' => 'ng-dirty form-control', 'data-role' => 'pmStart', 'checked' => 'checked', 'required']) !!}PM
                </span>
              @elseif(Input::old('startMeridian') === 'am')
                <span class="btn btn-suaray btn-suaray-primary control-label" data-role="pmStartMeridian" ng-click="setStartMeridian('pm')">
                  {!! Form::radio('startMeridian', 'pm', true, ['class' => 'ng-dirty form-control', 'data-role' => 'pmStart', 'required']) !!}PM
                </span>
              @else
                <span class="btn btn-suaray btn-suaray-primary control-label" ng-disabled="timeSelected === undefined" data-role="pmStartMeridian" ng-click="setStartMeridian('pm')">
                  {!! Form::radio('startMeridian', 'pm', true, ['class' => 'ng-dirty form-control', 'data-role' => 'pmStart', 'required']) !!}PM
                </span>
              @endif

            </div>

          </div>

        </div>

      </div>

      {{-- Event End Date --}}
      <div class="form-group col-sm-5">{{-- END DATE Tag --}}

        {!! Form::label('endDate', 'End Date', ['class' => 'clearfix control-label']) !!}<sup class="text-danger">&#42;</sup>
        <i class="fa fa-question-circle fa-lg suaray-popover" tabindex="0" role="button" data-toggle="suaray-popover" data-trigger="focus" title="Event End Date" data-content="What date does your event end on?"></i>

        {{-- End Date Picker --}}
        <div class="inner-addon right-addon input-group-lg create-event-detail-date-picker">
          <i class="glyphicon create-event-detail glyphicon-calendar"></i>
          @if(Input::old('endDate'))
            {!! Form::text('endDate', Input::old('startMeridian'), ['class' => 'form-control ng-dirty datepicker date-pick', 'data-role' => 'date-picker', 'id' => 'txtToDate', 'data-date-format' => 'mm/dd/yyyy', 'required']) !!}
          @else
            {!! Form::text('endDate', '', ['class' => 'form-control datepicker date-pick', 'data-role' => 'date-picker', 'id' => 'txtToDate', 'data-date-format' => 'mm/dd/yyyy', 'required']) !!}
          @endif
        </div>

        {{ $errors->first('endDate', '<p class="help-block">:message</p>') }}

        <div class="row">

          {{-- End Hour / Minute Input --}}
          <div class="form-group col-sm-6 create-event-time-input">
            {!! Form::label('endTime', 'End Time') !!}<sup class="text-danger">&#42;</sup>
            <i class="fa fa-question-circle fa-lg suaray-popover" tabindex="0" role="button" data-toggle="suaray-popover" data-trigger="focus" title="Event End Time" data-content="What time does your event end? If your not sure, that's ok. Select a time that seems right."></i>

            <div class="inner-addon right-addon input-group-lg create-event-detail-time-picker">
              <i class="glyphicon create-event-detail glyphicon-time"></i>
              @if(Input::old('endTime'))
                {!! Form::select('endTime', $timeList, Input::old('endTime'), ['class' => 'form-control ng-dirty standardEndTime', 'data-role' => 'endTime']) !!}
              @else
                {!! Form::select('endTime', $timeList, '1:00', ['class' => 'form-control standardEndTime', 'data-role' => 'endTime']) !!}
              @endif

            </div>

          </div>

          <small class="text-danger">{{ $errors->first('endTime') }}</small>

          {{-- Creates AM/PM Button Group --}}
          <div class="col-sm-6 create-event-time-button">

            <div class="btn-group pull-right" data-toggle="buttons">

              {{-- AM Radio Input --}}
              @if (Input::old('startMeridian') === 'am')
                <span class="btn btn-suaray btn-suaray-primary control-label" data-role="amEndMeridian" ng-click="setEndMeridian('am')">
                  {!! Form::radio('endMeridian', 'am', Input::old('endMeridian'), ['class' => 'form-control ng-dirty', 'data-role' => 'amEnd', 'required']) !!}AM
                </span>
              @elseif (Input::old('startMeridian') === 'pm')
                <span class="btn btn-suaray btn-suaray-primary control-label" data-role="amEndMeridian" ng-click="setEndMeridian('am')">
                  {!! Form::radio('endMeridian', 'am', false, ['class' => 'form-control ng-dirty', 'data-role' => 'amEnd', 'required']) !!}AM
                </span>
              @else
                <span class="btn btn-suaray btn-suaray-primary control-label" ng-disabled="timeSelected === undefined" data-role="amEndMeridian" ng-click="setEndMeridian('am')">
                  {!! Form::radio('endMeridian', 'am', false, ['class' => 'form-control ng-dirty', 'data-role' => 'amEnd', 'required']) !!}AM
                </span>
              @endif

              {{-- PM Radio Input --}}
              @if (Input::old('startMeridian') === 'pm')
                <span class="btn btn-suaray btn-suaray-primary control-label" data-role="pmEndMeridian" ng-click="setEndMeridian('pm')">
                  {!! Form::radio('endMeridian', 'pm', Input::old('endMeridian'), ['class' => 'form-control ng-dirty', 'data-role' => 'pmEnd', 'required']) !!}PM
                </span>
              @elseif (Input::old('startMeridian') === 'am')
                <span class="btn btn-suaray btn-suaray-primary control-label meridian-label" data-role="pmEndMeridian" ng-click="setEndMeridian('pm')">
                  {!! Form::radio('endMeridian', 'pm', true, ['class' => 'form-control ng-dirty', 'data-role' => 'pmEnd', 'required']) !!}PM
                </span>
              @else
                <span class="btn btn-suaray btn-suaray-primary control-label" ng-disabled="timeSelected === undefined" data-role="pmEndMeridian" ng-click="setEndMeridian('pm')">
                  {!! Form::radio('endMeridian', 'pm', true, ['class' => 'form-control ng-dirty', 'data-role' => 'pmEnd', 'required']) !!}PM
                </span>
              @endif

            </div>

          </div>

        </div>

      </div>

      <div class="form-group col-sm-3 col-sm-offset-1">

        {{-- Recurring Checkbox --}}
        <div class="create-event-recurring-checkbox">
          <label class="control-label" >
            @if(Input::old('recurring'))
              {!! Form::checkbox('recurring', 'recurring', Input::old('recurring'), ['class' => 'ng-dirty', 'data-role' => 'recurringSchedule']) !!}&nbsp;&nbsp;Recurring
            @else
              {!! Form::checkbox('recurring', 'recurring', null, ['data-role' => 'recurringSchedule']) !!}&nbsp;&nbsp;Recurring
            @endif
          </label>
          <i class="fa fa-question-circle fa-lg suaray-popover" tabindex="0" role="button" data-toggle="suaray-popover" data-trigger="focus" title="Recurring Event" data-content="If your event will occur on a regular basis, select this for more options."></i>
        </div>

      </div>

      <div class="" ng-hide="event.repeat.enabled !== true">

        <div class="col-sm-10 col-sm-offset-1">

          {{-- Repeats Input Field --}}
          <div class="form-group col-sm-4">
            {!! Form::label('repeats', 'Repeats', ['class' => 'clearfix control-label']) !!}
            <i class="fa fa-question-circle fa-lg suaray-popover" tabindex="0" role="button" data-toggle="suaray-popover" data-trigger="focus" title="Repeating Cycle" data-content="Will your event recur daily, weekly, monthly, or yearly?"></i>
            @if(Input::old('repeats'))
              {!! Form::select('repeats', $repeatList, Input::old('repeats'), ['class' => 'form-control ng-dirty', 'data-role' => 'repeatInterval']) !!}
            @else
              {!! Form::select('repeats', $repeatList, 'repeat', ['class' => 'form-control', 'data-role' => 'repeatInterval']) !!}
            @endif
            {{ $errors->first('repeats', '<p class="help-block">:message</p>') }}
          </div>

          {{-- 'Repeats Every' Input Field --}}
          <div class="form-group col-sm-2" >
            {!! Form::label('repeatEvery', 'Every', ['class' => 'clearfix control-label']) !!}
            <i class="fa fa-question-circle fa-lg suaray-popover" tabindex="0" role="button" data-toggle="suaray-popover" data-trigger="focus" title="Repeating Interval" data-content="How many cycles apart will your event occur? i.e. 'Repeats Daily every 2 days' will result in your event occuring every other day."></i>
            @if(Input::old('repeatEvery'))
              {!! Form::selectRange('repeatEvery', 1, 30, Input::old('repeatEvery'), ['class' => 'form-control ng-dirty', 'data-role' => 'repeatIntervalFrequency']) !!}
            @else
              {!! Form::selectRange('repeatEvery', 1, 30, '1', ['class' => 'form-control', 'data-role' => 'repeatIntervalFrequency']) !!}
            @endif
            {{ $errors->first('repeatEvery', '<p class="help-block">:message</p>') }}
          </div>

          {{-- Shows if Daily is picked --}}
          <div class="form-group col-sm-2 create-event-repeat-every-interim" ng-show="repeatInterval === 'daily'">
            <label class="clearfix control-label">Day<span ng-show="repeatIntervalFrequency > 1">s</span></label>
          </div>

          {{-- Shows if Weekly is picked --}}
          <div class="form-group col-sm-2 create-event-repeat-every-interim" ng-show="repeatInterval === 'weekly'">
            <label class="clearfix control-label">Week<span ng-show="repeatIntervalFrequency > 1">s</span></label>
          </div>

          {{-- Shows if Monthly is picked --}}
          <div class="form-group col-sm-2 create-event-repeat-every-interim" ng-show="repeatInterval === 'monthly'">
            <label class="clearfix control-label">Month<span ng-show="repeatIntervalFrequency > 1">s</span></label>
          </div>

          {{-- Shows if Yearly is picked --}}
          <div class="form-group col-sm-2 create-event-repeat-every-interim" ng-show="repeatInterval === 'yearly'">
            <label class="clearfix control-label">Year<span ng-show="repeatIntervalFrequency > 1">s</span></label>
          </div>

        </div>

        <div class="form-group col-sm-10 col-sm-offset-1" ng-show="repeatInterval === 'weekly'">

          <label class="control-label col-sm-12" >Repeats on:</label>

          <ul class="create-event-recurring-days-of-week">
            <li><label class="control-label" >{!! Form::checkbox('sun', 'sun', Input::old('sun'), ['data-role' => 'day-of-week-sun']) !!}&nbsp;&nbsp;Su</label></li>
            <li><label class="control-label" >{!! Form::checkbox('mon', 'mon', Input::old('mon'), ['data-role' => 'day-of-week-mon']) !!}&nbsp;&nbsp;Mo</label></li>
            <li><label class="control-label" >{!! Form::checkbox('tue', 'tue', Input::old('tue'), ['data-role' => 'day-of-week-tue']) !!}&nbsp;&nbsp;Tu</label></li>
            <li><label class="control-label" >{!! Form::checkbox('wed', 'wed', Input::old('wed'), ['data-role' => 'day-of-week-wed']) !!}&nbsp;&nbsp;We</label></li>
            <li><label class="control-label" >{!! Form::checkbox('thu', 'thu', Input::old('thu'), ['data-role' => 'day-of-week-thu']) !!}&nbsp;&nbsp;Th</label></li>
            <li><label class="control-label" >{!! Form::checkbox('fri', 'fri', Input::old('fri'), ['data-role' => 'day-of-week-fri']) !!}&nbsp;&nbsp;Fr</label></li>
            <li><label class="control-label" >{!! Form::checkbox('sat', 'sat', Input::old('sat'), ['data-role' => 'day-of-week-sat']) !!}&nbsp;&nbsp;Sa</label></li>
          </ul>

          {!! Form::hidden('daysOfWeek', '', ['data-role' => 'daysOfWeek']) !!}

        </div>

        {{--  <div class="form-group col-sm-10 col-sm-offset-1" ng-show="repeatInterval === 'monthly'">

          <label class="control-label col-sm-12" >Repeats Monthly</label>

          <ul class="create-event-recurring-repeat-by">
            <li><label class="control-label" >{!! Form::radio('repeatsBy', 'dayOfMonth', null, ['ng-model' => 'event.repeat.months', 'ng-init' => 'checked=false']) !!}&nbsp;&nbsp;Day of the Month</label></li>
            <li><label class="control-label" >{!! Form::radio('repeatsBy', 'dayOfWeek', null, ['ng-model' => 'event.repeat.months', 'ng-init' => 'checked=false']) !!}&nbsp;&nbsp;Day of the Week</label></li>
          </ul>

        </div> --}}

        {{-- If user chooses to set an end time for recurring event --}}
        <div class="form-group col-sm-10 col-sm-offset-1">

          <div class="form-group col-sm-6" >

            {!! Form::label('endFinalDate', 'Final Date', ['class' => 'clearfix control-label', 'data-role' => 'finalEndDate']) !!}<span class="">(optional)</span>
            <i class="fa fa-question-circle fa-lg suaray-popover" tabindex="0" role="button" data-toggle="suaray-popover" data-trigger="focus" title="Final Event Date" data-content="This is is the last day your event will ever occur. It should match the final event's 'End Date' if it will be different than the final 'Start Date.'"></i>

            {{-- Final Date Picker --}}
            <div class="inner-addon right-addon input-group-lg create-event-detail-date-picker">
              {{-- Class => "endFinaldate" should not be removed - Used in selector for function due to data-role being used for other functionality --}}

              <i class="glyphicon create-event-detail glyphicon-calendar"></i>
              @if(Input::old('endFinalDate'))
                {!! Form::text('endFinalDate', Input::old('endFinalDate'), ['class' => 'form-control ng-dirty datepicker date-pick endFinalDate', 'data-role' => 'date-picker', 'data-date-format' => 'mm/dd/yyyy']) !!}
              @else
                {!! Form::text('endFinalDate', '', ['class' => 'form-control datepicker date-pick endFinalDate', 'data-role' => 'date-picker', 'id' => 'txtFinalDate', 'data-date-format' => 'mm/dd/yyyy']) !!}
              @endif

            </div>
            <button type="button" class="btn btn-suaray btn-suaray-warning" name="button" ng-click="clearEndFinalDate()" ng-if="finalEndTimeSelected === true">Clear Final End Date</button>

            {!! Form::hidden('endFinalTime', Input::old('endFinalTime'), ['data-role' => 'endFinalTime']) !!}
            {!! Form::hidden('endFinalMeridian', Input::old('endFinalMeridian'), ['data-role' => 'endFinalMeridian']) !!}

          </div>

        </div>

      </div>

      {{-- BEGIN 'Type of Event' Section | Tags and minimal other traits --}}
      <h3 class="col-sm-12 create-event-form-main-title">Type of Event</h3>

      <div class="form-group col-sm-4 col-sm-offset-1">

        {!! Form::label('category1', 'Category', ['class' => 'clearfix control-label']) !!}<sup class="text-danger">&#42;</sup>
        <i class="fa fa-question-circle fa-lg suaray-popover" tabindex="0" role="button" data-toggle="suaray-popover" data-trigger="focus" title="Event Category" data-content="What category best describes your event?"></i>
        @if(Input::old('category1'))
          {!! Form::select('category1', $categories, Input::old('category1'), ['class' => 'form-control ng-dirty create-event-category-selector', 'data-role' => 'eventCategory']) !!}
        @else
          {!! Form::select('category1', $categories, $categories, ['class' => 'form-control create-event-category-selector', 'data-role' => 'eventCategory']) !!}
        @endif

      </div>

      {{-- Tags Input Field --}}
      <div class="form-group col-sm-6">
        {!! Form::label('tags', 'Tags', ['class' => 'clearfix control-label']) !!}<sup class="text-danger">&#42;</sup><sup class="text-danger">&dagger;</sup>
        <i class="fa fa-question-circle fa-lg suaray-popover" tabindex="0" role="button" data-toggle="suaray-popover" data-trigger="focus" title="Event Tags" data-content="Use up to five words to describe your event here. For instance, if you selected 'Festival' for your Category, add some other terms such as 'food' or 'music' to make your event easier to find."></i>

        {{-- List out the tags for the event --}}
        @if (Input::old('tags'))
          {!! Form::text('tags', Input::old('tags'), ['class' => 'form-control ng-dirty', 'data-role' => 'tagsInput', 'required']) !!}
        @else
          {!! Form::text('tags', $tagConcat, ['class' => 'form-control', 'placeholder' => 'Tags...', 'data-role' => 'tagsInput', 'multiple' => 'multiple', 'required']) !!}
        @endif

        {{ $errors->first('tags', '<p class="help-block">:message</p>') }}
      </div>

      {{-- BEGIN Checkbox group --}}
      <div class="form-group col-sm-10 col-sm-offset-1">


        {!! Form::label('features', 'Features', ['class' => 'clearfix control-label']) !!}
        <i class="fa fa-question-circle fa-lg suaray-popover" tabindex="0" role="button" data-toggle="suaray-popover" data-trigger="focus" title="Event Features" data-content="These features let others know if the event will be indoor, outdoor, and what age range it is suitable for."></i>

        <ul class="create-event-features">

          <li>
            <div class="age-select-dropdown-create">

              {{-- User clicks checkbox to display age appropriate options --}}
              <input type="checkbox" data-role="isAgeSelect" class="age-select"/>

              <label class="age-select">Age Group
                <span class="caret age-caret"></span>
              </label>

              {{-- Begin dropdown checkbox age options --}}
              <section class="age-select">

                <div class="age-select">

                  {{-- If the event is 13 + and set true --}}
                  @if (Input::old('isAge13'))
                    {!! Form::checkbox('isAge13', '1', Input::old('isAge13'), ['data-role' => 'isAge13', 'id' => 'isAge13', 'ng-init' => 'event.isAge13=true', 'class' => 'age-select-checkbox']) !!}
                    <label class="age-select">13 +</label>
                  @else
                    {!! Form::checkbox('isAge13', '1', null, ['data-role' => 'isAge13', 'id' => 'isAge13', 'class' => 'age-select-checkbox']) !!}
                    <label class="age-select" >13 +</label>
                  @endif

                </div>

                <div class="age-select">

                  {{-- If the event is 16 + and set true --}}
                  @if (Input::old('isAge16'))
                    {!! Form::checkbox('isAge16', '1', Input::old('isAge16'), ['data-role' => 'isAge16', 'id' => 'isAge16', 'ng-init' => 'event.isAge16=true', 'class' => 'age-select-checkbox']) !!}
                    <label class="age-select">16 +</label>
                  @else
                    {!! Form::checkbox('isAge16', '1', null, ['data-role' => 'isAge16', 'id' => 'isAge16', 'class' => 'age-select-checkbox']) !!}
                    <label class="age-select" >16 +</label>
                  @endif

                </div>

                <div class="age-select">

                  {{-- If the event is 18 + and set true --}}
                  @if (Input::old('isAge18'))
                    {!! Form::checkbox('isAge18', '1', Input::old('isAge18'), ['data-role' => 'isAge18', 'id' => 'isAge18', 'ng-init' => 'event.isAge18=true', 'class' => 'age-select-checkbox']) !!}
                    <label class="age-select">18 +</label>
                  @else
                    {!! Form::checkbox('isAge18', '1', null, ['data-role' => 'isAge18', 'id' => 'isAge18', 'class' => 'age-select-checkbox']) !!}
                    <label class="age-select" >18 +</label>
                  @endif

                </div>

                <div class="age-select">

                  {{-- If the event is adult and set true --}}
                  @if (Input::old('isAge21'))
                    {!! Form::checkbox('isAge21', '1', Input::old('isAge21'), ['data-role' => 'isAge21', 'id' => 'isAge21', 'ng-init' => 'event.isAge21=true', 'class' => 'age-select-checkbox']) !!}
                    <label class="age-select">21 +</label>
                  @else
                    {!! Form::checkbox('isAge21', '1', null, ['data-role' => 'isAge21', 'id' => 'isAge21', 'class' => 'age-select-checkbox']) !!}
                    <label class="age-select" >21 +</label>
                  @endif

                </div>

                <div class="age-select">

                  {{-- If the event is all ages and set true --}}
                  @if (Input::old('isAge0'))
                    {!! Form::checkbox('isAge0', '1', Input::old('isAge0'), ['data-role' => 'isAge0', 'id' => 'isAge0', 'ng-init' => 'event.isAge0=true', 'class' => 'age-select-checkbox']) !!}
                    <label class="age-select">All Ages</label>
                  @else
                    {!! Form::checkbox('isAge0', '1', null, ['data-role' => 'isAge0', 'id' => 'isAge0', 'class' => 'age-select-checkbox']) !!}
                    <label class="age-select" >All Ages</label>
                  @endif

                </div>

              </section>

            </div>
          </li>

          {{-- If the event is indoor and set true --}}
          @if (Input::old('isIndoor'))
            <li>
              <label class="control-label" >
                {!! Form::checkbox('isIndoor', '1', true, ['data-role' => 'isIndoor', 'id' => 'isIndoor', 'ng-init' => 'event.isIndoor=true']) !!}&nbsp;&nbsp;Indoor
              </label>
            </li>
          @else
            <li>
              <label class="control-label" >
                {!! Form::checkbox('isIndoor', '1', null, ['data-role' => 'isIndoor', 'id' => 'isIndoor']) !!}&nbsp;&nbsp;Indoor
              </label>
            </li>
          @endif

          {{-- If the event is outdoor and set true --}}
          @if (Input::old('isIndoor'))
            <li>
              <label class="control-label">
                {!! Form::checkbox('isOutdoor', '1', true, ['data-role' => 'isOutdoor', 'id' => 'isOutdoor', 'ng-init' => 'event.isOutdoor=true']) !!}&nbsp;&nbsp;Outdoor
              </label>
            </li>
          @else
            <li>
              <label class="control-label">
                {!! Form::checkbox('isOutdoor', '1', null, ['data-role' => 'isOutdoor', 'id' => 'isOutdoor']) !!}&nbsp;&nbsp;Outdoor
              </label>
            </li>
          @endif

        </ul>

      </div>

      {{-- 'NEXT >' Button to continue along form --}}
      <div class="form-group text-right">

        <div class="col-sm-10 col-sm-offset-1">
          <button class="btn btn-suaray btn-suaray-primary btn-lg pull-right" data-role="test-button" ng-click="tab = 'location'">Next <span class="glyphicon glyphicon-chevron-right"></span></button>
        </div>

      </div>

    </div>

  </div>
