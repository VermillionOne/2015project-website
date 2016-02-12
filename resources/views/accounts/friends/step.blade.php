<nav class="navbar friend-position navbar-default create-event-nav-background" data-role="view-nav-container">

  {{-- User feedback --}}
  @include('pages.feedback-tab')

  <div class="container">

  <div class="row">

    <div class="navbar-header">

      {{-- Collapse Button --}}
     <button type="button" class="navbar-toggle" ng-init="isCollapsed = true" ng-click="isCollapsed = !isCollapsed">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>

    </div>

    {{--  --}}
    <div class="navbar-collapse friend-position" ng-class="{collapse: isCollapsed}">

      {{-- Begin unordered list of Nav Tabs --}}
      <ul class="nav navbar-nav create-event-nav friend-link-cursor" role="navigation">

        {{-- Create notifications tab  --}}
        <li class="create-event-nav-tabs" role="presentation" ng-class="{active: tab === 'notifications'}" >
          <a class="create-event-nav-tabs-link" ng-click="tab = 'notifications'" data-role="tab-notifications">
            <h4 class="list-group-item-heading">Notifications</h4>
          </a>
        </li>

        {{-- Create requests tab  --}}
{{--         <li class="create-event-nav-tabs" role="presentation" ng-class="{active: tab === 'requests'}" >
          <a class="create-event-nav-tabs-link" ng-click="tab = 'requests'" data-role="tab-requests">
            <h4 class="list-group-item-heading">Requests</h4>
          </a>
        </li>
 --}}
        {{-- Create friends list tab  --}}
        <li class="create-event-nav-tabs" role="presentation" ng-class="{active: tab === 'friends'}" >
          <a class="create-event-nav-tabs-link" ng-click="tab = 'friends'" data-role="tab-friends">
            <h4 class="list-group-item-heading">Friends</h4>
          </a>
        </li>

        {{-- Create invite tab  --}}
        <li class="create-event-nav-tabs" role="presentation" ng-class="{active: tab === 'invite'}" >
          <a class="create-event-nav-tabs-link" ng-click="tab = 'invite'" data-role="tab-invite">
            <h4 class="list-group-item-heading">Invite</h4>
          </a>
        </li>

      </ul>
        <!-- Friends search bar  -->
      <form class="navbar-right" role="search">
        <div class="form-group">
        <ul class="list-unstyled">
          <li class="create-event-nav-tabs navbar-right" role="presentation">
            <div class="form-group has-feedback friend-search">
              <input type="search" ng-model="queryText" ng-keyup="doSearch(queryText)" id="friend-search" class="friend-search-bar-bg-color form-control" placeholder="Find Friends" autofocus />
              <span class="glyphicon glyphicon-search form-control-feedback"></span>
            </div>

            <!-- User search dropdown -->
            <div class="friends-overlay-wrapper">
              <div class="friends-overlay">
                <ul class="user-list list-unstyled" ng-show="queryText">
                  <div>

                    <li class="user cf invite-user-checkbox" ng-show="noMatchMessage">

                        <span class="friend-name-text" style="text-transform: capitalize">No Matches Found</span>

                    </li>

                    <li class="user cf" ng-repeat="suarayDataUser in suarayDataUsers" ng-show="searchMatches">

                      <!-- User image -->
                      <div class="friends-search-thumb">
                        <img ng-src="@{{ suarayDataUser.avatar }}" alt="">
                      </div>

                      <!-- Retrieves users name -->
                      <div class="user-info">
                      <a ng-href="/users/@{{suarayDataUser.username}}"><span class="friend-name-text " style="text-transform: capitalize">@{{suarayDataUser.firstName}}&nbsp;@{{suarayDataUser.lastName}}</span></a>
                      </div>
                    </li>
                  </div>
                </ul>
              </div>
            </div>
          </li>
        </ul>
        </div>
      </form>
    </div>
  </div>
</div>
</nav>
