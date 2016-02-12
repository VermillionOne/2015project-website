<nav class="navbar navbar-default create-event-nav-background" data-role="view-nav-container">
  <div class="container">
    <div class="navbar-header">

      {{-- Collapse Button --}}
      <button type="button" class="sub-nav navbar-toggle collapsed" data-role="view-nav" data-target="#navbar" aria-expanded="true" aria-controls="navbar">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>

    </div>

    {{--  --}}
    <div class="collapse navbar-collapse " data-role="show-nav" aria-expanded="false">

      {{-- Begin unordered list of Nav Tabs --}}
      <ul class="nav navbar-nav create-event-nav friend-link-cursor" role="navigation">

        {{-- Create Freemium tab  --}}
        {{-- <li class="create-event-nav-tabs" role="presentation" ng-class="{active: tab === 'freemium'}" >
          <a class="create-event-nav-tabs-link" ng-click="tab = 'freemium'" data-role="tab-freemium">
            <h4 class="list-group-item-heading">Free / Premium</h4>
          </a>
        </li> --}}

        {{-- Create Payment tab  --}}
        <li class="create-event-nav-tabs" role="presentation" ng-show="free === null" ng-class="{active: tab === 'payment'}" >
          <a class="create-event-nav-tabs-link" ng-click="tab = 'payment'" data-role="tab-payment">
            <h4 class="list-group-item-heading">Payment</h4>
          </a>
        </li>

        {{-- Create Details tab  --}}
        <li class="create-event-nav-tabs" role="presentation" ng-class="{active: tab === 'details'}" >
          <a class="create-event-nav-tabs-link" ng-click="tab = 'details'" data-role="tab-details">
            <h4 class="list-group-item-heading">Details</h4>
          </a>
        </li>

        {{-- Create Location tab  --}}
        <li class="create-event-nav-tabs" role="presentation" ng-class="{active: tab === 'location'}" >
          <a class="create-event-nav-tabs-link" ng-click="tab = 'location'" data-role="tab-location">
            <h4 class="list-group-item-heading">Location</h4>
          </a>
        </li>

        {{-- Create Options tab  --}}
        <li class="create-event-nav-tabs" role="presentation" ng-class="{active: tab === 'options'}" >
          <a class="create-event-nav-tabs-link" ng-click="tab = 'options'" data-role="tab-options">
            <h4 class="list-group-item-heading">Options</h4>
          </a>
        </li>

        {{-- Create Media Upload tab  --}}
        <li class="create-event-nav-tabs" role="presentation" ng-class="{active: tab === 'media'}" >
          <a class="create-event-nav-tabs-link" ng-click="tab = 'media'" data-role="tab-media">
            <h4 class="list-group-item-heading">Media</h4>
          </a>
        </li>

        {{-- Create Summary tab  --}}
        <li class="create-event-nav-tabs" role="presentation" ng-class="{active: tab === 'summary'}" >
          <a class="create-event-nav-tabs-link timezone-val" ng-click="openSummary()" data-role="tab-summary">
            <h4 class="list-group-item-heading">Summary</h4>
          </a>
        </li>

      </ul>
    </div>
  </div>
</nav>
