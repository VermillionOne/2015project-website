@extends('layouts.master')

@section('javascript-includes')

  {{-- For evaporate.js settings --}}
  <script>suarayConfig.upload = {@foreach($uploadSettings as $key => $value){!! "{$key}:'{$value}'," !!}@endforeach random:0};</script>

  <script src="{{ ViewHelper::asset('assets/dist/lib-events-create.js') }}"></script>
@stop

@section('content')

{{-- Wrapper for the Create Event Page --}}
<div class="view-event-create" ng-controller="EventsCreateController" ng-init="masterTab = 'addEvent'">

  {{-- Create Events Form Navbar --}}
  @include('events.create.step')

  {{-- Danger Message --}}
  @if(Session::has('fail_message'))
    <div class="alert alert-danger" align="center">
      <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
      {{ Session::get('fail_message') }}
    </div>
  @endif

  <div class="create-event-container">

    @if (isset($event['id']))

      {{-- BEGIN Form Elements | Details --}}
      {!! ViewHelper::formModel(['route' => ['events.create', $event['id']], 'name' => 'createForm', 'novalidate', 'ng-click' => 'schedule.updateJson()', 'method' => 'PUT']) !!}

        {{-- Free Or Premium Section |  --}}
        {{-- <div class="container" ng-show="tab === 'freemium'">
          @include('events.create.freemium')
        </div> --}}

        {{-- Method of Payment Section | Includes Payment Options --}}
        <div class="container" ng-show="tab === 'payment'">
          @include('events.create.payment')
        </div>

        {{-- Details Section | Includes Title, Descriptions, Time, and Type Details --}}
        <div class="container" ng-show="tab === 'details'">
          @include('events.create.details')
        </div>

        {{-- Locations Section | Includes Venue Name, Address, City, State, Zip Code --}}
        <div class="container" ng-show="tab === 'location'">
          @include('events.create.location')
        </div>

        {{-- Options Section | Includes Toggleable Options --}}
        <div class="container" ng-show="tab === 'options'">
          @include('events.create.options')
        </div>

        {{-- Method of Payment Section | Includes Payment Options --}}
        <div class="container" ng-show="tab === 'media'">
          @include('events.create.media')
        </div>

        {{-- Summary Section |  --}}
        <div class="container" ng-show="tab === 'summary'">
          @include('events.create.summary')
        </div>

      {{-- End Form --}}
      {!! Form::close() !!}
      {!! Form::hidden('isPremium', 1, ['data-role' => 'premium']) !!}

    @else

      {{-- BEGIN Form Elements | Details --}}
      {!! ViewHelper::formOpen(['route' => 'events.create', 'name' => 'createForm', 'novalidate', 'ng-click' => 'schedule.updateJson()', 'method' => 'POST']) !!}

        {{-- Free Or Premium Section |  --}}
        {{-- <div class="container" ng-show="tab === 'freemium'">
          @include('events.create.freemium')
        </div> --}}

        {{-- Method of Payment Section | Includes Payment Options --}}
        <div class="container" ng-show="tab === 'payment'">
          @include('events.create.payment')
        </div>

        {{-- Details Section | Includes Title, Descriptions, Time, and Type Details --}}
        <div class="container" ng-show="tab === 'details'">
          @include('events.create.details')
        </div>

        {{-- Locations Section | Includes Venue Name, Address, City, State, Zip Code --}}
        <div class="container" ng-show="tab === 'location'">
          @include('events.create.location')
        </div>

        {{-- Options Section | Includes Toggleable Options --}}
        <div class="container" ng-show="tab === 'options'">
          @include('events.create.options')
        </div>

        {{-- Method of Payment Section | Includes Payment Options --}}
        <div class="container" ng-show="tab === 'media'">
          @include('events.create.media')
        </div>

        {{-- Summary Section |  --}}
        <div class="container" ng-show="tab === 'summary'">
          @include('events.create.summary')
        </div>

      {{-- End Form --}}
      {!! Form::close() !!}

    @endif

    <p class="container asterisk-explanation">
      <span><sup class="text-danger">&#42;</sup> Fields Are Required<br></span>
      <span ng-show="tab === 'details'"><sup class="text-danger">&dagger;</sup> Up To Five Tags Allowed - Separate Tags Via Comma</span>
    </p>

  </div>
</div>
@stop
