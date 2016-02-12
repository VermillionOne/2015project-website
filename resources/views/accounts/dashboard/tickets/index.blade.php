@extends('layouts.master')

@section('javascript-includes')
  <script src="{{ ViewHelper::asset('assets/dist/lib-accounts-profile.js') }}"></script>
@stop

@section('content')

  {{-- Session Message --}}
  @include('includes.sessionStatus')

  <div class="tickets-bg">

    <div class="results-body">

      <div class = "container">

        <div class="dashboard-titles">

          <div class="row">

            <div class="col-md-8 col-sm-6 col-xs-12">

              <h2 class="results-title-txt">Tickets :
                <a class="manage-link" href="{{ url('events') }}/{{ $event['slug'] }}">{{ $event['title'] }}</a>
              </h2>

            </div>

            <div class="col-md-4 col-sm-6 col-xs-12">

              @include('accounts.dashboard.includes.editnav')

              {{-- If no payment information has been set up, will prompt user to enter payment info --}}
              @if (empty($managed) || ! isset($managed['acctId']))
                <a class="clear-form search-clear" href="{{ route('payment') }}">Add Payment Information</a>
              @else
                <a class="clear-form search-clear" href="{{ route('tickets.create',  ['id' => $event['id']]  ) }}">Add Ticket</a>
              @endif

            </div>

          </div>

        </div>

        <div class = "view-event-show">
          <div class = "event-detail-container">

            {{-- If tickets inventory is empty default message will display with option to add --}}
            @if (empty($event['ticketsInventory']))
              <div class="row">
                <div class="col-md-12">
                  <div class="poll-card manage-link">

                    {{-- If no schedules, default will show --}}
                    <div class="default-schedule-message">
                      {{-- If no payment information has been set up, will prompt user to enter payment info --}}
                      @if (empty($managed) || ! isset($managed['acctId']))

                        <h5 class="text-muted">You Must Have a Payment Source to Create Tickets</h5>
                        <h5 class="text-muted"><a href="{{ route('payment') }}" class="poll-title">Add Payment Information</a></h5>

                      @else

                        <h5 class="text-muted">There Are No Tickets For This Event</h5>
                        <h5 class="text-muted"><a href="{{ route('tickets.create',  ['id' => $event['id']]  ) }}">Add Tickets</a></h5>

                      @endif


                    </div>

                  </div>
                </div>
              </div>
            @else

              {{-- Loops through existing tickets--}}
              @foreach (array_reverse($event['ticketsInventory']) as $ticketsInventory)

                <div class="row">
                  <div class="col-md-12">
                    <div class="poll-card manage-link">

                      <div class = "event-detail-tickets-col padding-ticket">

                        {{-- Ticket title --}}
                        <div class="row">
                          <div class="col-xs-9">
                            <div class= "poll-title">{{ $ticketsInventory['name'] }}</div>
                          </div>

                          {{-- Edit option for existing tickets --}}
                          <div class="col-xs-3">
                            <a href="{{ url(route('tickets.edit', ['id' => $ticketsInventory['id']])) }}" class="pull-right">Edit</a>
                          </div>
                        </div>

                        <div class="row">

                          {{-- If ticket, show stats for tickets --}}
                          @if($ticketsInventory['isReservation'] === false)

                            <ul class="list-unstyled friends-list-populate">

                              <li>

                                <div class="col-md-3 col-sm-3 col-xs-6">
                                  <div class="friends-text-container">

                                    <div class="ticket-data ticket-header">
                                      Start Date
                                    </div>

                                    <div class="ticket-data">
                                      {{ empty($ticketsInventory['startsAt'])?'Anytime' : date('F d', strtotime($ticketsInventory['startsAt'])) }}
                                    </div>

                                  </div>
                                </div>

                              </li>

                              <li>

                                {{-- End date --}}
                                <div class="col-md-3 col-sm-3 col-xs-6">
                                  <div class="friends-text-container">

                                    <div class="ticket-data ticket-header">
                                      End Date
                                    </div>

                                    <div class="ticket-data">
                                      {{ empty($ticketsInventory['endsAt'])?'Never' : date('F d', strtotime($ticketsInventory['endsAt'])) }}
                                    </div>

                                  </div>
                                </div>

                              </li>

                              <li>

                                {{-- Ticket price --}}
                                <div class="col-md-2 col-sm-2 col-xs-6">
                                  <div class="friends-text-container">

                                    <div class="ticket-data ticket-header">
                                      Price
                                    </div>

                                    <div class="ticket-data">
                                      ${{ $ticketsInventory['amount'] }}
                                    </div>

                                  </div>
                                </div>

                              </li>

                              <li>

                                {{-- Ticket inventory --}}
                                <div class="col-md-2 col-sm-2 col-xs-6">
                                  <div class="friends-text-container">

                                    <div class="ticket-data ticket-header">
                                      Inventory
                                    </div>

                                    <div class="ticket-data">
                                      {{ $ticketsInventory['inventory'] }}
                                    </div>

                                  </div>
                                </div>

                              </li>

                              <li>

                                {{-- Ticket price --}}
                                <div class="col-md-2 col-sm-2 col-xs-12">
                                  <div class="friends-text-container">

                                    <div class="ticket-data ticket-header">
                                      Status
                                    </div>

                                    {{-- Show if tickets are enabled or disabled --}}
                                    <div class="ticket-data">

                                      @if ($ticketsInventory['isEnabled'] === true)

                                        Enabled

                                      @else

                                        Disabled

                                      @endif

                                    </div>

                                  </div>
                                </div>

                              </li>

                            </ul>

                          @else

                            {{-- If reservation, show reservation information --}}
                            <div class="col-md-12">
                              <div class="friends-text-container">

                                {{-- Reservation description header--}}
                                <div class="reservation-data ticket-header">
                                  <p class="reservation-text">Description of Reservation</p>
                                </div>

                                {{-- Reservation description --}}
                                <div class="reservation-data">

                                  {{-- If description is empty, show default message --}}
                                  @if(!empty($ticketsInventory['description']))

                                    <p>{{ $ticketsInventory['description'] }}</p>

                                  @else

                                    <p>No Description Has Been Set For This Reservation</p>

                                  @endif

                                </div>

                              </div>
                            </div>

                            <div class="col-md-12">
                              <div class="friends-text-container">

                                {{-- Reservation Confirmation Message header --}}
                                <div class="reservation-data ticket-header">
                                  <p class="reservation-text">Confirmation Message for Reservation</p>
                                </div>

                                {{-- If Confirmation Message is empty, show default message --}}
                                <div class="reservation-data">

                                @if(!empty($ticketsInventory['confirmationMessage']))

                                  <p>{{ $ticketsInventory['confirmationMessage'] }}</p>

                                @else

                                  <p>No Confirmation Message Has Been Set For This Reservation</p>

                                @endif

                                </div>

                              </div>
                            </div>

                          @endif

                        </div>

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
@stop
