@extends('layouts.master')

@section('javascript-includes')
  <script src="{{ ViewHelper::asset('assets/dist/lib-accounts-profile.js') }}"></script>
@stop

@section('content')
  <div class="tickets-bg" ng-controller="TicketsController" ng-cloak class="ng-cloak">

    <div class="results-body">

      <div class="container">

        {{-- Check-in header and option to create new poll or see more options --}}
        <div class="dashboard-titles">

          <div class="row">

            <div class="col-md-8 col-sm-8 col-xs-12">

              <h2 class="results-title-txt">Check In</h2>

            </div>

            <div class="col-md-4 col-sm-6 col-xs-12">

              {{-- When history is clicked, the link will be replaced by tickets check-in --}}
              <span class="check-in-title" ng-show="tab === 'checkin'" ng-click="tab = 'history'; ticketData = null; success = false" data-role="tab-history">Check-In History</span>

              {{-- When tickets check-in is clicked, the link will be replaced by history --}}
              <span class="check-in-title" ng-show="tab === 'history'" ng-click="tab = 'checkin'" data-role="tab-checkin">Ticket Check-In</span>

            </div>

          </div>

        </div>

        {{-- Start of check-in for event --}}
        <div class="grid-results" ng-show="tab === 'checkin'">

          <div class="row">

            <div class="col-md-12">

              <div class="poll-card manage-link">

                <div class="row">

                  <div class = "col-md-6 col-sm-12 col-xs-12 form-group">

                    {{-- Area to add code and submit to get ticket info --}}
                    <div class="form-group has-feedback code-input">

                      <input type="text" ng-model="code" id="codeInput" class="code-search-txt form-control" placeholder="Ticket Code" required maxlength="4">

                      {{-- Spinner icon while request being sent --}}
                      <div class="code-submit" ng-show="loading"><i class="fa fa-spinner fa-lg fa-pulse"></i></div>

                      {{-- Submit button for check-in ticket code --}}
                      <button type="button" ng-hide="loading" class="code-submit" ng-click="ajaxCode(code)">
                        <i class="fa fa-arrow-right"></i>
                      </button>

                    </div>

                  </div>

                  {{-- Success message with prompt to continue --}}
                  <div class = "col-md-6 col-sm-12 col-xs-12 form-group">

                    <div ng-show="success === true" class="form-group group-size" ng-repeat="(key, ticket) in ticketData.types | filter:ticketInventoryId">

                      <div class="card card-block">

                        <div class="row check-in-success">

                          <h4>Check In Successful</h4>
                          {{-- Shows ticket type, how many purchased and how many available to use --}}

                          <p class="purchased-ticket-date">
                            @{{ticket.name}}
                          </p>

                          <p class="purchased-ticket-info">
                            X @{{ ticket.qty.used }} Used
                          </p>

                          <p class="purchased-ticket-info">
                            X @{{ ticket.qty.available }} Remaining
                          </p>

                        </div>

                      </div>

                    </div>

                    {{-- Error Messages --}}

                    <div class="form-group has-feedback code-input" ng-show="codeLookup === true">
                      <div class="alert-check-in alert-danger" role="alert">

                        <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>

                        <span class="sr-only">Error:</span>

                        Enter A Valid Code

                      </div>
                    </div>

                  </div>

                </div>

                {{-- Start of corresponding ticket information --}}
                <div class="row">

                  <div class = "col-md-6 col-xs-12">

                    <div class="form-group group-size" ng-if="ticketData" ng-repeat="(key, ticket) in ticketData.types">

                      <div class="card card-block">

                        <div class="row code-confirm-container">

                          <div class="col-md-10">

                            {{-- Type of ticket purchased --}}
                            <span class="card-text ticket-type-label" ng-model="ticketName">@{{ ticket.name }}</span>

                            {{-- Date and location --}}
                            <span class="card-text ticket-type-confirm-text">@ @{{ ticketData.event.venueName }} </span>

                            <span class="label label-success ticket-type-date" ng-if="ticketData.eventTime.start">@{{ ticketData.eventTime.start | parseTimeDataFilter | date:'EEE. MMM d, y' }}</span>

                            {{-- Input for number of people entering --}}
                            <div class="ticket-confirm-amount-div">

                              <span>Admit </span>

                              <input type='text' name="usedTicket" id="usedTicket" data-role="ticket-admit-@{{$index}}" class='ticket-type-confirm-amount'>

                              <span class="ticket-type-confirm-amount"> / </span>

                              <span class="ticket-amount-purchased" ng-model="available">@{{ ticket.qty.available }}</span>

                            </div>

                          </div>

                          <div class="col-md-2">

                            <div class="code-confirm">
                              <i class="fa fa-caret-up ticket-type-increment" ng-click="increaseAdmit($index)"></i>
                            </div>

                            <div class="code-confirm">
                              <i class="fa fa-caret-down ticket-type-increment" ng-click="decreaseAdmit($index)"></i>
                            </div>

                          </div>

                        </div>

                      </div>

                      <button ng-if="ticket.qty.available !== 0" ng-click="ajaxCodeUse(ticketData.code, usedTicket, ticket.ticketInventoryId)" class="check-in-btn">Check-In</button>

                      <div ng-if="ticket.qty.available === 0" class="alert-no-ticket alert-danger" role="alert">

                        <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>

                        <span class="sr-only">Error:</span>

                        All Tickets Have Been Used For This Event

                      </div>

                    </div>

                  </div>

                </div>

              </div>

            </div>

          </div>

        </div>

      </div>

      {{-- Start history of people who have checked in with search queried by code --}}
      <div class="grid-results" ng-show="tab === 'history'">

        <div class="container">

          <div class="row">

            <div class="col-md-12">

              <div class="poll-card manage-link">

                <div class="history-search navbar">

                  <div class="container-fluid">

                    <div class="navbar-form navbar-left">

                      {{-- Search for ticket by code --}}
                      <div class="form-group">
                        <input type="text" ng-model="search.code" class="friend-search-bar-bg-color form-control" placeholder="Search">
                      </div>

                    </div>

                  </div>

                </div>

                {{-- Search results, all showing if none entered --}}
                <div class="row">

                  <div class="col-xs-12 col-sm-6 col-md-12 purchased-ticket-box" ng-repeat="usedTicket in userTickets | filter:search:strict">

                    <div class="check-in-card manage-link">

                      {{-- Main header includes ticket code and date --}}
                      <div class="history-title">

                        <span class="code-number">@{{ usedTicket.code }}</span>

                        <span class="ticket-type-confirm-text history-venue"> @ @{{ usedTicket.event.venueName }}</span>

                      </div>

                      <div class="history-container">

                        <div class="row" ng-repeat="(key, ticket) in usedTicket.types">

                          {{-- Shows ticket type, how many purchased and how many available to use --}}
                          <div class="col-md-7 col-sm-7 col-xs-12">

                            <p class="purchased-ticket-date">@{{ ticket.name }}</p>

                            <span class="purchased-ticket-info">Used: @{{ ticket.qty.used }}</span>

                            <span class="purchased-ticket-info history-available">Available: @{{ ticket.qty.available }}</span>

                          </div>

                          <div class="col-md-5 col-sm-5 col-xs-12">

                            <span class="label label-success" style="float: right; margin: 1px 7px 0 0;" ng-if="usedTicket.eventTime.start">@{{ usedTicket.eventTime.start | parseTimeDataFilter | date:'EEE. MMM d, y' }}</span>

                            <span class="purchased-ticket-info history-date-time" ng-if="usedTicket.eventTime.start">@{{ usedTicket.eventTime.start | parseTimeDataFilter | date:'h:mm a' }}</span>

                          </div>

                        </div>

                      </div>

                    </div>

                  </div>

                </div>

              </div>

            </div>

          </div>

        </div>

      </div> {{-- End of history --}}

    </div>

  </div>

@stop
