@extends('layouts.master')

@section('content')
<div class="tickets-bg">
  <div class="results-body">

    {{-- User tickets header --}}
    <div class="container" ng-controller="DashboardController">

      {{-- Dashboard edit navigation and title link --}}
      <div class="dashboard-titles">

        <div class="row">

          <div class="col-md-7 col-sm-7 col-xs-12">
            <div class="results-title-txt">{{ Auth::user()->first_name }}'s Tickets</div>
          </div>

          <div class="col-md-5 col-sm-5 col-xs-12">

              {{-- Begin unordered list of Nav Tabs --}}
              <ul class="nav navbar-nav my-tickets-nav friend-link-cursor" role="navigation">

               {{-- Reservation list tab  --}}
                <li class="analytics-tabs" role="presentation" ng-class="{active: tab === 'upcoming'}" >
                  <a class="analytics-tabs-link" ng-click="tab = 'upcoming'" data-role="tab-upcoming">
                    <span class="analytics-tab-text">Upcoming Events</span>
                  </a>
                </li>

                {{-- past tab  --}}
                <li class="analytics-tabs" role="presentation" ng-class="{active: tab === 'past'}" >
                  <a class="analytics-tabs-link" ng-click="tab = 'past'" data-role="tab-past">
                    <span class="analytics-tab-text">Past Events</span>
                  </a>
                </li>

              </ul>

          </div>

        </div>

      </div>

      <div class="row" ng-show="tab === 'upcoming'">

        {{-- If tickets have been purchased, list with panel will show event and tickets --}}
        @if($futurePurchasedTickets)

          @foreach ($futurePurchasedTickets as $ticket)

            <div class="col-xs-12 col-md-12">

                <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
                  <div class="panel panel-default">

                    <div class="panel-heading panel-heading-ticket" role="tab" id="heading{{$ticket['id']}}" style="overflow: hidden; padding: 10px 0 0;">

                      <div class="row">

                        {{-- Ticket icon --}}
                        <div class="col-md-1 col-sm-1">

                          <span class="icon fa fa-ticket fa-fw ticket-icon-image"></span>

                        </div>

                        {{-- Event title with location and date --}}
                        <div class="col-md-5 col-sm-5 col-xs-12">

                          @if ($ticket['event']['title'] !== null)
                            <h4 class="ticket-icon mobile-my-ticket-title">{{ $ticket['event']['title'] }}</h4>
                          @else
                            <h4 class="ticket-icon mobile-my-ticket-title">No title for Event</h4>
                          @endif

                        </div>

                        <div class="col-md-5 col-sm-5 col-xs-12">

                            @if ($ticket['event']['city'] !== null)
                              <p class=" ticket-panel-text mobile-location-title"><span class="fa fa-map-marker fa-fw"></span>{{$ticket['event']['city']}}</p>
                            @elseif ($ticket['event']['venueName'] !== null)
                              <p class=" ticket-panel-text mobile-location-title"><span class="fa fa-map-marker fa-fw"></span>{{$ticket['event']['venueName']}}</p>
                            @else
                              <p class=" ticket-panel-text mobile-location-title"><span class="fa fa-map-marker fa-fw"></span>No event Location</p>
                            @endif

                            @if (isset($ticket['eventTime']['start']) && $ticket['eventTime']['start'] !== null)
                              <p class=" ticket-panel-text mobile-date-time">
                                <span class="fa fa-calendar fa-fw"></span>&nbsp;{{ date('F d', strtotime($ticket['eventTime']['start'])) }} @ {{ date('g:i a', strtotime($ticket['eventTime']['start'])) }}
                              </p>
                            @else
                              <p class="ticket-panel-text mobile-date-time">
                                <span class="fa fa-calendar fa-fw"></span>&nbsp; No Dates
                              </p>
                            @endif

                        </div>

                        {{-- Toggles to show event tickets purchased --}}
                        <div class="col-md-1 col-sm-1 col-xs-2">

                          <h4 class="panel-title">

                            <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse{{$ticket['id']}}" title="Show List" aria-expanded="true" aria-controls="collapse{{$ticket['id']}}">
                            </a>

                          </h4>

                        </div>

                      </div>

                    </div>

                    {{-- Start of dropdown for accordian containing tickets purchased for event --}}
                    <div id="collapse{{$ticket['id']}}" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading{{$ticket['id']}}">
                      <div class="panel-body">

                        @if (! empty($ticket['types']))
                          {{-- Show individual tickets purchased --}}
                          @foreach ($ticket['types'] as $eventTicket)
                            <div class="col-xs-12 col-sm-4 col-md-4 purchased-ticket-box">

                              <div class="purchased-ticket-title">
                                {{ $eventTicket['name']}}
                              </div>

                              <div class="purchased-ticket-container">

                                {{-- Shows address date and time of specific ticket --}}
                                <div class="purchased-ticket-location-min">

                                  <p class="purchased-ticket-header">
                                    {{ $ticket['event']['address1']}}
                                  </p>

                                  <p class="purchased-ticket-date">
                                    @if (isset($ticket['eventTime']['start']))
                                      {{ date('F d', strtotime($ticket['eventTime']['start'])) }}
                                    @endif
                                  </p>

                                  <p class="purchased-ticket-date">
                                    @if (isset($ticket['eventTime']['start']))
                                      {{ date('g:i a', strtotime($ticket['eventTime']['start'])) }} to {{ date('g:i a', strtotime($ticket['eventTime']['end'])) }}
                                    @endif
                                  </p>

                                </div>

                                {{-- Shows count of purchased,used and remaining tickets --}}
                                <div class="purchased-ticket-used">

                                  <p class="purchased-ticket-info">
                                    Total Paid: ${{ $eventTicket['amount'] * $eventTicket['qty']['purchased'] }}
                                  </p>

                                  <p class="purchased-ticket-info">
                                    Purchased: {{$eventTicket['qty']['purchased']}}
                                  </p>

                                  <p class="purchased-ticket-info">
                                    Remaining: {{$eventTicket['qty']['available']}}
                                  </p>

                                  {{-- When use ticket is clicked, qr for ticket shows --}}
                                  <div class="purchased-ticket-btn">

                                    <div class="btn-group" role="group" aria-label="...">

                                      <span class="dropdown btn-group">
                                        {{-- Toggles qr --}}
                                        <a type="button" href="#" class="btn dropdown-toggle manage-link" id="menu1" data-toggle="dropdown">Use Ticket</a>

                                        {{-- Shows qr --}}
                                        <div class="dropdown-menu dropdown-menu-edit purchased-ticket-qr" role="menu" aria-labelledby="menu1">

                                          <img src="../assets/img/sample-qr.jpg" class="purchased-ticket-img">

                                          <p class="purchased-ticket-code">{{$ticket['code']}}</p>

                                        </div>

                                      </span>

                                    </div>

                                  </div>

                                  <div class="purchased-ticket-info">
                                      This ticket purchase is subject to <br/>
                                      SHINDIIG LLC. TERMS AND CONDITIONS and PRIVACY POLICY on suaray.com
                                  </div>

                                </div>

                              </div>

                            </div>
                          @endforeach
                        @endif

                      </div>
                    </div>

                  </div>
                </div>

            </div>

          @endforeach

        {{-- If no tickets are available to display, user will see default message --}}
        @else

          <div class="row">
            <div class="col-md-12">
              <div class="poll-card manage-link">

                <div class = "friends-invite-confirmation">

                  <div class= "poll-title">There Are No Tickets To Display</div>

                  <div class= "poll-title"> Browse Events <a href="{{ url(route('events.categories')) }}" >Here</a></div>

                </div>

              </div>
            </div>
          </div>

        @endif

      </div>

      <div class="row past-tickets"  ng-show="tab === 'past'">

        {{-- If tickets have been purchased, list with panel will show event and tickets --}}
        @if($pastPurchasedTickets)

          @foreach ($pastPurchasedTickets as $ticket)

            <div class="col-xs-12 col-md-12">

                <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
                  <div class="panel panel-default">

                    <div class="panel-heading panel-heading-ticket" role="tab" id="heading{{$ticket['id']}}" style="overflow: hidden; padding: 10px 0 0;">

                      <div class="row">

                        {{-- Ticket icon --}}
                        <div class="col-md-1 col-sm-1">

                          <span class="icon fa fa-ticket fa-fw ticket-icon-image"></span>

                        </div>

                        {{-- Event title with location and date --}}
                        <div class="col-md-5 col-sm-5 col-xs-12">

                          @if ($ticket['event']['title'] !== null)
                            <h4 class="ticket-icon mobile-my-ticket-title">{{ $ticket['event']['title'] }}</h4>
                          @else
                            <h4 class="ticket-icon mobile-my-ticket-title">No title for Event</h4>
                          @endif

                        </div>

                        <div class="col-md-5 col-sm-5 col-xs-12">

                            @if ($ticket['event']['city'] !== null)
                              <p class=" ticket-panel-text mobile-location-title"><span class="fa fa-map-marker fa-fw"></span>{{$ticket['event']['city']}}</p>
                            @elseif ($ticket['event']['venueName'] !== null)
                              <p class=" ticket-panel-text mobile-location-title"><span class="fa fa-map-marker fa-fw"></span>{{$ticket['event']['venueName']}}</p>
                            @else
                              <p class=" ticket-panel-text mobile-location-title"><span class="fa fa-map-marker fa-fw"></span>No event Location</p>
                            @endif

                            @if (isset($ticket['eventTime']['start']) && $ticket['eventTime']['start'] !== null)
                              <p class=" ticket-panel-text mobile-date-time">
                                <span class="fa fa-calendar fa-fw"></span>&nbsp;{{ date('F d', strtotime($ticket['eventTime']['start'])) }} @ {{ date('g:i a', strtotime($ticket['eventTime']['start'])) }}
                              </p>
                            @else
                              <p class="ticket-panel-text mobile-date-time">
                                <span class="fa fa-calendar fa-fw"></span>&nbsp; No Dates
                              </p>
                            @endif

                        </div>

                        {{-- Toggles to show event tickets purchased --}}
                        <div class="col-md-1 col-sm-1 col-xs-2">

                          <h4 class="panel-title">

                            <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse{{$ticket['id']}}" title="Show List" aria-expanded="true" aria-controls="collapse{{$ticket['id']}}">
                            </a>

                          </h4>

                        </div>

                      </div>

                    </div>

                    {{-- Start of dropdown for accordian containing tickets purchased for event --}}
                    <div id="collapse{{$ticket['id']}}" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading{{$ticket['id']}}">
                      <div class="panel-body">

                        @if (! empty($ticket['types']))
                          {{-- Show individual tickets purchased --}}
                          @foreach ($ticket['types'] as $eventTicket)
                            <div class="col-xs-12 col-sm-4 col-md-4 purchased-ticket-box">

                              <div class="purchased-ticket-title">
                                {{ $eventTicket['name']}}
                              </div>

                              <div class="purchased-ticket-container">

                                {{-- Shows address date and time of specific ticket --}}
                                <div class="purchased-ticket-location-min">

                                  <p class="purchased-ticket-header">
                                    {{ $ticket['event']['address1']}}
                                  </p>

                                  <p class="purchased-ticket-date">
                                    @if (isset($ticket['eventTime']['start']))
                                      {{ date('F d', strtotime($ticket['eventTime']['start'])) }}
                                    @endif
                                  </p>

                                  <p class="purchased-ticket-date">
                                    @if (isset($ticket['eventTime']['start']))
                                      {{ date('g:i a', strtotime($ticket['eventTime']['start'])) }} to {{ date('g:i a', strtotime($ticket['eventTime']['end'])) }}
                                    @endif
                                  </p>

                                </div>

                                {{-- Shows count of purchased,used and remaining tickets --}}
                                <div class="purchased-ticket-used">

                                  <p class="purchased-ticket-info">
                                    Total Paid: ${{ $eventTicket['amount'] * $eventTicket['qty']['purchased'] }}
                                  </p>

                                  <p class="purchased-ticket-info">
                                    Purchased: {{$eventTicket['qty']['purchased']}}
                                  </p>

                                  <p class="purchased-ticket-info">
                                    Remaining: {{$eventTicket['qty']['available']}}
                                  </p>

                                  {{-- When use ticket is clicked, qr for ticket shows --}}
                                  <div class="purchased-ticket-btn">

                                    <div class="btn-group" role="group" aria-label="...">

                                      <span class="dropdown btn-group">
                                        {{-- Toggles qr --}}
                                        <a type="button" href="#" class="btn dropdown-toggle manage-link" id="menu1" data-toggle="dropdown">Use Ticket</a>

                                        {{-- Shows qr --}}
                                        <div class="dropdown-menu dropdown-menu-edit purchased-ticket-qr" role="menu" aria-labelledby="menu1">

                                          <img src="../assets/img/sample-qr.jpg" class="purchased-ticket-img">

                                          <p class="purchased-ticket-code">{{$ticket['code']}}</p>

                                        </div>

                                      </span>

                                    </div>

                                  </div>

                                  <div class="purchased-ticket-info">
                                      This ticket purchase is subject to <br/>
                                      SHINDIIG LLC. TERMS AND CONDITIONS and PRIVACY POLICY on suaray.com
                                  </div>

                                </div>

                              </div>

                            </div>
                          @endforeach
                        @endif

                      </div>
                    </div>

                  </div>
                </div>

            </div>

          @endforeach

        {{-- If no tickets are available to display, user will see default message --}}
        @else

          <div class="row">
            <div class="col-md-12">
              <div class="poll-card manage-link">

                <div class = "friends-invite-confirmation">

                  <div class= "poll-title">You Have No Previous Tickets To Show</div>

                  <div class= "poll-title"> Browse Events <a href="{{ url(route('events.categories')) }}" >Here</a></div>

                </div>

              </div>
            </div>
          </div>

        @endif

      </div>


    </div>

  </div>
</div>
@stop
