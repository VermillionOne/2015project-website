@extends('layouts.master')

@section('javascript-includes')
  <script src="{{ ViewHelper::asset('assets/dist/lib-accounts-profile.js') }}"></script>

  <script type="text/javascript">
    var schedules = JSON.parse('{!! json_encode($scheduleEdit) !!}');
    var attendees = JSON.parse('{!! json_encode($dateAttendee) !!}');
  </script>

@stop

@section('content')
<div class="view-event-create" ng-controller="SchedulesController" ng-cloak class="ng-cloak">

  {{-- If error is returned from deletion of schedule or date will show --}}
  <div class="alert alert-danger" align="center" ng-if="errorMessage">
    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
    @{{errorMessage}}
  </div>

  <div class="alert alert-success" align="center" ng-if="successMessage">
    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
    @{{successMessage}}
  </div>

  @if(Session::has('fail_message'))
    <div class="alert alert-danger" align="center">
      <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
      @{{ errorMessage }}
    </div>
  @endif

  {{-- Session Message --}}
  @include('includes.sessionStatus')

  <div class="results-body">

    <div class="container">

      <div class="dashboard-titles">

        <div class="row">

          <div class="col-md-8 col-sm-6 col-xs-12">

            <h2 class="results-title-txt">Schedules : <a class="manage-link" href="{{ url('events') }}/{{ $event['slug'] }}">{{ $event['title'] }}</a></h2>

          </div>

          <div class="col-md-4 col-sm-6 col-xs-12">

            @include('accounts.dashboard.includes.editnav')

            <a class="clear-form search-clear" href="{{ route('schedules.showCreate', ['event' => $event['id']]) }}">Add Schedule</a>

          </div>

        </div>

      </div>

      @if (empty($event['schedules']))
        <div class="row">
          <div class="col-md-12">
            <div class="poll-card manage-link">

              {{-- If no schedules, default will show --}}
              <div class="default-schedule-message">
                <h5 class="text-muted">There Are No Schedules For This Event</h5>
                <h5 class="text-muted">Add Schedule <a href="{{ route('schedules.showCreate', ['event' => $event['id']]) }}" class="">Here</a></h5>
              </div>

            </div>
          </div>
        </div>
      @else

        <div class="row" ng-repeat="times in schedules">
          <div class="col-md-12">

            <div class="panel-group schedule-panel" id="accordion" role="tablist" aria-multiselectable="true">
              <div class="panel panel-primary ">
                <div class="panel-heading" role="tab" id="heading@{{times.id}}">

                  <div class="row">

                    <div class="col-sm-9 col-xs-12">

                      {{-- Opens accordian containing times of events, Times id makes link specific to date --}}
                      <a role="button" class="times-option-label manage-link schedule-text" data-toggle="collapse" data-parent="#accordion" href="#collapse@{{times.id}}" title="Show List" aria-expanded="true" aria-controls="collapse@{{times.id}}">
                        @{{ times.label }}
                      </a>

                    </div>

                    <div class="col-sm-3 col-xs-12 options-div">

                      {{-- Deletes specific schedule --}}
                      <a class="times-option-list"
                         ng-click="deleteSchedule(times.eventId, times.id, times)"
                         title="Delete Schedule"
                         id="@{{times.id}}">
                        <i class="fa fa-times"></i>
                      </a>

                      {{-- Edit link for specific schedule --}}
                      <a class="times-option-list"
                         href="events/@{{ times.eventId }}/schedules/@{{times.id}}"
                         title="Edit Schedule">
                        <i class="fa fa-pencil-square-o"></i>
                      </a>

                    </div>

                  </div>

                </div>

                {{-- Start of dropdown for accordian containing times --}}
                <div id="collapse@{{times.id}}" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading@{{times.id}}">
                  <div class="panel-body">



                    {{-- If attendees exist show user information --}}
                    <table class="analytics-table table table-condensed table-responsive">

                      <thead class="">
                        <tr class="">

                          <th class="title-link schedule-width">Start Time</th>
                          <th class="title-link schedule-width">End Time</th>

                          {{-- If user has only one date for schedule, can edit schedule through main action bar --}}
                          <th ng-if="times.times.length > 1" class="title-link">Actions</th>

                        </tr>
                      </thead>

                      <tbody class="">

                          {{-- Shows when event was last updated, start and end dates and times for individual dates --}}
                          <tr ng-repeat="time in times.times">

                            <td class="schedule-width">
                              <p class="schedule-text schedule-float-mobile" ng-if="time.start"><b>Date: </b>
                              @{{ time.start | parseTimeDataFilter | date:'EEE. MMM d, y' }}&nbsp;</p>
                              <p class="schedule-text schedule-float-inherit-mobile" ng-if="time.start"><b>Time: </b>
                              @{{ time.start | parseTimeDataFilter | date:'h:mm a' }}</p>
                            </td>

                            <td class="schedule-width">
                              <p class="schedule-text schedule-float-mobile" ng-if="time.end"><b>Date: </b>
                              @{{ time.end | parseTimeDataFilter | date:'EEE. MMM d, y' }}&nbsp;</p>

                              <p class="schedule-text schedule-float-inherit-mobile" ng-if="time.end"><b>Time: </b>
                              @{{ time.end | parseTimeDataFilter | date:'h:mm a' }}</p>
                            </td>

                            {{-- If user has only one date for schedule, can edit schedule through main action bar --}}
                              <td ng-if="times.times.length > 1">

                                <a class="manage-link individual-schedule-option"
                                   ng-class="{'disabled' : errorMessage != undefined}"
                                   ng-click="deleteSingleDate(time.id, times.eventId, times.id, time)"
                                   title="Delete Date"
                                   id = "@{{time.id}}"
                                   ng-model="deleteDate">
                                  <i class="fa fa-times"></i>
                                </a>

                              </td>


                          </tr>

                      </tbody>

                    </table>

                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

      @endif

      {{-- Previous and next buttons to match width of results --}}
      <div class="container">
        <div class="col-md-12 schedule-padding">
          {{-- Pagination for more than twenty photos in gallery --}}
          @include('pages.default-pagination', ['paginator' => $schedules])

        </div>
      </div>

    </div>
  </div>
</div>
@stop
