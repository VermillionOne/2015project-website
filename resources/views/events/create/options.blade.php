<?php if (!isset($options)) $options = 'create_event_options'; ?>
<?php if (!isset($optionsLocked)) $optionsLocked = true; ?>

  <div class="create-event-form-box">

    <div class="row">
      {{-- BEGIN Form Elements --}}
      <h3 class="col-sm-12 create-event-form-main-title">Options</h3>

      {{-- Adds margin matching the rest of the forms around elements --}}
      <div class="col-sm-10 col-sm-offset-1">

        {{-- BEGIN Comment Option --}}
        <div class="panel create-event-panel" data-role="show-info-container">

          {{-- Panel Heading Container --}}
          <div class="panel-heading row">

            {{-- Panel Header --}}
            <p class="panel-title pull-left col-xs-12 col-sm-9">Comments
            </p>
            <i class="fa fa-question-circle fa-lg suaray-popover" tabindex="0" role="button" data-toggle="suaray-popover" data-trigger="focus" title="Comments" data-content="With this option turned on, visitors to your event page will be able to post comments about your event."></i>

            {{-- On/Off Button Group --}}
            <div class="btn-group pull-right" data-toggle="buttons">

              {{-- Option On Button --}}
              <label class="btn btn-suaray btn-suaray-discreet create-event-option-on" ng-click="comments = 'true'">
                {!! Form::radio('meta[comments][enabled]', true, false, ['data-role' => 'comment-on', 'autocomplete' => 'off']) !!} On
              </label>

              {{-- Option Off Button --}}
              <label class="btn btn-suaray btn-suaray-discreet create-event-option-off" ng-click="comments = 'null'">
                {!! Form::radio('meta[comments][enabled]', false, false, ['data-role' => 'comment-off', 'autocomplete' => 'off']) !!} Off
              </label>

            </div>
          </div>

          {{-- Collapsible Comment Options Content --}}
          <div class="collapse" data-role="show-info" aria-expanded="false">

            {{-- Close Button for closing content --}}
            <button type="button" class="close" aria-label="Close" data-role="show-info-button"><span class="fa fa-times fa-lg"></span></button>

            {{-- Div Row for containing form elements --}}
            <div class="row panel-content">
              <p>This option allows you users to leave comments about the event on your event page.</p>
            </div>

          </div>
        </div>


        {{-- BEGIN Maps Option --}}
        <div class="panel create-event-panel" data-role="show-info-container" >

          {{-- Panel Heading Container --}}
          <div class="panel-heading row">

            {{-- Panel Header --}}
            <p class="panel-title pull-left col-xs-12 col-sm-9">Map
            </p>
            <i class="fa fa-question-circle fa-lg suaray-popover" tabindex="0" role="button" data-toggle="suaray-popover" data-trigger="focus" title="Map" data-content="This gives you the option of displaying a map of where your event will be held based on the address you have provided."></i>

            {{-- Collapse button for Poll info --}}
            {{-- <a class="btn btn-suaray btn-suaray-discreet"  data-role="show-info-button" ng-click="">More Info</a> --}}

            {{-- On/Off Button Group --}}
            <div class="btn-group pull-right" data-toggle="buttons">

              {{-- Option On Button --}}
              <label class="btn btn-suaray btn-suaray-discreet create-event-option-on" ng-click="maps = 'true'">
                {!! Form::radio('meta[maps][enabled]', true, false, ['data-role' => 'maps-on', 'autocomplete' => 'off']) !!} On
              </label>

              {{-- Option Off Button --}}
              <label class="btn btn-suaray btn-suaray-discreet create-event-option-off" ng-click="maps = 'null'">
                {!! Form::radio('meta[maps][enabled]', false, false, ['data-role' => 'maps-off', 'autocomplete' => 'off']) !!} Off
              </label>

            </div>
          </div>

          {{-- Collapsible Maps Options Content --}}
          <div class="collapse" data-role="show-info" aria-expanded="false">

            {{-- Close Button for closing content --}}
            <button type="button" class="close" aria-label="Close" data-role="show-info-button"><span class="fa fa-times fa-lg"></span></button>

            {{-- Div Row for containing form elements --}}
            <div class="row panel-content">
              <p></p>
            </div>

          </div>
        </div>

        {{-- BEGIN Guest Pictures Option --}}
        <div class="panel create-event-panel" data-role="show-info-container" >

          {{-- Panel Heading Container --}}
          <div class="panel-heading row">

            {{-- Panel Header --}}
            <p class="panel-title pull-left col-xs-12 col-sm-9">Event Pictures
            </p>
            <i class="fa fa-question-circle fa-lg suaray-popover" tabindex="0" role="button" data-toggle="suaray-popover" data-trigger="focus" title="Event Pictures" data-content="Choose whether to allow visitors view photos you've uploaded for your event."></i>

            {{-- Collapse button for Poll info --}}
            {{-- <a class="btn btn-suaray btn-suaray-discreet" data-role="show-info-button" ng-click="">More Info</a> --}}

            {{-- On/Off Button Group --}}
            <div class="btn-group pull-right" data-toggle="buttons">

              {{-- Option On Button --}}
              <label class="btn btn-suaray btn-suaray-discreet create-event-option-on" ng-click="guestImages = 'true'" >
                {!! Form::radio('meta[notNow][guestPicturesOptions]', true, false, ['data-role' => 'guest-pictures-on', 'autocomplete' => 'off']) !!} On
              </label>

              {{-- Option Off Button --}}
              <label class="btn btn-suaray btn-suaray-discreet create-event-option-off" ng-click="guestImages = 'null'" >
                {!! Form::radio('meta[notNow][guestPicturesOptions]', false, false, ['data-role' => 'guest-pictures-off', 'autocomplete' => 'off']) !!} Off
              </label>

            </div>
          </div>

          {{-- Collapsible Guest Pictures Options Content --}}
          <div class="collapse" data-role="show-info" aria-expanded="false">

            {{-- Close Button for closing content --}}
            <button type="button" class="close" aria-label="Close" data-role="show-info-button"><span class="fa fa-times fa-lg"></span></button>

            {{-- Div Row for containing form elements --}}
            <div class="row panel-content">

            </div>

          </div>
        </div>

        {{-- BEGIN Reviews Option --}}
        <div class="panel create-event-panel" data-role="show-info-container" >

          {{-- Panel Heading Container --}}
          <div class="panel-heading row">

            {{-- Panel Header --}}
            <p class="panel-title pull-left col-xs-12 col-sm-9">Reviews
            </p>
            <i class="fa fa-question-circle fa-lg suaray-popover" tabindex="0" role="button" data-toggle="suaray-popover" data-trigger="focus" title="Reviews" data-content="Allow attendees to rate and leave a review of your event."></i>

            {{-- Collapse button for Poll info --}}
            {{-- <a class="btn btn-suaray btn-suaray-discreet"  data-role="show-info-button" ng-click="">More Info</a> --}}

            {{-- On/Off Button Group --}}
            <div class="btn-group pull-right" data-toggle="buttons">

              {{-- Option On Button --}}
              <label class="btn btn-suaray btn-suaray-discreet create-event-option-on" ng-click="reviews = 'true'" >
                {!! Form::radio('meta[reviews][enabled]', true, false, ['data-role' => 'reviews-on', 'autocomplete' => 'off']) !!} On
              </label>

              {{-- Option Off Button --}}
              <label class="btn btn-suaray btn-suaray-discreet create-event-option-off" ng-click="reviews = 'null'" >
                {!! Form::radio('meta[reviews][enabled]', false, false, ['data-role' => 'reviews-off', 'autocomplete' => 'off']) !!} Off
              </label>

            </div>

          </div>

          {{-- Collapsible Reviews Options Content --}}
          <div class="collapse" data-role="show-info" aria-expanded="false">

            {{-- Close Button for closing content --}}
            <button type="button" class="close" aria-label="Close" data-role="show-info-button"><span class="fa fa-times fa-lg"></span></button>

            {{-- Div Row for containing form elements --}}
            <div class="row panel-content">
              <p>This options allows your </p>
            </div>

          </div>
        </div>

        {{-- BEGIN RSVP Option --}}
        <div class="panel create-event-panel" data-role="show-info-container">

          {{-- Panel Heading Container --}}
          <div class="panel-heading row">

            {{-- Panel Header --}}
            <p class="panel-title pull-left col-xs-12 col-sm-9">RSVP
            </p>
            <i class="fa fa-question-circle fa-lg suaray-popover" tabindex="0" role="button" data-toggle="suaray-popover" data-trigger="focus" title="RSVP" data-content="Allow visitors to your event page RSVP to your event. This is helpful for getting a head count."></i>

            {{-- On/Off Button Group --}}
            <div class="btn-group pull-right" data-toggle="buttons">

              {{-- Option On Button --}}
              <label class="btn btn-suaray btn-suaray-discreet create-event-option-on" ng-click="rsvp = 'true'">
                {!! Form::radio('meta[rsvp][enabled]', true, false, ['data-role' => 'rsvp-on', 'autocomplete' => 'off']) !!} On
              </label>

              {{-- Option Off Button --}}
              <label class="btn btn-suaray btn-suaray-discreet create-event-option-off" ng-click="rsvp = 'null'">
                {!! Form::radio('meta[rsvp][enabled]', false, false, ['data-role' => 'rsvp-off', 'autocomplete' => 'off']) !!} Off
              </label>

            </div>

          </div>

        </div>

        {{-- BEGIN Ticket Option --}}
        <div class="panel create-event-panel" data-role="show-info-container" >

          {{-- Panel Heading Container --}}
          <div class="panel-heading row">

            {{-- Panel Header --}}
            <p class="panel-title pull-left col-xs-12 col-sm-9">Tickets
            </p>
            <i class="fa fa-question-circle fa-lg suaray-popover" tabindex="0" role="button" data-toggle="suaray-popover" data-trigger="focus" title="Tickets" data-content="Choose this option if you are planning on selling tickets for your event."></i>

            {{-- Collapse button for Poll info --}}
            {{-- <a class="btn btn-suaray btn-suaray-discreet" data-role="show-info-button" ng-click="">More Info</a> --}}

            {{-- On/Off Button Group --}}
            <div class="btn-group pull-right" data-toggle="buttons">

              {{-- Option On Button --}}
              <label class="btn btn-suaray btn-suaray-discreet create-event-option-on" ng-click="tickets = 'true'">
                {!! Form::radio('meta[tickets][enabled]', true, false, ['data-role' => 'tickets-on', 'autocomplete' => 'off']) !!} On
              </label>

              {{-- Option Off Button --}}
              <label class="btn btn-suaray btn-suaray-discreet create-event-option-off" ng-click="tickets = 'null'">
                {!! Form::radio('meta[tickets][enabled]', false, false, ['data-role' => 'tickets-off', 'autocomplete' => 'off']) !!} Off
              </label>

            </div>
          </div>

          {{-- Collapsible Ticket Options Content --}}
          <div class="collapse" data-role="show-info" aria-expanded="false">

            {{-- Close Button for closing content --}}
            <button type="button" class="close" aria-label="Close" data-role="show-info-button"><span class="fa fa-times fa-lg"></span></button>

            {{-- Div Row for containing form elements --}}
            <div class="row panel-content">

              <div class="form-group col-sm-10 col-sm-offset-1 @if ($errors->has('question')) has-error @endif }}">
                This option allows you to create your own custom tickets to sell for your event.
                <br>
                <sub>Suaray receives 10% of the whole cost of the ticket price.</sub>
              </div>

            </div>

          </div>
        </div>

      </div>

      {{-- 'NEXT >' Button to continue along form --}}
      <div class="form-group text-right">

        {{-- Holder for the button --}}
        <div class="col-sm-10 col-sm-offset-1">
          <button class="btn btn-suaray btn-suaray-primary btn-lg pull-right" ng-click="tab = 'media'">Next <span class="glyphicon glyphicon-chevron-right"></span></button>
        </div>

      </div>

    </div>

  </div>
