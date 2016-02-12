@extends('layouts.master')

@section('content')

<div class="tickets-bg" ng-controller="DashboardController">

  {{-- Session Message --}}
  @include('includes.sessionStatus')

  <div class="results-body tickets-create">

    <div class = "container">

      <div class="dashboard-titles">

        <div class="row">

          {{-- Edit ticket header and route back to tickets index --}}
          <div class="col-md-3 col-sm-3 col-xs-12">

            {{-- Show appropriate header based on ticket type --}}
            @if ($ticket['isReservation'] === true)
              <h2 class="results-title-txt">Edit Reservation</h2>
            @else
              <h2 class="results-title-txt">Edit Ticket</h2>
            @endif

          </div>

          {{-- Back to tickets --}}
          <div class="col-md-9 col-sm-9 hidden-xs">

            <a href="{{ route('tickets.index', ['id' => $ticket['eventId']]) }}" type="button" class=" poll-default-text pull-right btn btn-sm btn-suaray btn-suaray-plain">Back To Tickets</a>

            <a href="{{ url(route('payment')) }}" type="button" class="pull-right btn btn-sm btn-suaray btn-suaray-plain">Edit Payment Account</a>

            <p class="pull-right feature-notification">Click here to create your payment account. <span class="fa fa-long-arrow-right"></span></p>

          </div>

        </div>

      </div>

			<div class="row">
        <div class="section-card">

          {{-- Event Ticket section --}}
					<div class = "event-detail-tickets-col padding-ticket">
		        <div class="row">

              {{-- If created as reservation --}}
              @if ($ticket['isReservation'] === true)

                {!! ViewHelper::formModel($ticket, [ 'route' => ['tickets.update', 'id' => $ticket['id']]]) !!}

                  {!! Form::hidden('is_reservation', 1) !!}
                  {!! Form::hidden('eventId', $ticket['eventId']) !!}
                  {!! Form::hidden('id', Input::old('id')) !!}

                  {!! Form::hidden('amount', 0) !!}
                  {!! Form::hidden('inventory', 0) !!}

                  {{-- Reservation Title --}}
                  <div class="form-group col-sm-10 col-sm-offset-1">
                    {!! Form::label('name', 'Reservation Name' , ['clearfix control-label']) !!}
                    {!! Form::text('name', Input::old('name') , ['class' => 'form-control ']) !!}
                    @if(Session::has('name'))
                      <p class="edit-tickets-warn-text">{{ Session::get('name') }}</p>
                    @endif
                  </div>

                  {{-- Reservation Description --}}
                  <div class="form-group col-sm-10 col-sm-offset-1">
                    {!! Form::label('description', 'Reservation Description' , ['clearfix control-label']) !!}
                    {!! Form::textarea('description', Input::old('description'), ['class' => 'form-control', 'size' => '35x3']) !!}
                  </div>

                  {{-- Reservation Confirmation Message --}}
                  <div class="form-group col-sm-10 col-sm-offset-1">
                    {!! Form::label('confirmationMessage', 'Confirmation Message' , ['clearfix control-label']) !!}
                    {!! Form::textarea('confirmationMessage', Input::old('confirmationMessage'), ['class' => 'form-control', 'size' => '35x3']) !!}
                  </div>

                  <div class="form-group col-sm-10 col-sm-offset-1">
                    <button class="btn btn-suaray btn-suaray-positive btn-lg pull-right" ng-disabled="addTicketForm.$invalid"type="submit">Update</button>
                  </div>

                {!! Form::close() !!}

              {{-- If created as ticket --}}
              @else

                {!! ViewHelper::formModel($ticket, [ 'route' => ['tickets.update', 'id' => $ticket['id']]]) !!}

                  {!! Form::hidden('is_reservation', 0) !!}
                  {!! Form::hidden('eventId', $ticket['eventId']) !!}
                  {!! Form::hidden('id', Input::old('id')) !!}

                  {{-- Ticket Title --}}
                  <div class="form-group col-sm-10 col-sm-offset-1">
                    {!! Form::label('name', 'Ticket Name' , ['clearfix control-label']) !!}
                    {!! Form::text('name', Input::old('name') , ['class' => 'form-control ']) !!}
                    @if(Session::has('name'))
                      <p class="edit-tickets-warn-text">{{ Session::get('name') }}</p>
                    @endif
                  </div>

                  {{-- Ticket Description --}}
                  <div class="form-group col-sm-10 col-sm-offset-1">
                    {!! Form::label('description', 'Ticket Description' , ['clearfix control-label']) !!}
                    {!! Form::textarea('description', Input::old('description'), ['class' => 'form-control', 'size' => '35x3']) !!}
                  </div>

                  {{-- Event Start Date --}}
                  <div class="form-group col-sm-5 col-sm-offset-1">

                    {{-- START DATE Tag --}}
                    {!! Form::label('startsAt', 'Start Date', ['class' => 'clearfix control-label']) !!}<sup class="text-danger">&dagger;</sup>

                    {{-- Start Date Picker --}}
                    <div class="inner-addon right-addon input-group-lg create-event-detail-date-picker">
                      <i class="glyphicon create-event-detail glyphicon-calendar"></i>
                      {!! Form::text('startsAt', Input::old('startsAt') , ['class' => 'form-control date-pick datepicker', 'data-role' => 'date-picker', 'id' => 'txtFromDate', 'data-date-format' => 'mm/dd/yyyy']) !!}
                    </div>

                  </div>

                  {{-- Event End Date --}}
                  <div class="form-group col-sm-5">{{-- END DATE Tag --}}
                    {!! Form::label('endsAt', 'End Date', ['class' => 'clearfix control-label']) !!}<sup class="text-danger">&dagger;</sup>

                    {{-- End Date Picker --}}
                    <div class="inner-addon right-addon input-group-lg create-event-detail-date-picker">
                      <i class="glyphicon create-event-detail glyphicon-calendar"></i>
                      {!! Form::text('endsAt', Input::old('endsAt') ,  ['class' => 'form-control date-pick datepicker', 'data-role' => 'date-picker', 'id' => 'txtToDate', 'data-date-format' => 'mm/dd/yyyy']) !!}
                    </div>

                  </div>


                  <div class="form-group col-sm-4 col-sm-offset-1">
                    {!! Form::label('amount', 'Ticket Price' , ['clearfix control-label']) !!}
                    <div class="input-group">
                      <span class="input-group-addon">$</span>
                      {!! Form::text('amount', Input::old('amount') , ['class' => 'form-control no-negatives']) !!}
                    </div>
                    @if(Session::has('amount'))
                      <p class="edit-tickets-warn-text">{{ Session::get('amount') }}</p>
                    @endif
                  </div>

                  <div class="form-group col-sm-3">
                    {!! Form::label('inventory', 'Inventory', ['control-label']) !!}
                    <div class="input-group">
                      <span class="input-group-addon">#</span>
                      {!! Form::text('inventory', Input::old('inventory'), ['class' => 'form-control no-negatives']) !!}
                    </div>
                    @if(Session::has('inventory'))
                      <p class="edit-tickets-warn-text">{{ Session::get('inventory') }}</p>
                    @endif
                  </div>

                 <div class="col-md-3 col-sm-3 col-xs-6 edit-ticket ticket-type-new mobile-enable-btn">

                    <div class="btn-group edit-tickets-inventory-buttons update-btn mobile-margin-edit" data-toggle="buttons">

                      {{-- Ticket Enable Radio Input --}}
                      <span class="btn btn-suaray btn-suaray-primary control-label">
                        {!! Form::radio('isEnabled', '1', null, ['class' => 'form-control']) !!}
                      Enable </span>

                      {{-- Ticket Disable Input --}}
                      <span class="btn btn-suaray btn-suaray-primary control-label">
                        {!! Form::radio('isEnabled', '0', null, ['class' => 'form-control']) !!}
                      Disable </span>

                    </div>

                  </div>

                  <div class="col-md-3 col-sm-3 col-xs-6 edit-more ticket-type-new mobile-width-edit right-list-padding">
                    <div class="update-padding">
                      <button class="btn btn-suaray btn-suaray-positive btn-block pull-right" ng-disabled="addTicketForm.$invalid" type="submit">Update</button>
                    </div>
                  </div>

                {!! Form::close() !!}

              @endif

            </div> <!-- close row -->
          </div>
        </div>
      </div>
    </div>
	</div>
</div>
@stop



