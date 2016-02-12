

<div class = "event-detail-col-r-widget rsvp-container" ng-controller="RsvpController">

  <div>@{{time}}</div>


  {{-- Begin the RSVP section --}}
  <div class = "events-detail-title-r-col">{{ isset($events['meta']['rsvp']['label']) ? $events['meta']['rsvp']['label'] : 'RSVP' }}</div>

  {{-- Check to see if the user is logged in --}}
  @if(Auth::check())

    @if ($events['nextEvent'] === null)

    <div class = "event-detail-poll-title">No Events To RSVP</div>

    @else

      {{-- Start attendence tally --}}
      <div class="center-text">Are you going to attend <b>{{ $events['title'] }}</b>?</div>

      {{-- <button ng-click="getRsvp()">Testing getRsvp()</button> --}}

      @if (\Auth::check())

      	<div class="select-style">

					{!! Form::select('eventTimeId', $times, 0, ['class' => 'rsvp-date-select', 'ng-model' => 'eventTimeIdSelected', 'ng-change' => 'getRsvp()', 'data-role' => 'event-date', 'ng-init' => 'eventTimeIdSelected = ' . $eventTimeIdSelected]) !!}

        </div>

        <a class="btn btn-block rsvp-choice btn-suaray btn-suaray-positive"
          data-role="rsvp-yes"
          ng-click="rsvpResponse({{ \Auth::user()->id }}, eventTimeIdSelected, 'yes')"
          ng-hide="data.isNo || data.isMaybe"
          ng-disabled="data.isYes"><span ng-hide="data.isYes">Yes</span><span ng-show="data.isYes">Attending</span>
        </a>
        <a class="btn btn-block rsvp-choice btn-suaray btn-suaray-primary"
          data-role="rsvp-maybe"
          ng-click="rsvpResponse({{ \Auth::user()->id }}, eventTimeIdSelected, 'maybe')"
          ng-hide="data.isYes || data.isNo"
          ng-disabled="data.isMaybe"><span ng-hide="data.isMaybe">Maybe</span><span ng-show="data.isMaybe">Might Attend</span>
        </a>
        <a class="btn btn-block rsvp-choice btn-suaray btn-suaray-alert"
          data-role="rsvp-no"
          ng-click="rsvpResponse({{ \Auth::user()->id }}, eventTimeIdSelected, 'no')"
          ng-hide="data.isYes || data.isMaybe"
          ng-disabled="data.isNo"><span ng-hide="data.isNo">No</span><span ng-show="data.isNo">Not Attending</span>
        </a>

      @endif

      <div class="center-text">
        {{-- Yes response --}}
        <div class="rsvp-message">
          <div ng:bind="rsvpMessage"></div>
        </div>
      </div>

			{{-- Change RSVP --}}
			<button ng-click="reopenRsvp()" ng-show="rsvpSent === true" class="btn btn-suaray btn-suaray-linkish">Change RSVP</button>

    @endif

  {{-- Else show a you are not logged in message --}}
  @else
    Please <a href="{{ url(route('login')) }}" name="rsvp-sign-in">Sign In</a> to RSVP
  @endif



</div>
