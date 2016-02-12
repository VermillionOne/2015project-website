{{-- Invite friends to currently shown event --}}
<div class = "event-detail-col-r-widget">

  <div class = "events-detail-title-r-col">Invite</div>

    <div class = "events-detail-info">

      {{-- Dropdown button that displays invite by email and invite by suaray friends --}}
      <a class="btn btn-block btn-social event-invite-dropdown collapsed" role="button" data-toggle="collapse" href="#collapseInvite" aria-expanded="false" aria-controls="collapseInvite">
      <span class="fa fa-paper-plane"></span>Send Invite<span class="caret" ng-class="{invertCaret: social-dropdown === true}"></span>
      </a>

      {{-- Start of dropdown with buttons in well --}}
      <div class="collapse invite-container well well-sm" id="collapseInvite">

        {{-- Email share button toggles modal --}}
        <a class="btn btn-block btn-social btn-invite-email"
           ng-click="toggleModal()"
           title="Share with Email">
           <span class="fa fa-envelope"></span> Via Email
        </a>

        {{-- Friend share button toggles modal with friends list --}}
        <a class="btn btn-block btn-social btn-invite-friends"
           ng-click="toggleModal2()"
           title="Share with Suaray Friends">
          <span class="fa fa-user-plus"></span>Via Suaray
        </a>

    </div>

  </div>

</div>

{{-- Start modal for email --}}
<modal id="email-invite" title="Invite Your Friends" visible="showModal">

  @if(Auth::check())

    {!! ViewHelper::formOpen(['url' => route('events.email', ['slug' => $events['slug'], Config::get('api.actionIdentifiers.eventInviteRequesterId') => \Auth::user()->id]), 'method' => 'POST', 'name' => 'emailShare', 'role' => 'form']) !!}

  @else

    {!! ViewHelper::formOpen(['url' => route('events.email', ['slug' => $events['slug']]), 'method' => 'POST', 'name' => 'emailShare', 'role' => 'form']) !!}

  @endif

  {{-- Email recipient --}}
  <div class="form-group" ng-class="{ 'has-error' : emailShare.email.$invalid && !emailShare.email.$pristine }">
    {!! Form::label('email', 'Email') !!}<span class="email-message-text">* Please enter one email address per invitation sent. </span>
    <input type="email" class="form-control" required name="email" ng-model="user.email" placeholder="Enter Email Address" required ng-pattern="userValidation.email">
  </div>

  {{-- Error messages displayed if email not entered or an invalid email is entered --}}
  <p ng-show="submitted && emailShare.email.$error.required" class="edit-tickets-warn-text">Required Field</p>
  <p ng-show="emailShare.email.$error.email" class="edit-tickets-warn-text">Enter a valid email.</p>

  {!! Form::hidden('eventId', $events['id']) !!}

  <div class="form-group">

    <!-- Read only event link -->
    <div class="form-group">
      {!! Form::label('eventLink', 'Event Link') !!}

      @if(Auth::check())

        <input type="text" class="form-control" id="url" readonly value="{{ url(route('events.show', ['slug' => $events['slug'], Config::get('api.actionIdentifiers.eventInviteRequesterId') => \Auth::user()->id])) }}" />

      @else

        <input type="text" class="form-control" value="{{ route('events.show', $events['slug']) }}" id="url" readonly value="{{ url(route('events.show', $events['slug'])) }}" />

      @endif

    </div>

    {{-- Optional personalized message for user to send with email --}}
    <div class="form-group">
      {!! Form::label('message', 'Message') !!}
      <textarea class="form-control" rows="3" placeholder="Say something about this event! (optional)"></textarea>
    </div>

    <div class="email-container">

      <div class="row">

        {{-- Captcha to confirm user is not a robot --}}
        <div class="col-md-6 col-sm-6 col-xs-12">
          <div class="g-recaptcha" data-sitekey="{{ Config::get('api.google.captcha.public') }}"></div>
        </div>

        {{-- Email submit --}}
        <div class="col-md-6 col-sm-6 col-xs-12">
          <button type="submit" ng-disabled="emailShare.$invalid" value="submit" class="btn btn-send-email btn-block email-submit" onclick="checkCaptcha()">Send</button>
        </div>

      </div>

    </div>

  {!! Form::close() !!}


    {{-- Toggle event preview picture, title, description --}}
    <a class="event-preview" ng-show="eventLink" ng-click="toggleThis(); toggleLink()">+ Show Event Preview</a>
    <a class="event-preview" ng-hide="eventLink" ng-click="toggleThis(); toggleLink()">- Hide Event Preview</a>

    {{-- Shows event preview  with title and description--}}
    <div class="form-group" ng-hide="eventPreview">

      <div class="thumbnail">

        {{-- Event main photo --}}
        <div class="email-event">
          @if (isset($events['photos']['0']))
            <img src="{{$events['photos']['0']['url']['318x190']}}" alt="...">
          @endif
        </div>

        {{-- Title and description section --}}
        <div class="caption">
          <h3>{{ $events['title'] }}</h3>
          <p>{{ $events['description'] }}</p>
        </div>

      </div>

    </div>
  </div>

</modal>

{{-- Start modal for friends invite --}}
  <modal id="friend-invite" class="modal-text" title="{{ $events['title'] }}" visible="showModal2">

    @if(Auth::check())

      @if (empty($friends))
        No friends to invite
      @endif

      <ul class="list-unstyled modal-invite-height">

        {{-- Loops through friends list --}}
        <li class="invite-border" ng-repeat="suarayInvite in suarayEventInvite">

          <form name="invite-friends">

            <div class="friends-user-list">

              {{-- Friend avatar --}}
              <div class="invite-image-thumb">
                <img class="image-invite" ng-src ="@{{suarayInvite.avatar}}" border="0">
              </div>

              {{-- Friend name and online status --}}
              <div class="friends-text-container">

                <div class="friends-name-container">
                  <a class="friend-request-link" href="">
                    @{{suarayInvite.firstName}}&nbsp;@{{suarayInvite.lastName}}
                  </a>
                </div>

                <div class="friend-active-status">
                  ACTIVE
                </div>

                {{-- Invites friend to currently viewed event --}}
                <div class="delete-btn">

                  <button ng-click="doSendInvite({{ \Auth::user()->id }}, {{$eventId}}, suarayInvite.id); suarayInvite.selected=!suarayInvite.selected"
                     name="friend-invite"
                     class="btn btn-send-invite accept-small"
                     for="@{{ suarayInvite.id }}"
                     ng-model="requestBtn"
                     ng-disabled="suarayInvite.selected">

                    <span>@{{suarayInvite.selected?'Sent':''}} @{{!suarayInvite.selected?'Send':''}}</span>

                  </button>

                </div>

              </div>

            </div>

          </form>

        </li>

        <div class="friends-invite-confirmation" ng-show="!suarayEventInvite.length">
          <h5 class="text-muted">No Friends Found </h5>
        </div>

      </ul>

    @else

      <h3>Please sign in to view friends to invite</h3>

    @endif

  </modal>
<script src="https://www.google.com/recaptcha/api.js?onload=onloadCallback&getResponse"></script>

