<div class="friend-box" ng-hide="inviteFriend">

  <div class="row">

    <!-- Section hidden when submit button is clicked -->
    <div class="friends-container-margin">

      <form name="friendInvite">

        <div class="friends-invite-nav navbar" >
          <div class="container-fluid">

            <span class="navbar-brand">Invite</span>  <!-- Start invite options -->

              <div class="navbar-form navbar-left">
                <div class="form-group">
                  <input type="text" ng-model="query" class="friend-search-bar-bg-color form-control" placeholder="Filter Friends"/>
                </div>
              </div>


            <div class="navbar-form navbar-right">

              <div class="form-group friend-nav-item">

                <select required class="temp-select-margin form-control friends-select-box-dropdown" ng-model="eventId" ng-options="event.id as event.title for event in suarayEvents">
                  <option value=''>-- Select An Event --</option>
                </select>

              </div>

              <div class="form-group friend-nav-item">

                {{-- Invite all will exist here --}}
                {{--  <button
                  type="submit"
                  ng-click="doInviteAll({{ \Auth::user()->id }}, eventId)"
                  class="btn friend-event-invite-btn accept-small"
                  ng-model="requestAllBtn">
                  <span>@{{suarayDataEventInvite.selected?'Sent':''}} @{{!suarayDataEventInvite.selected?'Invite All':''}}</span>
                </button> --}}

              </div>

            </div>
          </div>
        </div>

        <!-- Friends hidden when submit button is clicked -->
        <ul class="list-unstyled friends-list-populate">

          <li ng-repeat="suarayDataFriend in suarayFriends | filter: query">
            <div class="col-md-4 col-sm-6 col-xs-12"> <!-- Start of user -->
              <div class="friends-user-list">

                {{-- Friend avatar --}}
                <div class="friends-image-thumb">
                  <img ng-src = "@{{ suarayDataFriend.avatar }}" border="0" />
                </div>

                <div class="friends-text-container">

                  <div class="friends-name-container">
                    <label>@{{suarayDataFriend.firstName}}&nbsp;@{{suarayDataFriend.lastName}}</label>
                  </div>

                  {{-- Button to invite friend to selected event --}}
                  <button
                    type="submit"
                    ng-click="doSendInvite({{ \Auth::user()->id }}, eventId, suarayDataFriend.id); suarayDataEventInvite.selected=!suarayDataEventInvite.selected"
                    class="btn friend-event-invite-btn accept-small"
                    ng-model="requestBtn"
                    ng-disabled="friendInvite.$invalid || suarayDataEventInvite.selected">
                    <span>@{{suarayDataEventInvite.selected?'Sent':''}} @{{!suarayDataEventInvite.selected?'Send':''}}</span>
                  </button>

                </div>

              </div>
            </div>  <!-- End of user -->
          </li>

        </ul>

        <div class="friends-invite-confirmation" ng-show="!(suarayFriends | filter:query).length">
          <h5 class="text-muted">NO FRIENDS FOUND </h5>
        </div>

      </form>

    </div>
  </div>  <!-- End of row -->
</div>

<!-- Section shown when invite is clicked -->
<div class="create-event-form-box" ng-show="inviteFriend">
  <div class="row">
    <div class="friends-container-margin"> <!--Shows confirmation of invite -->
      <button type="button" ng-click="toggleFirst()" class="btn btn-suaray btn-suaray-discreet btn-sm friends-back-btn btn-gallery-l"><span class="fa fa-chevron-left"></span>&nbsp;Back To Invite</button>
      <h3 class="friends-invite-confirmation">Your Invites Have Been Sent!</h3>
    </div>
  </div>
</div>
