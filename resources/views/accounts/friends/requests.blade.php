<?php if (!isset($requests)) $requests = 'friends_requests'; ?>
<?php if (!isset($requestsLocked)) $requestsLocked = true; ?>

  <div class="friend-box">

    <div class="row">

      <div class="friends-container-margin">

        <div class="friends-invite-nav navbar">

          <div class="container-fluid">

            <span class="navbar-brand">Requests</span>  <!-- Start invite options -->

          </div>

        </div>

        <div class="row">

          <ul class="list-unstyled friends-list-populate"> <!-- Populates requests -->

            <li  ng-repeat="suarayFriendRequest in suarayRequests">

              <div class="col-md-4 col-sm-3 col-xs-6" > <!-- Start of user -->

                  <div class="friend-request-image"> <img ng-src="@{{suarayFriendRequest.avatar}}" border="0" /> </div>

                  <div class="friends-text-container">

                    <div class="friends-name-container">
                      <a class="friend-request-link" ng-href="/users/@{{suarayFriendRequest.username}}">@{{suarayFriendRequest.firstName}}&nbsp;@{{suarayFriendRequest.lastName}}</a>
                    </div>

                      <div class="friend-accept-deny-btn">

                        <a ng-href="/account/friends/update/@{{suarayFriendRequest.id}}" class=" btn accept-btn  btn-suaray btn-xs">Accept</a>

                        <a class=" btn deny-btn btn-suaray btn-xs" ng-href="/account/friends/delete/request/@{{suarayFriendRequest.id}}">Deny</a>

                    </div>

                </div>
              </div>  <!-- End of user -->
            </li>
          </ul>

          <div class="friends-invite-confirmation" ng-hide="suarayRequests.length">
            <h5 class="text-muted">You Have No Requests</h5>
          </div>

        </div>
      </div>
    </div> <!-- End of user row -->
  </div>  <!-- End of request page -->

