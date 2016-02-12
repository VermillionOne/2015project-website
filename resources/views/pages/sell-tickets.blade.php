@extends('layouts.master')

@section('javascript-includes')
  <script src="{{ ViewHelper::asset('assets/dist/lib-home.js') }}"></script>
  <script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
@stop

@section('content')
<div ng-controller="SellTicketsController">

  {{-- Featured Events --}}
  <div id="header-carousel" class="carousel slide sell-tickets-carousel" data-ride="carousel">

    {{-- Carousel indicators --}}
    <ol class="carousel-indicators">

      {{-- For every featured event --}}
      <?php $index = 0; ?>
      @foreach ($eventsIsFeatured as $event)

        {{-- Only if we have photos --}}
        @if (isset($event['featuredPhoto']))

          {{-- If this is event 1 --}}
          @if ($index == 0)
            {{-- Set first event with "active" class --}}
            <li data-target="#header-carousel" data-slide-to="{{ $index }}" class="active"></li>
          @else
            <li data-target="#header-carousel" data-slide-to="{{ $index }}"></li>
          @endif

          <?php $index++; ?>

        @endif

      @endforeach

    </ol>

    {{-- Carousel wrapper for slides --}}
    <div class="carousel-inner" role="listbox">

      {{-- For every featured event --}}
      <?php $index = 0; ?>

      @foreach ($eventsIsFeatured as $event)

          {{-- Make sure this event has photos --}}
          @if (isset($event['featuredPhoto']) && !empty($event['times']))

            {{-- Set "active" class if this is event 1 --}}
            <div class="item @if ($index == 0){{ 'active' }}@endif">

              <div class="home-hero-overlay">

                @if (isset($event['isSponsored']) && $event['isSponsored'] == 1)
                  <p class="sponsored-badge">Sponsored</p>
                @endif

              </div>

              <div class="home-hero-info">
                <div class="container">

                  <img src="{{ ViewHelper::asset('assets/img/featured-badge.png') }}">

                  <h3>{{ $event['title'] }}</h3>

                  <p class="home-hero-info-event-description">
                    <i class="fa fa-map-marker event-slider-map-marker"></i>&nbsp;&nbsp;{{$event['city']}}, {{ strtr($event['state'], $stateAbbreviations) }}

                    <span class="event-slider-date "><i class="fa fa-calendar search-listview-clock"></i> {{ date('F d', strtotime($event['times'][0]['start'])) }} | {{ date('g:i a', strtotime($event['times'][0]['start'])) }}
                    </span>
                  </p>

                  <p class="home-hero-info-event-description">{{ $event['description'] }}</p>

                  <a href="{{ url(route('events.show', ['slug' => $event['slug']])) }}" class="btn btn-suaray btn-lg btn-suaray-primary" role="button">View Event</a>

                </div>
              </div>

              <img ng-if="viewportWidth === 'large'" data-role="slider-image" ng-src="{{ $event['featuredPhoto']['url']['1920x430'] }}" alt="{{ $event['title'] }}" >
              <img ng-if="viewportWidth === 'medium'" data-role="slider-image" ng-src="{{ $event['featuredPhoto']['url']['660x320'] }}" alt="{{ $event['title'] }}" >
              <img ng-if="viewportWidth === 'small'" data-role="slider-image" ng-src="{{ $event['featuredPhoto']['url']['460x275'] }}" alt="{{ $event['title'] }}" >
              <img ng-if="viewportWidth === 'xSmall'" data-role="slider-image" ng-src="{{ $event['featuredPhoto']['url']['320x192'] }}" alt="{{ $event['title'] }}" >
              {{-- Below are invisible tags used to show the right size image when appropriate /\ /\ --}}
              <div ng-show="" ng-click="viewportWidth = 'large'" data-role="viewport-large"></div>
              <div ng-show="" ng-click="viewportWidth = 'medium'" data-role="viewport-medium"></div>
              <div ng-show="" ng-click="viewportWidth = 'small'" data-role="viewport-small"></div>
              <div ng-show="" ng-click="viewportWidth = 'xSmall'" data-role="viewport-xSmall"></div>

            </div>

            <?php $index++; ?>

          @endif

      @endforeach

    </div>

  </div>

  <div class="home-full-section">

    {{-- Session Message --}}
    @include('includes.sessionStatus')

    <div class="container">
      <div class="row">
        <div class="col-sm-6">
          <div class="home-half-section-holder">
            <h2>Discover Events</h2>
            <p>find unique events near you</p>
            <a href="{{ url(route('home')) }}" class="btn-suaray btn-lg btn-suaray-primary">Browse</a>
          </div>
        </div>

        <div class="col-sm-6">
          <div class="home-half-section-holder">
            <h2>Sell Tickets</h2>
            <p>Increase Sales, Manage Your Event and Tickets Smoothly</p>

            {{-- if the user is logged in do something --}}
            @if (Auth::check())
              <a href="{{ url(route('events.create')) . '?tab=freemium'}}"  class="btn-suaray btn-lg btn-suaray-positive">Create An Event</a>
            @else
              <a href="{{ url(route('register')) }}" class="btn-suaray btn-lg btn-suaray-positive">Create An Event</a>
            @endif

          </div>
        </div>
      </div>
    </div>
  </div>

  {{-- Session Message --}}
  @include('includes.sessionStatus')

</div>
@stop
