<?php if (!isset($summary)) $summary = 'create_event_summary'; ?>
<?php if (!isset($summaryLocked)) $summaryLocked = true; ?>

  <div class="create-event-form-box">

    <div class="row">
      {{-- BEGIN Event Summary input Fields --}}
      <h3 class="col-sm-12 create-event-form-main-title">Event Summary</h3>

      {{-- Adds margin matching the rest of the forms around elements --}}
      <div class="col-sm-10 col-sm-offset-1">

        {{-- BEGIN Poll Question Option --}}
        <div class="panel create-event-panel">

          {{-- Panel Heading Container --}}
          <div class="panel-heading row flat">

            {{-- Panel Header --}}
            <p class="panel-title pull-left">Details</p>

            {{-- Summary Section Edit Button --}}
            <button class="btn btn-small pull-right btn-suaray" ng-click="tab = 'details'"><i class="fa fa-edit"></i>&nbsp;Edit</button>

          </div>

          <div class="create-event-summary-event-details">

            {{-- Div Row for containing form elements --}}
            <div class="row panel-content">

              <h4 class="col-sm-10 col-sm-offset-1">Event Title</h4>

              <p class="col-sm-10 col-sm-offset-1 create-event-summary-element">@{{summaryItems.title}}<span ng-show="!summaryItems.title" class="edit-tickets-warn-text">The title field is required.</span></p>

              @if(Session::has('title'))
                <p class="edit-tickets-warn-text col-sm-10 col-sm-offset-1">{{ Session::get('title') }}</p>
              @endif

              <h4 class="col-sm-10 col-sm-offset-1">Description</h4>

              <p class="col-sm-10 col-sm-offset-1 create-event-summary-element">
                {{-- @{{markdownDescription}} --}}
                @{{summaryItems.description}}
                <span ng-show="!summaryItems.description" class="edit-tickets-warn-text">The title field is required.</span>
              </p>

              <h4 class="col-sm-10 col-sm-offset-1">Time Zone</h4>

              <p class="col-sm-10 col-sm-offset-1 create-event-summary-element">
                @{{summaryItems.timeZoneId}}
              </p>

              <div class="col-sm-5 col-sm-offset-1">

                <h4 class="">Start Time</h4>
                <p class="create-event-time-and-date-summary">
                  <i class="glyphicon glyphicon-calendar "></i><span ng-show="!summaryItems.startDate" class="edit-tickets-warn-text">The start date field is required.</span><span class="create-event-summary-element">&nbsp;@{{summaryItems.startDate}}</span>
                </p>
                <p class="create-event-time-and-date-summary">
                  <i class="glyphicon glyphicon-time "></i><span ng-if="!summaryItems.startTime" class="edit-tickets-warn-text">The start time field is required.</span><span class="create-event-summary-element">&nbsp;@{{summaryItems.startTime}}&nbsp;<span ng-show="summaryItems.startMeridian === 'am'">AM</span><span ng-show="summaryItems.startMeridian === 'pm'">PM</span></span>
                </p>

              </div>

              <div class="col-sm-5">
                <h4 class="">End Time</h4>
                <p class="create-event-time-and-date-summary">
                  <i class="glyphicon glyphicon-calendar "></i><span ng-show="!summaryItems.endDate" class="edit-tickets-warn-text">The end date field is required.</span><span class="create-event-summary-element">&nbsp;@{{summaryItems.endDate}}</span>
                </p>
                <p class="create-event-time-and-date-summary">
                  <i class="glyphicon glyphicon-time "></i><span ng-if="!summaryItems.endTime" class="edit-tickets-warn-text">The end time field is required.</span><span class="create-event-summary-element">&nbsp;@{{summaryItems.endTime}}&nbsp;<span ng-show="summaryItems.endMeridian === 'am'">AM</span><span ng-show="summaryItems.endMeridian === 'pm'">PM</span></span>
                </p>

              </div>

              <h4 class="col-sm-10 col-sm-offset-1" ng-if="summaryItems.repeatEnabled">Recurring Times</h4>

              <p class="col-sm-10 col-sm-offset-1 create-event-summary-element" ng-if="summaryItems.repeatEnabled">Event recurring every
                <span ng-if="summaryItems.repeatEvery > 1">@{{summaryItems.repeatEvery}}</span>
                <span ng-if="summaryItems.repeatRepeats === 'daily'">day<span ng-if="summaryItems.repeatEvery > 1">s</span></span>
                <span ng-if="summaryItems.repeatRepeats === 'weekly'">week<span ng-if="summaryItems.repeatEvery > 1">s</span></span>
                <span ng-if="summaryItems.repeatRepeats === 'monthly'">month<span ng-if="summaryItems.repeatEvery > 1">s</span></span>
                <span ng-if="summaryItems.repeatRepeats === 'yearly'">year<span ng-if="summaryItems.repeatEvery > 1">s</span></span>
                <span class="create-event-recurring-weekdays" ng-if="summaryItems.repeatRepeats === 'weekly'">on:<br>
                  <span class="summary-text-block" data-role="recurringSun" ng-if="recurringDaysOfWeek.sun === true">Sunday</span>
                  <span class="summary-text-block" data-role="recurringMon" ng-if="recurringDaysOfWeek.mon === true">Monday</span>
                  <span class="summary-text-block" data-role="recurringTue" ng-if="recurringDaysOfWeek.tue === true">Tuesday</span>
                  <span class="summary-text-block" data-role="recurringWed" ng-if="recurringDaysOfWeek.wed === true">Wednesday</span>
                  <span class="summary-text-block" data-role="recurringThu" ng-if="recurringDaysOfWeek.thu === true">Thursday</span>
                  <span class="summary-text-block" data-role="recurringFri" ng-if="recurringDaysOfWeek.fri === true">Friday</span>
                  <span class="summary-text-block" data-role="recurringSat" ng-if="recurringDaysOfWeek.sat === true">Saturday</span>
                </span>
                {{-- <span ng-if="summaryItems.repeatRepeats === 'monthly'">on:<br>
                  <span ng-show="summaryItems.repeatMonths === 'dayOfMonth'">day @{{summaryItems.start.date | limitTo:2:4}} of the month</span>
                  <span ng-show="summaryItems.repeatMonths === 'dayOfWeek'"> the "nth" "weekday" of the month</span>
                </span> --}}
              </p>

              <h4 class="col-sm-10 col-sm-offset-1" ng-if="summaryItems.finalEndDate">Final End Date</h4>

              <p class="col-sm-10 col-sm-offset-1 create-event-summary-element">
                @{{summaryItems.finalEndDate}}
              </p>

              <h4 class="col-sm-10 col-sm-offset-1">Category</h4>
              <p class="col-sm-10 col-sm-offset-1 create-event-summary-element "><span class="summary-text-block">@{{summaryItems.category}}</span></p>

              <h4 class="col-sm-10 col-sm-offset-1">Tags</h4>
              <p class="col-sm-10 col-sm-offset-1 create-event-summary-element "><span class="summary-text-block" ng-repeat="tag in summaryItems.tags track by $index">@{{tag}}</span></p>

              <p ng-if="!summaryItems.tags" class="edit-tickets-warn-text col-sm-10 col-sm-offset-1">The tags field is required.</p>

              <h4 class="col-sm-10 col-sm-offset-1">Other Features</h4>
              <p class="col-sm-3 col-sm-offset-1 create-event-summary-element create-event-other-feature" ng-show="summaryItems.isIndoor">Indoor</p>
              <p class="col-sm-3 col-sm-offset-1 create-event-summary-element create-event-other-feature" ng-show="summaryItems.isOutdoor">Outdoor</p>

              <p class="col-sm-10 col-sm-offset-1 create-event-summary-element">
                <span class="create-event-recurring-weekdays">
                  <span class="summary-text-block" ng-show="summaryItems.isAge0">All Ages</span>
                  <span class="summary-text-block" ng-show="summaryItems.isAge13">13 +</span>
                  <span class="summary-text-block" ng-show="summaryItems.isAge16">16 +</span>
                  <span class="summary-text-block" ng-show="summaryItems.isAge18">18 +</span>
                  <span class="summary-text-block" ng-show="summaryItems.isAdult">21 +</span>
                </span>
                {{-- <span ng-if="summaryItems.repeatRepeats === 'monthly'">on:<br>
                  <span ng-show="summaryItems.repeatMonths === 'dayOfMonth'">day @{{summaryItems.start.date | limitTo:2:4}} of the month</span>
                  <span ng-show="summaryItems.repeatMonths === 'dayOfWeek'"> the "nth" "weekday" of the month</span>
                </span> --}}
              </p>

            </div>

          </div>

        </div>

        <div class="panel create-event-panel">

          {{-- Panel Heading Container --}}
          <div class="panel-heading row flat">

            {{-- Panel Header --}}
            <p class="panel-title pull-left">Location</p>

            <button class="btn btn-small pull-right btn-suaray" ng-click="tab = 'location'"><i class="fa fa-edit"></i>&nbsp;Edit</button>

          </div>

          <div class="create-event-summary-event-details">

            {{-- Div Row for containing form elements --}}
            <div class="row panel-content">

              <h4 class="col-sm-10 col-sm-offset-1">Venue Name</h4>

                <p class="col-sm-10 col-sm-offset-1 create-event-summary-element">@{{summaryItems.venue}}</p>

              <h4 class="col-sm-10 col-sm-offset-1">Address</h4>

                <p class="col-sm-10 col-sm-offset-1 create-event-summary-element">@{{summaryItems.address}}</p>
                @if(Session::has('address1'))
                  <p class="edit-tickets-warn-text col-sm-10 col-sm-offset-1">{{ Session::get('address1') }}</p>
                @endif

                <p ng-if="!summaryItems.address" class="edit-tickets-warn-text col-sm-10 col-sm-offset-1">The address field is required.</p>

              <div class="col-sm-6 col-sm-offset-1">
                <h4 class="">City</h4>

                  <p class="create-event-summary-element">@{{summaryItems.city}}</p>

              </div>

              <div class="col-sm-2">
                <h4 class="">State</h4>

                  <p class=" create-event-summary-element">@{{summaryItems.state}}</p>

              </div>

              <div class="col-sm-3">
                <h4 class="">Zip</h4>

                  <p class=" create-event-summary-element">@{{summaryItems.zipcode}}</p>
                  @if(Session::has('zipcode'))
                    <p class="edit-tickets-warn-text">{{ Session::get('zipcode') }}</p>
                  @endif

                  <p ng-if="!summaryItems.zipcode" class="edit-tickets-warn-text">The zipcode field is required.</p>

              </div>

            </div>

          </div>

        </div>

        <div class="panel create-event-panel">

          {{-- Panel Heading Container --}}
          <div class="panel-heading row flat">

            {{-- Panel Header --}}
            <p class="panel-title pull-left">Options</p>

            {{-- Summary Section Edit Button --}}
            <button class="btn btn-small pull-right btn-suaray" ng-click="tab = 'options'"><i class="fa fa-edit"></i>&nbsp;Edit</button>

          </div>

          <div class="create-event-summary-event-details">

            {{-- Div Row for containing form elements --}}
            <div class="row panel-content">

              <h4 class="col-sm-10 col-sm-offset-1">Options Enabled</h4>

              <div class="col-sm-10 col-sm-offset-1">

                <div class="create-event-summary-element create-event-option-item" ng-show="pollQuestion === 'true'">
                  <p>
                    <span class="fa fa-check fa-1x"></span>&nbsp;&nbsp;&nbsp;&nbsp;Polls
                  </p>
                </div>

                <div class="create-event-summary-element create-event-option-item" ng-show="comments === 'true'">
                  <p>
                    <span class="fa fa-check fa-1x"></span>&nbsp;&nbsp;&nbsp;&nbsp;Comments
                  </p>
                </div>

                <div class="create-event-summary-element create-event-option-item" ng-show="maps === 'true'">
                  <p>
                    <span class="fa fa-check fa-1x"></span>&nbsp;&nbsp;&nbsp;&nbsp;Maps
                  </p>
                </div>

                <div class="create-event-summary-element create-event-option-item" ng-show="guestImages === 'true'">
                  <p>
                    <span class="fa fa-check fa-1x"></span>&nbsp;&nbsp;&nbsp;&nbsp;Event Pictures
                  </p>
                </div>

                <div class="create-event-summary-element create-event-option-item" ng-show="reviews === 'true'">
                  <p>
                    <span class="fa fa-check fa-1x"></span>&nbsp;&nbsp;&nbsp;&nbsp;Reviews
                  </p>
                </div>

                <div class="create-event-summary-element create-event-option-item" ng-show="rsvp === 'true'">
                  <p>
                    <span class="fa fa-check fa-1x"></span>&nbsp;&nbsp;&nbsp;&nbsp;RSVP
                  </p>
                </div>

                <div class="create-event-summary-element create-event-option-item" ng-show="tickets === 'true'">
                  <p>
                    <span class="fa fa-check fa-1x"></span>&nbsp;&nbsp;&nbsp;&nbsp;Tickets
                  </p>
                </div>

                <div class="create-event-summary-element create-event-option-item" ng-show="transportation === 'true'">
                  <p>
                    <span class="fa fa-check fa-1x"></span>&nbsp;&nbsp;&nbsp;&nbsp;Transportation
                  </p>
                </div>

                <div class="create-event-summary-element create-event-option-item" ng-show="share === 'true'">
                  <p>
                    <span class="fa fa-check fa-1x"></span>&nbsp;&nbsp;&nbsp;&nbsp;Share
                  </p>
                </div>

                <div class="create-event-summary-element create-event-option-item" ng-show="guestList === 'true'">
                  <p>
                    <span class="fa fa-check fa-1x"></span>&nbsp;&nbsp;&nbsp;&nbsp;Guest List
                  </p>
                </div>

                <div class="create-event-summary-element create-event-option-item" ng-show="guestVideos === 'true'">
                  <p>
                    <span class="fa fa-check fa-1x"></span>&nbsp;&nbsp;&nbsp;&nbsp;Guest Videos
                  </p>
                </div>

                <div class="create-event-summary-element create-event-option-item" ng-show="weather === 'true'">
                  <p>
                    <span class="fa fa-check fa-1x"></span>&nbsp;&nbsp;&nbsp;&nbsp;Weather
                  </p>
                </div>

              </div>

            </div>

        </div>

      </div>

      <div class="panel create-event-panel">

        {{-- Panel Heading Container --}}
        <div class="panel-heading row flat">

          {{-- Panel Header --}}
          <p class="panel-title pull-left">Media</p>

          {{-- Summary Section Edit Button --}}
          <button class="btn btn-small pull-right btn-suaray" ng-click="tab = 'media'"><i class="fa fa-edit"></i>&nbsp;Edit</button>

        </div>

        <div class="create-event-summary-event-details">

          {{-- Div Row for containing form elements --}}
          <div class="row panel-content">

            <h4 class="col-sm-10 col-sm-offset-1">Photos Uploaded</h4>

            {{-- Message displayed if no photos added --}}
            <p ng-if="!files" class="edit-tickets-warn-text col-sm-10 col-sm-offset-1">At least one photo is required.</p>

            {{-- List of event photos that have been uploaded --}}
            <div ng-repeat="(index, file) in files">
              <p class="col-sm-10 col-sm-offset-1 create-event-summary-element">@{{file.name}}</p>
            </div>


            <h4 class="col-sm-10 col-sm-offset-1" ng-if="event.premium === 1">Youtube Videos</h4>

            {{-- List of event photos that have been uploaded --}}
            <div ng-repeat="code in summaryItems.videoEmbed.youtube track by $index" ng-if="event.premium === 1">
              <p class="col-sm-10 col-sm-offset-1 create-event-summary-element">@{{code}}</p>
            </div>

            <h4 class="col-sm-10 col-sm-offset-1" ng-if="event.premium === 1">Vimeo Videos</h4>

            {{-- List of event photos that have been uploaded --}}
            <div ng-repeat="code in summaryItems.videoEmbed.vimeo track by $index" ng-if="event.premium === 1">
              <p class="col-sm-10 col-sm-offset-1 create-event-summary-element">@{{code}}</p>
            </div>

          </div>
        </div>
      </div>

        <div class="panel create-event-panel" ng-show="free === null">

        {{-- Panel Heading Container --}}
        <div class="panel-heading row flat">

          {{-- Panel Header --}}
          <p class="panel-title pull-left">Billing</p>

          {{-- Summary Section Edit Button --}}
          <button class="btn btn-small pull-right btn-suaray" ng-click="tab = 'options'"><i class="fa fa-edit"></i>&nbsp;Edit</button>

        </div>

        <div class="create-event-summary-event-details">

          {{-- Div Row for containing form elements --}}
          <div class="row panel-content">

            @if (isset($userCard))

              <h4 class="col-sm-10 col-sm-offset-1">Using card on file</h4>

            @else

              <h4 class="col-sm-10 col-sm-offset-1">Cardholder Name</h4>
              <p class="col-sm-10 col-sm-offset-1 create-event-summary-element">@{{summaryItems.billingCardName}}</p>

              <div class="col-sm-5 col-sm-offset-1">
                <h4 class="">Last Four</h4>
                <p class="create-event-summary-element">XXXX-XXXX-XXXX-@{{summaryItems.billingCardNumber | limitTo:-4}}</p>
              </div>

              <div class="col-sm-5">
                <h4 class="">Expiration Date</h4>
                <p class="create-event-summary-element">@{{summaryItems.billingCardExpMonth}} / @{{summaryItems.billingCardExpYear}}</p>
              </div>

              <div class="col-sm-10 col-sm-offset-1">
                <h4 class="">Card Billing Address</h4>
                <p class="create-event-summary-element">@{{summaryItems.billingCardAddress}}</p>
              </div>

              <div class="col-sm-4 col-sm-offset-1">
                <h4 class="">City</h4>
                <p class="create-event-summary-element">@{{summaryItems.billingCardCity}}</p>
              </div>

              <div class="col-sm-3">
                <h4 class="">State</h4>
                <p class="create-event-summary-element">@{{summaryItems.billingCardState}}</p>
              </div>

              <div class="col-sm-3">
                <h4 class="">Zipcode</h4>
                <p class="create-event-summary-element">@{{summaryItems.billingCardZipcode}}</p>
              </div>

            @endif

          </div>

        </div>

      </div>

    </div>

    {{-- 'NEXT >' Button to continue along form --}}
    <div class="form-group">

      <div class="col-sm-10 col-sm-offset-1">
        @if (isset($event['title']))
          <button class="btn btn-suaray btn-suaray-positive btn-lg pull-right" ng-disabled="createFormFree.$invalid" type="submit">Update Event&nbsp;<span class="glyphicon glyphicon-chevron-right"></span></button>
        @else
          {!! Form::hidden('isPublished', true) !!}
          <button class="btn btn-suaray btn-suaray-positive btn-lg pull-right" ng-disabled="createFormFree.$invalid" type="submit" name="create-event">Create Event&nbsp;<span class="glyphicon glyphicon-chevron-right"></span></button>
        @endif
      </div>

    </div>
   </div>
  </div>
