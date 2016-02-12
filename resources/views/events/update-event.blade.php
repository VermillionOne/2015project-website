@extends('layouts.master')

@section('javascript-includes')
  <script src="{{ ViewHelper::asset('assets/dist/lib-events-create.js') }}"></script>
@stop

@section('content')

<div class="tickets-bg" data-ng-controller="EventsCreateController">

  {{-- Session Message --}}
  @include('includes.sessionStatus')

  <div class="results-body">
    <div class="container">

      {{-- Create new ticket header and route back to tickets index --}}
      <div class="dashboard-titles">

        <div class="row">

          <div class="col-md-8 col-sm-8 col-xs-8">
            <div class="results-title-txt">Event Details</div>
          </div>

          <div class="col-md-4 col-sm-4 col-xs-4">
            @include('accounts.dashboard.includes.editnav')
          </div>

        </div>

      </div>

      <div class="row">

        <div class="create-event-form-box">
          {{-- Schedules update section --}}
          <div class = "event-detail-tickets-col padding-ticket">
            <div class="row">

              {!! ViewHelper::formModel($event, ['route' => ['events.doUpdateEvent', 'id' => $event['id']], 'method' => 'PUT']) !!}

              {{-- Event Title --}}
              <div class="form-group col-md-10 col-md-offset-1">
                {!! Form::label('title', 'Title', ['class' => 'clearfix control-label title-padding']) !!}
                {{-- If the event is available to edit then preload it --}}
                {!! Form::text('title', Input::old('title'), ['class' => 'form-control', 'maxlength' => '74']) !!}
              </div>

              {{-- Event Description | Free --}}
              <div class="form-group col-md-10 col-md-offset-1">
                {!! Form::label('description', 'Description', ['class' => 'clearfix control-label']) !!} <sup class="text-danger">&#42;</sup>
                {!! Form::textarea('description', Input::old('description'), ['class' => 'form-control icon-size', 'rows' => '6']) !!}
              </div>

              {{-- Event tags --}}
              <div class="form-group col-md-10 col-md-offset-1">
                {!! Form::label('tags', 'Tags', ['class' => 'clearfix control-label']) !!}

                {{-- If the event is available to edit then preload it --}}
                {!! Form::text('tags', $tagConcat, ['class' => 'form-control', 'rows' => '3', 'maxlength' => '75']) !!}
              </div>

              {{-- Venue Name --}}
              <div class="form-group col-md-10 col-md-offset-1">
                {!! Form::label('venueName', 'Venue Name', ['class' => 'clearfix control-label']) !!}

                {!! Form::text('venueName', Input::old('venueName'), ['class' => 'form-control', 'maxlength' => '75']) !!}
              </div>

              {{-- Address --}}
              <div class="form-group col-md-10 col-md-offset-1">
                {!! Form::label('location', 'Address', ['class' => 'clearfix control-label']) !!}

                {!! Form::text('address1', Input::old('address1'), ['class' => 'form-control']) !!}

              </div>

              {{-- City --}}
              <div class="form-group col-md-4 col-md-offset-1">
                {!! Form::label('city', 'City', ['class' => 'clearfix control-label']) !!}

                {!! Form::text('city', Input::old('city'), ['class' => 'form-control feedback-name']) !!}

              </div>

              {{-- State --}}
              <div class="form-group col-md-3">
                {!! Form::label('state', 'State', ['class' => 'clearfix control-label']) !!}

                {!! Form::text('state', Input::old('state'), ['class' => 'feedback-name form-control']) !!}

              </div>

              {{-- Zip Code --}}
              <div class="form-group col-md-3">
                {!! Form::label('zipcode', 'Zip Code', ['class' => 'clearfix control-label']) !!}

                {!! Form::text('zipcode', Input::old('zipcode'), ['class' => 'form-control']) !!}

              </div>

              {{-- BEGIN Checkbox group --}}
              <div class="form-group col-md-10 col-md-offset-1">

                {!! Form::label('features', 'Features', ['class' => 'clearfix control-label']) !!}

                <ul class="edit-details-list-options">

                  <li>

                    <div class="age-select-dropdown-create">

                      {{-- User clicks checkbox to display age appropriate options --}}
                      <input type="checkbox" class="age-select"/>

                      <label class="age-select">Age Group
                        <span class="caret age-caret"></span>
                      </label>

                      {{-- Begin dropdown checkbox age options --}}
                      <section class="age-select">

                        <div class="age-select">

                          {{-- If the event is 13 + and set true --}}
                          @if (Input::old('isAge13'))
                            {!! Form::checkbox('isAge13', '1', Input::old('isAge13'), ['class' => 'age-select-checkbox']) !!}
                            <label class="age-select">13 +</label>
                          @else
                            {!! Form::checkbox('isAge13', '1', null, ['class' => 'age-select-checkbox']) !!}
                            <label class="age-select" >13 +</label>
                          @endif

                        </div>

                        <div class="age-select">

                          {{-- If the event is 16 + and set true --}}
                          @if (Input::old('isAge16'))
                            {!! Form::checkbox('isAge16', '1', Input::old('isAge16'), ['class' => 'age-select-checkbox']) !!}
                            <label class="age-select">16 +</label>
                          @else
                            {!! Form::checkbox('isAge16', '1', null, ['class' => 'age-select-checkbox']) !!}
                            <label class="age-select" >16 +</label>
                          @endif

                        </div>

                        <div class="age-select">

                          {{-- If the event is 18 + and set true --}}
                          @if (Input::old('isAge18'))
                            {!! Form::checkbox('isAge18', '1', Input::old('isAge18'), ['class' => 'age-select-checkbox']) !!}
                            <label class="age-select">18 +</label>
                          @else
                            {!! Form::checkbox('isAge18', '1', null, ['class' => 'age-select-checkbox']) !!}
                            <label class="age-select" >18 +</label>
                          @endif

                        </div>

                        <div class="age-select">

                          {{-- If the event is adult and set true --}}
                          @if (Input::old('isAge21'))
                            {!! Form::checkbox('isAge21', '1', Input::old('isAge21'), ['class' => 'age-select-checkbox']) !!}
                            <label class="age-select">21 +</label>
                          @else
                            {!! Form::checkbox('isAge21', '1', null, ['class' => 'age-select-checkbox']) !!}
                            <label class="age-select" >21 +</label>
                          @endif

                        </div>

                        <div class="age-select">

                          {{-- If the event is all ages and set true --}}
                          @if (Input::old('isAge0'))
                            {!! Form::checkbox('isAge0', '1', Input::old('isAge0'), ['class' => 'age-select-checkbox']) !!}
                            <label class="age-select">All Ages</label>
                          @else
                            {!! Form::checkbox('isAge0', '1', null, ['class' => 'age-select-checkbox']) !!}
                            <label class="age-select" >All Ages</label>
                          @endif

                        </div>

                      </section>

                    </div>

                  </li>

                  <li><label class="control-label" >{!! Form::checkbox('isIndoor', 1, Input::old('isIndoor'), null, ['ng-model' => 'event.isIndoor', 'id' => 'isIndoor']) !!}&nbsp;&nbsp;Indoor </label></li>
                  <li><label class="control-label" >{!! Form::checkbox('isOutdoor', 1, Input::old('isOutdoor') ,null, ['ng-model' => 'event.isOutdoor', 'id' => 'isOutdoor']) !!}&nbsp;&nbsp;Outdoor </label></li>

                </ul>

              </div>

              <div class="col-md-10 col-md-offset-1">
                <button class="btn btn-suaray btn-suaray-primary btn-lg pull-right" type="submit" name="save">Save</button>
              </div>

              {!! Form::close() !!}

              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@stop
