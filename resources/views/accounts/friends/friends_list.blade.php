<div class="friend-box">

  <div class="row">

    <div class="friends-container-margin">

      <div class="friends-invite-nav navbar">
        <div class="container-fluid">

          <span class="navbar-brand">Friends</span>  <!-- Start invite options -->

          <div class="navbar-form navbar-left">

            <div class="form-group">
              <input type="text" ng-model="query" class="friend-search-bar-bg-color form-control" placeholder="Filter Friends"/>
            </div>

          </div>
        </div>
      </div>

        <ul class="list-unstyled friends-list-populate">
          {{-- Loop through friends list --}}
          <li ng-repeat="suarayDataFriend in suarayFriends | filter: query">

            <div class="col-md-4 col-sm-6 col-xs-12"> <!-- Start of user -->

              <div class="friends-user-list">

                  <div class="friends-image-thumb"> <img ng-src = "@{{suarayDataFriend.avatar}}" border="0" /> </div>

                    <div class="friends-text-container">

                      <div class="friends-name-container">
                        <a class="friend-request-link" ng-href="/users/@{{suarayDataFriend.username}}">@{{suarayDataFriend.firstName}}&nbsp;@{{suarayDataFriend.lastName}}</a>
                      </div>

                        <div class="friend-active-status">
                          ACTIVE
                        </div>

                          {{-- Deletes friend --}}
                          <div class="delete-btn">
                            <button type="button" class="btn btn-suaray btn-suaray-clear btn-xs accept-small">
                              <a ng-href="/account/friends/delete/@{{suarayDataFriend.id}}">Unfriend</a>
                            </button>
                          </div>
                    </div>
              </div>
            </div>  <!-- End of user -->
          </li>
        </ul>

          <!-- Shows when friend search returns no result -->
        <div class="friends-invite-confirmation" ng-show="!(suarayFriends | filter:query).length">
          <h5 class="text-muted">NO FRIENDS FOUND </h5>
        </div>

    </div> <!-- End of user row -->
  </div>
</div>

{{--        @foreach ($friends as $friend)

          <li>
            <div class="col-md-4 col-sm-3 col-xs-6"> <!-- Start of user -->
              <div class="friends-user-list">

                  <div class="friends-image-thumb"> <img src = "{{ ViewHelper::asset('assets/img/user.png') }}" border="0" /> </div>
                    <div class="friends-text-container">
                      <div class="friends-name-container"><a class="friend-request-link" href="">{{$friend['firstName'] }}&nbsp;{{$friend['lastName']}} </a> </div>

                        <div class="friend-active-status">
                          ACTIVE
                        </div>

                          <!-- Deletes friend -->
                         <div class="delete-btn">
                            <button type="button" class="btn btn-suaray btn-suaray-clear btn-xs accept-small">
                              <a href="{{ route('friends.delete', ['id' => $friend['id']]) }}">Delete</a>
                            </button>
                          </div>

                    </div>  <!-- End of user -->
          </li>
        @endforeach --}}
