<?php if (!isset($location)) $location = 'create_event_location'; ?>
<?php if (!isset($locationLocked)) $locationLocked = true; ?>

  <div class="create-event-form-box">

    <div class="row">

      {{-- BEGIN Form Elements | Location --}}
      <h3 class="col-sm-12 create-event-form-main-title">Event Location</h3>

      {{-- Venue Name --}}
      <div class="form-group col-sm-10 col-sm-offset-1 @if ($errors->has('venueName')) has-error @endif }}">
        {!! Form::label('venueName', 'Venue Name', ['class' => 'clearfix control-label']) !!}
        <i class="fa fa-question-circle fa-lg suaray-popover" tabindex="0" role="button" data-toggle="suaray-popover" data-trigger="focus" title="Event Venue" data-content="The venue name is the actual name of the location the event will be taking place. Venue examples could be anything from 'The House of Blues' to 'The Brown's House.'"></i>

        @if(Input::old('venueName'))
          {!! Form::text('venueName', Input::old('venueName'), ['class' => 'form-control ng-dirty', 'maxlength' => '74', 'data-role' => 'eventVenueName', 'placeholder' => 'Name of Venue']) !!}
        @else
          {!! Form::text('venueName', null, ['class' => 'form-control', 'maxlength' => '74', 'data-role' => 'eventVenueName', 'placeholder' => 'Name of Venue']) !!}
        @endif
      </div>

      {{-- Address --}}
      <div class="form-group col-sm-10 col-sm-offset-1 @if ($errors->has('address1')) has-error @endif }}">
        {!! Form::label('address1', 'Address', ['class' => 'clearfix control-label']) !!} <span class="text-danger">&#42;</span>
        <i class="fa fa-question-circle fa-lg suaray-popover" tabindex="0" role="button" data-toggle="suaray-popover" data-trigger="focus" title="Event Address" data-content="Physical Address of the event"></i>

        @if(Input::old('address1'))
          {!! Form::text('address1', Input::old('address1'), ['class' => 'form-control ng-dirty', 'maxlength' => '50', 'data-role' => 'eventAddress', 'required', 'placeholder' => 'Building Number / Street Name / Unit Number']) !!}
        @else
          {!! Form::text('address1', null, ['class' => 'form-control', 'maxlength' => '50', 'data-role' => 'eventAddress', 'required', 'placeholder' => 'Building Number / Street Name / Unit Number']) !!}
        @endif

        @if(Session::has('address1'))
          <p class="edit-tickets-warn-text">{{ Session::get('address1') }}</p>
        @endif
      </div>

      {{-- City --}}
      <div class="form-group col-sm-4 col-sm-offset-1 @if ($errors->has('city')) has-error @endif }}">
        {!! Form::label('city', 'City', ['class' => 'clearfix control-label']) !!}
        <i class="fa fa-question-circle fa-lg suaray-popover" tabindex="0" role="button" data-toggle="suaray-popover" data-trigger="focus" title="Event City" data-content="The city the event will take place in."></i>

        @if(Input::old('city'))
          {!! Form::text('city', Input::old('city'), ['class' => 'form-control ng-dirty', 'maxlength' => '30', 'data-role' => 'eventCity', 'placeholder' => 'City Name']) !!}
        @else
          {!! Form::text('city', null, ['class' => 'form-control', 'maxlength' => '30', 'data-role' => 'eventCity', 'placeholder' => 'City Name']) !!}
        @endif
      </div>

      {{-- State --}}
      <div class="form-group col-sm-3 @if ($errors->has('state')) has-error @endif }}">
        {!! Form::label('state', 'State', ['class' => 'clearfix control-label']) !!}
        <i class="fa fa-question-circle fa-lg suaray-popover" tabindex="0" role="button" data-toggle="suaray-popover" data-trigger="focus" title="Event State" data-content="The state the event will take place in."></i>
        @if(Input::old('state'))
          {!! Form::text('state', Input::old('state'), ['class' => 'form-control ng-dirty', 'data-role' => 'eventState', 'placeholder' => 'State Name']) !!}
        @else
          {!! Form::text('state', null, ['class' => 'form-control', 'data-role' => 'eventState', 'placeholder' => 'State Name']) !!}
        @endif

      </div>

      {{-- Zip Code --}}
      <div class="form-group col-sm-3 @if ($errors->has('zipcode')) has-error @endif }}">
        {!! Form::label('zipcode', 'Zip Code', ['class' => 'clearfix control-label']) !!} <span class="text-danger">&#42;</span>
        <i class="fa fa-question-circle fa-lg suaray-popover" tabindex="0" role="button" data-toggle="suaray-popover" data-trigger="focus" title="Event Zipcode" data-content="The zipcode the event will take place in."></i>

        @if(Input::old('zipcode'))
          {!! Form::text('zipcode', Input::old('zipcode'), ['class' => 'form-control ng-dirty', 'id' => 'zip', 'data-role' => 'zipcode', 'maxlength' => '5', 'data-role' => 'eventZipcode', 'required', 'placeholder' => 'Zipcode']) !!}
        @else
          {!! Form::text('zipcode', null, ['class' => 'form-control', 'id' => 'zip', 'data-role' => 'zipcode', 'maxlength' => '5', 'data-role' => 'eventZipcode', 'required', 'placeholder' => 'Zipcode']) !!}
        @endif

        @if(Session::has('zipcode'))
          <p class="edit-tickets-warn-text">{{ Session::get('zipcode') }}</p>
        @endif
      </div>

      <div class="col-sm-10 col-sm-offset-1">
        <button class="btn btn-suaray btn-suaray-primary btn-lg pull-right" ng-click="tab = 'options'">Next <span class="glyphicon glyphicon-chevron-right"></span></button>
      </div>

    </div>

  </div>
