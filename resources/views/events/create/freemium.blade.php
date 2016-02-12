<?php if (!isset($freemium)) $freemium = 'create_event_freemium'; ?>
<?php if (!isset($freemiumLocked)) $freemiumLocked = true; ?>

  <div class="create-event-form-box">

    <div class="row">
      {{-- BEGIN Event Free or Premium input Fields --}}
      <h3 class="col-sm-12 create-event-form-main-title">Choose Free or Premium</h3>


      <div class="col-sm-5 col-sm-offset-1">

        {{-- BEGIN Poll Question Option --}}
        <div class="panel create-event-panel">

          {{-- Panel Heading Container --}}
          <div class="panel-heading free-premium row flat">

            {{-- Panel Header --}}
            <p class="panel-title">Free: <span>$0</span></p>

          </div>

          <div class="panel-body free-premium">

            <div class="freemium-icon-holder fa-stack fa-lg create-event-free ">
              <i class="fa fa-star fa-stack-2x"></i>
            </div>

            <ul class="create-event-freemium-list">

              <li>Create Polls</li>
              <li>Show your event the map</li>
              <li>Have a comment wall</li>
              <li>Allow guests to post pictures</li>

            </ul>

            <div ng-click="freeEvent()">
              <button type="button" class="btn btn-suaray btn-suaray-primary btn-lg btn-block" ng-click="premiumOption(0)">Choose Free</button>
            </div>

          </div>

        </div>
      </div>

      <div class="col-sm-5">

        {{-- BEGIN Poll Question Option --}}
        <div class="panel create-event-panel">

          {{-- Panel Heading Container --}}
          <div class="panel-heading free-premium row flat">

            {{-- Panel Header --}}
            <p class="panel-title">Premium: <span>$20</span></p>

          </div>

          <div class="panel-body free-premium">

            <div class="freemium-icon-holder fa-stack fa-lg create-event-premium ">
              <i class="fa fa-certificate fa-stack-2x"></i>
              <i class="fa fa-star-o fa-stack-1x fa-inverse"></i>
            </div>

            <ul class="create-event-freemium-list">

              <li>Free Features +</li>
              <li>Verified Account</li>
              <li>Sell Custom Tickets</li>
              <li>Extra options only for Premium Accounts</li>

            </ul>

            <div ng-click="free = null" >
              {!! Form::hidden('isPremium', Input::old('premium'), ['data-role' => 'premium']) !!}
              <button type="button" class="btn btn-suaray btn-suaray-warning btn-lg btn-block" data-role="premiumButton" ng-click="premiumOption(1)">Choose Premium</button>
            </div>

          </div>

        </div>

      </div>

    </div>

  </div>
