<div class="friend-box">

  <div class="row">

    <div class="friends-container-margin">

      <div class="friends-invite-nav navbar">

        <div class="container-fluid">

          <span class="navbar-brand">Notifications</span>  <!-- Start invite options -->

        </div>

      </div>

        <ul class="list-unstyled"> <!-- Populates requests -->

          {{-- Repeat for friend requests if any --}}
          <li ng-repeat="suarayFriendRequest in suarayRequests">
            <div class="row notification-row">

              {{-- Requester avatar --}}
              <div class="col-md-2 col-sm-2 col-xs-2 friends-list-populate"> <!-- Start of user -->

                  <div class="notification-event-request">

                    <div class="friend-request-image">
                      <img ng-src="@{{suarayFriendRequest.avatar}}" border="0" />
                    </div>

                  </div>

              </div>

              <div class="col-md-8 col-sm-8 col-xs-8 friends-list-populate">

                <a class="friend-request-link" ng-href="/users/@{{suarayFriendRequest.username}}">@{{suarayFriendRequest.firstName}}&nbsp;@{{suarayFriendRequest.lastName}}</a>

                <span class="notification-sub-text">has sent you a friend request.</span>

              </div>

              <div class="col-md-2 col-sm-2 col-xs-2 friends-list-populate friend-accept-deny-btn">

                <a ng-href="/account/friends/update/@{{suarayFriendRequest.id}}" class="btn accept-btn  btn-suaray btn-xs">Accept</a>

                <a class="btn deny-btn btn-suaray btn-xs" ng-href="/account/friends/delete/request/@{{suarayFriendRequest.id}}">Deny</a>

              </div>

            </div>
          </li>

          {{-- Repeat for event invitations if any  --}}
          <li ng-repeat="suarayEventInvites in suarayInvites track by suarayEventInvites.event.id">
            <div class="row notification-row">
              <div class="col-md-2 col-sm-2 col-xs-2 friends-list-populate"> <!-- Start of user -->

                <div class="notification-event-request">

                  {{-- If user clicks calendar will take to event --}}
                  <a ng-href="/events/@{{suarayEventInvites.event.slug}}" ng-click="updateRequest(suarayEventInvite.event.id, suarayEventInvites.id)">
                    <span class="fa fa-calendar fa-fw notification-calendar"></span>
                  </a>

                </div>

              </div>

              <div class="col-md-8 col-sm-8 col-xs-8 friends-list-populate">

                {{-- Link to event from request --}}
                <a class="friend-request-link" ng-href="/events/@{{suarayEventInvites.event.slug}}" ng-click="updateRequest(suarayEventInvites.event.id, suarayEventInvites.id, $index)">@{{suarayEventInvites.event.title}}</a>

                {{-- If city is not null, will display --}}
                <span class="notification-sub-text" ng-if="suarayEventInvites.event.city"><span class="fa fa-map-marker fa-fw"></span>@{{suarayEventInvites.event.city}}</span>

                {{-- Requester name --}}
                <span class="notification-sub-text">
                @{{suarayEventInvites.requester.firstName}} @{{suarayEventInvites.requester.lastName}} has invited you to this event!</span>

              </div>

              <div class="col-md-2 col-sm-2 col-xs-2 friends-list-populate friend-close-btn">

                {{-- Close button --}}
                <button
                  type="submit"
                  ng-click="updateRequest(suarayEventInvites.event.id, suarayEventInvites.id, $index)"
                  class="close notifications-close"
                  aria-label="Close">
                  <span aria-hidden="true" style="color: #000;">&times;</span>
                </button>

              </div>

            </div>
          </li>

        </ul>

        {{-- If no requests, default will show --}}
        <div class="friends-invite-confirmation" ng-if="!suarayRequests.length && !suarayInvites.length">
          <h5 class="text-muted">No Recent Activity</h5>
        </div>


    </div>
  </div>
</div>
