@extends('layouts.master')

@section('content')
<div class="tickets-bg" ng-controller="AnalyticsController">

  <div class="results-body">

    <div class = "container">

      {{-- Session Message --}}
      @include('includes.sessionStatus')

      {{-- Dashboard edit navigation and title link --}}
      <div class="dashboard-titles">

        <div class="row">

          <div class="col-md-7 col-sm-7 col-xs-12">
            <h2 class="dash-title-txt">Analytics : <a class="manage-link" href="{{ url('events') }}/{{ $event['slug'] }}">{{ $event['title'] }}</a></h2>
          </div>

          <div class="col-md-5 col-sm-5 col-xs-12">


            <div class="friend-position" ng-class="{collapse: isCollapsed}">

              {{-- Begin unordered list of Nav Tabs --}}
              <ul class="nav navbar-nav analytics-nav friend-link-cursor" role="navigation">

                {{-- Ticket analytics tab  --}}
                <li class="analytics-tabs" role="presentation" ng-class="{active: tab === 'tickets'}" >
                  <a class="analytics-tabs-link" ng-click="tab = 'tickets'" data-role="tab-tickets">
                    <span class="analytics-tab-text">Tickets</span>
                  </a>
                </li>

               {{-- Reservation list tab  --}}
                <li class="analytics-tabs" role="presentation" ng-class="{active: tab === 'reservations'}" >
                  <a class="analytics-tabs-link" ng-click="tab = 'reservations'" data-role="tab-reservations">
                    <span class="analytics-tab-text">Reservations</span>
                  </a>
                </li>

                {{-- Attendees tab  --}}
                <li class="analytics-tabs" role="presentation" ng-class="{active: tab === 'attendees'}" >
                  <a class="analytics-tabs-link" ng-click="tab = 'attendees'" data-role="tab-attendees">
                    <span class="analytics-tab-text">Attendees</span>
                  </a>
                </li>

              </ul>
            </div>

            @include('accounts.dashboard.includes.editnav')

          </div>

        </div>

      </div>

      <div class="grid-results" ng-show="tab === 'tickets'">
        {{-- Start of event analytics --}}
        <div class = "view-event-show">
          <div class="dashboard-container">

            <div class="row">
              <div class="col-md-12">
                <div class="poll-card" style="margin-bottom: 28px;">

                    {{-- Shows revenue for event --}}
                    <div class="row">
                      <div class="col-md-4">
                        <div class = "dashboard-headers manage-link">
                        <p class="dashboard-title-tab">tickets</p></div>
                      </div>
                    </div>

                    {{-- Start of radials to show revenue, will show today, week and over all revenue once logic provided --}}
                    <div class="row">
                      <ul class="list-unstyled">

                        <li>
                          <div class="col-md-4 col-sm-4 col-xs-12">
                            <div class="friends-text-container">
                              <div class="poll-container ticket-header">
                                Available
                                </div>

                                {{-- Radial bar to fill per tickets available--}}
                                <div class="poll-padding">
                                  {{-- data-total holds the value to be displayed, data-percentage is pulled in for radial fill --}}
                                  <div class="pie" data-total="{{ $totalAvailable }}" data-percentage="{{ $percentageAvailable }}"></div>
                                </div>
                              {{-- End of radial bar --}}
                            </div>
                          </div>
                        </li>

                        <li>
                          <div class="col-md-4 col-sm-4 col-xs-12">
                            <div class="friends-text-container">
                              <div class="poll-container  ticket-header">
                                Sold
                                </div>

                                {{-- Radial bar to fill per tickets sold --}}
                                <div class="poll-padding">
                                  <div class="pie" data-total="{{ $totalSold }}" data-percentage="{{ $percentageSold }}"></div>
                                </div>
                              {{-- End of radial bar --}}
                            </div>
                          </div>
                        </li>

                        <li>
                          <div class="col-md-4 col-sm-4 col-xs-12">
                            <div class="friends-text-container">
                              <div class="poll-container  ticket-header">
                                Redeemed
                                </div>

                                {{-- Radial bar to fill per tickets redeemed--}}
                                <div class="poll-padding">
                                  <div class="pie" data-total="{{ $totalUsed }}" data-percentage="{{ $percentageUsed }}"></div>
                                </div>
                              {{-- End of radial bar --}}
                            </div>
                          </div>
                        </li>

                      </ul>
                    </div>

               @if ($totalSold >= 1)

                    {{-- Shows revenue for event --}}
                    <div class="row">
                      <div class="col-md-4">
                        <div class = "dashboard-headers manage-link">
                        <p class="dashboard-title-tab analytics-purchased-title">Recently Purchased</p></div>
                      </div>
                    </div>

                    <div class="row">

                      <div class="col-md-12">

                        <table class=" table table-responsive">
                          <thead>
                            <tr>
                              <th>BUYER</th>
                              <th>EMAIL</th>
                              {{-- <th>QUANTITY</th> --}}
                              <th>PRICE</th>
                              <th>DATE</th>
                            </tr>
                          </thead>

                          <tbody>

                            @foreach (array_reverse($event['ticketsOrder']) as $ticketOrders)

                              @if ($ticketOrders['amount'] >= 1)

                                <tr>
                                  <td>{{ $ticketOrders['user']['firstName'] }} {{ $ticketOrders['user']['lastName'] }}</td>
                                  <td>{{ $ticketOrders['user']['email']}}</td>
                                  <td>$ {{ $ticketOrders['amount'] }}</td>
                                  <td>{{ date('F d Y', strtotime($ticketOrders['createdAt'])) }} {{ date('g:i a', strtotime($ticketOrders['createdAt'])) }}</td>
                                </tr>

                              @endif

                            @endforeach

                          </tbody>

                        </table>

                      </div>

                    </div>

                  @endif
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-md-12">
                <div class="poll-card" style="margin-bottom: 28px;">

                    {{-- Shows revenue for event --}}
                    <div class="row">
                      <div class="col-md-4">
                        <div class = "dashboard-headers manage-link">
                        <p class="dashboard-title-tab">revenue </p></div>
                      </div>
                    </div>

                    {{-- Start of radials to show revenue, will show today, week and over all revenue once logic provided --}}
                    <div class="row">
                      <ul class="list-unstyled">

                        <li>
                          <div class="col-md-4 col-sm-4 col-xs-12">
                            <div class="friends-text-container">
                              <div class="poll-container ticket-header">
                                Today
                                </div>

                                {{-- Radial bar to fill per revenue earned today--}}
                                <div class="poll-padding">
                                  <div class="pie" data-total="0" data-percentage=""></div>
                                </div>
                            {{-- End of radial bar --}}
                            </div>
                          </div>
                        </li>

                        <li>
                          <div class="col-md-4 col-sm-4 col-xs-12">
                            <div class="friends-text-container">
                              <div class="poll-container  ticket-header">
                                This Week
                                </div>

                                {{-- Radial bar to fill per revenue earned this week--}}
                                <div class="poll-padding">
                                  <div class="pie" data-total="0" data-percentage=""></div>
                                </div>
                            {{-- End of radial bar --}}
                            </div>
                          </div>
                        </li>

                        <li>
                          <div class="col-md-4 col-sm-4 col-xs-12">
                            <div class="friends-text-container">
                              <div class="poll-container  ticket-header">
                                Over All
                                </div>

                                {{-- Radial bar to fill per revenue earned over all--}}
                                <div class="poll-padding">
                                  <div class="pie" data-total="{{ $totalRevenue }}" data-percentage=""></div>
                                </div>
                            {{-- End of radial bar --}}
                            </div>
                          </div>
                        </li>

                      </ul>
                    </div>
                </div>
              </div>
            </div>

          </div>
        </div>
      </div>

      {{-- Show reservations list --}}
      <div class="grid-results" ng-show="tab === 'reservations'">

        <div class = "view-event-show">
          @if(isset($reservations) && $reservations)

            {{-- If attendees exist show user information --}}
            <table class="analytics-table table table-condensed table-responsive">

              <thead class="">
                <tr class="">

                  {{-- Indexes user --}}
                  <th class="table-count">#</th>

                  {{-- First and Last Name of user --}}
                  <th class="table-width">FIRST NAME</th>
                  <th class="table-width">LAST NAME</th>

                  {{-- User email --}}
                  <th class="table-width">EMAIL</th>
                  <th class="table-width">REQUEST</th>
                  <th class="table-width">RESEND</th>

                </tr>
              </thead>

              <tbody class="">

                @foreach ($reservations as $index => $reservation)

                <tr class="">
                  <td class="" data-label="#">{{ $index + 1}}</td>
                  <td class="table-overflow" data-label="FIRST NAME">
                    <a class="manage-link" href="{{ route('profile.public', ['username' => $reservation['username']]) }}">
                      {{ $reservation['firstName'] }}
                    </a>
                  </td>
                  <td  class="table-overflow" data-label="LAST NAME">
                    <a class="manage-link" href="{{ route('profile.public', ['username' => $reservation['username']]) }}">
                      {{ $reservation['lastName'] }}
                    </a>
                  </td>
                  <td class="table-overflow" data-label="EMAIL">{{ $reservation['email'] }}</td>
                  <td class="" data-label="REQUEST">{!! str_replace("\n", '<br>', $reservation['request']) !!}</td>
                  @if (isset($reservation['email']))
                    <td>
                      {!! Form::open(['method' => 'POST', 'route' => ['resend.reservation', $event['id'], $reservation['id']]]) !!}
                        {!! Form::submit('Resend email') !!}
                      {!! Form::close() !!}
                    </td>
                  @else
                    <td>
                      <strong>No vaid email supplied</strong>
                    </td>
                  @endif
                </tr>

                @endforeach

              </tbody>

            </table>

          @else

            {{-- Show default for no reservations --}}
            <div class="row">
              <div class="col-md-12">
                <div class="poll-card manage-link">

                  {{-- If no schedules, default will show --}}
                  <div class="default-schedule-message">
                    <h5 class="text-muted">There Are No Reservations For This Event</h5>
                  </div>

                </div>
              </div>
            </div>

          @endif
        </div>

      </div>

      {{-- Show attendees list --}}
      <div class="grid-results" ng-show="tab === 'attendees'">

        {{-- Start of event analytics --}}
        <div class = "view-event-show">
          <div class="dashboard-container">

            <div class="row">
              <div class="col-md-12">

                {{-- If no attendees exist for event show default message --}}
                @if(!empty($attendeeList) && $attendeeList)

                  {{-- If attendees exist show user information --}}
                  <table class=" table table-condensed table-responsive">

                    <thead>
                      <tr>

                        {{-- Indexes user --}}
                        <th>#</th>

                        {{-- First and Last Name of user --}}
                        <th>FIRST NAME</th>
                        <th>LAST NAME</th>

                        {{-- User email --}}
                        <th>EMAIL</th>

                      </tr>
                    </thead>

                    <tbody>

                      @foreach ($attendeeList['attendeesAndFriends'] as $index => $attendees)

                        <tr>
                          <td>{{ $index + 1 }}</td>
                          <td>
                            <a class="manage-link" href="{{ route('profile.public', ['username' => $attendees['user']['username']]) }}">
                              {{ $attendees['user']['firstName'] }}
                            </a>
                          </td>
                          <td>
                            <a class="manage-link" href="{{ route('profile.public', ['username' => $attendees['user']['username']]) }}">
                              {{ $attendees['user']['lastName'] }}
                            </a>
                          </td>

                          <td>email</td>

                          <td>Date</td>

                          {{-- <td>2</td> --}}

                        </tr>

                      @endforeach

                    </tbody>

                  </table>

                @else

                  <div class="poll-card manage-link">

                    {{-- If no schedules, default will show --}}
                    <div class="default-schedule-message">
                      <h5 class="text-muted">There Are No Attendees For This Event</h5>
                    </div>

                  </div>

                @endif

              </div>
            </div>

          </div>
        </div>
      </div>

    </div>

  </div>

</div>

@stop
