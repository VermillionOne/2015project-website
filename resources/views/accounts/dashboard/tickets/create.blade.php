@extends('layouts.master')

@section('content')

<div class="tickets-bg" ng-controller="FeedbackTabController">

  {{-- Session Message --}}
  @include('includes.sessionStatus')

  <div class="results-body">
    <div class = "container">

      <div class="dashboard-titles">

        <div class="row">

          {{-- Create new ticket header and route back to tickets index --}}
          <div class="col-md-8 col-sm-6 col-xs-12">

            <h2 class="results-title-txt">Add New Ticket</h2>

          </div>

          {{-- Back to tickets link --}}
          <div class="col-md-4 col-sm-6 hidden-xs">

            <a href="{{ route('tickets.index', ['id' => $events]) }}" class="clear-form search-clear">Back To Tickets</a>

          </div>

        </div>

      </div>

        <div class="row">
          <div class="section-card">

            {{-- Event Ticket create section --}}
            <div class = "event-detail-tickets-col padding-ticket">
              <div class="row">

                {{-- Checkbox to toggle new ticket or new reservation --}}
                <div class="checkbox tickets-update-container col-sm-10 col-sm-offset-1">

                  <label class="tickets-create-checkbox">
                    <input type="checkbox" ng-model="checked">
                    Reservation
                  </label>

                </div>

                <div ng-show="checked">

                  {!! ViewHelper::formOpen(['route' => 'tickets.store']) !!}

                  @if (\Auth::check())
                    {!! Form::text('userId', \Auth::user()->id , ['class' => 'hidden']) !!}
                  @endif

                  {!! Form::text('eventId', $events, ['class' => 'hidden']) !!}

                  {!! Form::hidden('is_reservation', 1) !!}

                  {!! Form::hidden('is_enabled', 1) !!}
                  {{-- Amount and Inventory set to 0, required data --}}
                  {!! Form::hidden('amount', 0) !!}
                  {!! Form::hidden('inventory', 0) !!}

                  <div class = " form-group col-md-10 col-md-offset-1 ">

                    {{-- Reservation Type --}}
                    <div class = "ticket-type-new">
                      {!! Form::label('name', 'Reservation Name', ['class' => 'clearfix control-label']) !!}
                      {!! Form::text('name', Input::old('name'), ['class' => 'form-control', 'maxlength' => '50']) !!}
                      @if(Session::has('name'))
                        <p class="edit-tickets-warn-text">{{ Session::get('name') }}</p>
                      @endif
                    </div>

                    {{-- Reservation Description --}}
                    <div class = "ticket-type-new ticket-create-margin">
                      {!! Form::label('description', 'Reservation Description' , ['class' => 'control-label']) !!}
                      {!! Form::textarea('description', Input::old('description') , ['class' => 'form-control form-control', 'size' => '35x3']) !!}
                    </div>

                    {{-- Reservation confirmation --}}
                    <div class = "ticket-type-new ticket-create-margin">
                      {!! Form::label('confirmation_message', 'Confirmation Message' , ['class' => 'control-label']) !!}
                      {!! Form::textarea('confirmation_message', Input::old('confirmation_message') , ['class' => 'form-control form-control', 'size' => '35x3']) !!}
                    </div>

                    <div class="form-group col-sm-3 col-sm-offset-9" style="padding: 0;">
                      <button class="btn ticket-create-margin btn-suaray btn-suaray-positive btn-block pull-right" type="submit">Save</button>
                    </div>

                  </div>
                  {!! Form::close() !!}

                </div>

                {{-- Initially shows ticket view, hidden when checkbox clicked --}}
                <div ng-hide="checked">

                  {!! ViewHelper::formOpen(['route' => 'tickets.store']) !!}

                  @if (\Auth::check())
                    {!! Form::text('userId', \Auth::user()->id , ['class' => 'hidden']) !!}
                  @endif

                  {!! Form::text('eventId', $events, ['class' => 'hidden']) !!}

                  {!! Form::hidden('is_reservation', 0) !!}

                  {{-- Ticket Title and description --}}
                  <div class = " form-group col-md-10 col-md-offset-1 ">

                    {{-- Ticket Type --}}
                    <div class = "ticket-type-new">
                      {!! Form::label('name', 'Ticket Name', ['class' => 'clearfix control-label']) !!}
                      {!! Form::text('name', Input::old('name'), ['class' => 'form-control', 'maxlength' => '50']) !!}
                      @if(Session::has('name'))
                        <p class="edit-tickets-warn-text">{{ Session::get('name') }}</p>
                      @endif
                    </div>

                    {{-- Ticket Title --}}
                    <div class = "ticket-type-new ticket-create-margin">
                      {!! Form::label('description', 'Description' , ['class' => 'control-label']) !!}
                      {!! Form::textarea('description', Input::old('description') , ['class' => 'form-control form-control', 'size' => '35x3']) !!}
                    </div>

                    {{-- Event Start Date --}}
                    <div class="row ticket-edit ticket-create-margin">
                      <div class="col-md-6 col-sm-6 col-xs-12 start-end-width ticket-type-new @if ($errors->has('startsAt')) has-error @endif }}">

                        {{-- START DATE Tag --}}
                        {!! Form::label('startsAt', 'Start Date*', ['class' => 'clearfix control-label']) !!}

                        {{-- Start Date Picker --}}
                        <div class="inner-addon right-addon input-group-lg create-event-detail-date-picker">
                          <i class="glyphicon create-event-detail glyphicon-calendar"></i>
                           {!! Form::text('startsAt', Input::old('startsAt') , ['class' => 'form-control datepicker', 'data-role' => 'date-picker', 'placeholder' => 'mm/dd/yyyy', 'data-date-format' => 'mm/dd/yyyy']) !!}
                        </div>

                        {{ $errors->first('startsAt', '<p class="help-block">:message</p>') }}

                      </div>

                      {{-- Event End Date --}}
                      <div class="col-md-6 col-sm-6 col-xs-12 start-width ticket-type-new @if ($errors->has('endsAt')) has-error @endif }}">

                        {{-- END DATE Tag --}}
                        {!! Form::label('endsAt', 'End Date*', ['class' => 'clearfix control-label']) !!}

                        {{-- End Date Picker --}}
                        <div class="inner-addon right-addon input-group-lg create-event-detail-date-picker">
                          <i class="glyphicon create-event-detail glyphicon-calendar"></i>
                          {!! Form::text('endsAt', Input::old('endsAt') ,  ['class' => 'form-control datepicker', 'data-role' => 'date-picker', 'placeholder' => 'mm/dd/yyyy', 'data-date-format' => 'mm/dd/yyyy']) !!}
                        </div>

                        {{ $errors->first('endsAt', '<p class="help-block">:message</p>') }}

                      </div>
                    </div>

                    <div class="row">
                      <div class="col-md-10 col-md-offset-1">
                        <p class="edit-tickets-warn-text">*If the Start/End Date fields are left empty, the ticket type will always be present.</p>
                      </div>
                    </div>

                    <div class="row ticket-edit ticket-create-margin">
                      <div class="col-md-3 col-sm-3 col-xs-12 ticket-type-new mobile-amount-inventory left-list-padding">
                        {!! Form::label('amount', 'Ticket Price' , ['clearfix control-label']) !!}
                        {!! Form::text('amount', Input::old('amount') , ['class' => 'form-control form-control no-negatives']) !!}
                        @if(Session::has('amount'))
                          <p class="edit-tickets-warn-text">{{ Session::get('amount') }}</p>
                        @endif
                      </div>

                      <div class="col-md-3 col-sm-3 col-xs-12 edit-ticket ticket-type-new mobile-amount-inventory">
                        {!! Form::label('inventory', 'Inventory', ['class' => 'control-label ']) !!}
                        {!! Form::text('inventory', Input::old('inventory'), ['class' => 'form-control form-control edit-tickets-placeholder-align no-negatives int-only']) !!}
                        @if(Session::has('inventory'))
                          <p class="edit-tickets-warn-text">{{ Session::get('inventory') }}</p>
                        @endif
                      </div>

                      <div class="col-md-3 col-sm-3 col-xs-6 edit-ticket ticket-type-new mobile-enable-btn">

                        <div class="btn-group edit-tickets-inventory-buttons update-btn mobile-margin" data-toggle="buttons">

                          {{-- Ticket Enable Radio Input --}}
                          <span class="btn btn-enable-padding btn-suaray btn-suaray-primary control-label">
                            {!! Form::radio('isEnabled', '1', null, ['class' => 'form-control', 'ng-model' => 'ticketsInventories.isEnabled', 'required' => 'required']) !!}
                          Enable </span>

                          {{-- Ticket Disable Input --}}
                          <span class="btn btn-enable-padding btn-suaray btn-suaray-primary control-label">
                            {!! Form::radio('isEnabled', '0', null, ['class' => 'form-control', 'ng-model' => 'ticketsInventories.isEnabled', 'required' => 'required']) !!}
                          Disable </span>

                        </div>

                      </div>

                      <div class="col-md-3 col-sm-3 col-xs-6 edit-more ticket-type-new mobile-width right-list-padding">
                        <div class="update-padding">
                          <button class="btn btn-suaray btn-suaray-positive btn-block pull-right" type="submit">Save</button>
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

</div>
@stop
