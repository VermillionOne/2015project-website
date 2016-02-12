@extends('layouts.master')

@section('content')
<div class="tickets-bg" ng-controller="DashboardController">

  <div class="results-body">

    <div class = "container">

      {{-- Dashboard edit navigation and title link --}}
      <div class="dashboard-titles">

        <div class="row">

          <div class="col-md-8 col-sm-8 col-xs-12">
            <h2 class="results-title-txt">Attendees : <a class="manage-link" href="{{ url('events') }}/{{ $event['slug'] }}">{{ $event['title'] }}</a></h2>
          </div>

          <div class="col-md-4 col-sm-4 col-xs-12">
            @include('accounts.dashboard.includes.editnav')
          </div>

        </div>

      </div>

      {{-- Start of event analytics --}}
      <div class = "view-event-show">
        <div class="dashboard-container">

          <div class="row">
            <div class="col-md-12">

              {{-- If no attendees exist for event show default message --}}
              @if(empty($attendeeList['attendeesAndFriends']))

                <div class="row">
                  <div class="col-md-12">
                    <div class="poll-card manage-link">

                      <div class = "friends-invite-confirmation">

                          <div class= "poll-title">There Are No Attendees For This Event</div>

                      </div>

                    </div>
                  </div>
                </div>

              @else

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

                    {{-- Date attending --}}
                    <th>DATE</th>


                    {{-- Date attending --}}
                    {{-- <th>Attending</th> --}}

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

                      <td>{{ $attendees['user']['email']}}</td>

                      <td>Date</td>

                      {{-- <td>2</td> --}}

                    </tr>

                  @endforeach

                </tbody>

              </table>
              @endif

            </div>
          </div>

        </div>
      </div>

    </div>
  </div>

</div>
@stop
