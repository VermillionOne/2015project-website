@extends('layouts.master')

@section('javascript-includes')
  <script src="{{ ViewHelper::asset('assets/dist/lib-browse.js') }}"></script>
  <script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
@stop

@section('content')
<div ng-controller="HomeController" class="home-page-body">
  {{-- Featured Events --}}
  {{-- Featured Events --}}
  <div id="header-carousel" class="carousel slide home-carousel" data-ride="carousel">

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

            <img ng-if="viewportWidth === 'large'" data-role="slider-image" ng-src="{{ $event['featuredPhoto']['url']['1920x430'] }}" alt="{{ $event['title'] }}" >
            <img ng-if="viewportWidth === 'medium'" data-role="slider-image" ng-src="{{ $event['featuredPhoto']['url']['660x320'] }}" alt="{{ $event['title'] }}" >
            <img ng-if="viewportWidth === 'small'" data-role="slider-image" ng-src="{{ $event['featuredPhoto']['url']['460x275'] }}" alt="{{ $event['title'] }}" >
            <img ng-if="viewportWidth === 'xSmall'" data-role="slider-image" ng-src="{{ $event['featuredPhoto']['url']['320x192'] }}" alt="{{ $event['title'] }}" >
            {{-- Below are invisible tags used to show the right size image when appropriate /\ /\ --}}

            <div ng-show="" ng-click="viewportWidth = 'large'" data-role="viewport-large"></div>
            <div ng-show="" ng-click="viewportWidth = 'medium'" data-role="viewport-medium"></div>
            <div ng-show="" ng-click="viewportWidth = 'small'" data-role="viewport-small"></div>
            <div ng-show="" ng-click="viewportWidth = 'xSmall'" data-role="viewport-xSmall"></div>

            @if (isset($event['isSponsored']) && $event['isSponsored'] == 1)
              <p class="sponsored-badge">Sponsored</p>
            @endif

            <div class="container">

              <div class="carousel-caption">

                <h3>{{ $event['title'] }}</h3>
                <p><i class="fa fa-map-marker fa-fw event-slider-map-marker"></i>
                  {{$event['city']}}, {{ strtr($event['state'], $stateAbbreviations) }}
                </p>
                <p><i class="fa fa-calendar fa-fw search-listview-clock"></i>
                  {{ date('D, M. j', strtotime($event['times'][0]['start'])) }}
                </p>

                {{-- Display ticket high/low range --}}
                @if (! empty($event['ticketPricing']['range']))
                  <p class="home-featured-ticket-range">
                    ${{ $event['ticketPricing']['range']['low'] }}&mdash;${{ $event['ticketPricing']['range']['high'] }}
                  </p>
                @endif

                @if (! empty($event['ticketPricing']['range']))
                  <a href="{{ url(route('events.show', ['slug' => $event['slug']])) }}" class="btn btn-suaray btn-lg btn-suaray-primary" name="view-event" role="button">Get Tickets</a>
                @else
                  <a href="{{ url(route('events.show', ['slug' => $event['slug']])) }}" class="btn btn-suaray btn-lg btn-suaray-primary" name="view-event" role="button">View Event</a>
                @endif

              </div>
            </div>

          </div>

          <?php $index++; ?>

        @endif

      @endforeach

    </div>

    <a class="left carousel-control" href="#header-carousel" role="button" data-slide="prev">
      <span class="fa fa-chevron-left fa-2x" aria-hidden="true"></span>
      <span class="sr-only">Previous</span>
    </a>
    <a class="right carousel-control" href="#header-carousel" role="button" data-slide="next">
      <span class="fa fa-chevron-right fa-2x" aria-hidden="true"></span>
      <span class="sr-only">Next</span>
    </a>

  </div>

  {{-- Session Message --}}
  @include('includes.sessionStatus')

  {{-- Search Section --}}

  <div class="browse-search-bar">
    <div class="container">
      {!! ViewHelper::formOpen(['route' => 'events.search', 'method' => 'GET']) !!}
        <span class="fa fa-search"></span>
        <input type="text" name="name" placeholder="Start Looking" value="{{ \Input::get('q') }}">
        {!! Form::submit('Search', ['class' => '', 'type' => 'submit']) !!}
      {!! Form::close() !!}
      </form>
    </div>
  </div>


  {{-- Featured 'Category' tags --}}
  <section  class="home-category-section">
    <div class="container">
      @foreach ($eventsTagsIsFeatured as $tag => $events)

        {{-- Tag title --}}
        <div class="row">
          <div class="col-md-12">
            <p class="browse-title-sm" href="{{ route('events.search', ['q' => $tag]) }}">{{ $tag }}</p>
            <a class="btn-suaray btn-lg btn-suaray-category home-category-button" name="{{$tag}}" href="{{ route('events.search', ['q' => $tag]) }}">See More</a>
          </div>
        </div>

        <div class="row">
          {{-- Events slider --}}
          <div class="col-md-12">
            <ul data-role="bx-slider-{{ $tag }}">

              {{-- All events under this tag --}}
              <!-- {{ $tag }} -->
              @foreach ($events as $event)

                @if(!empty($event['times']))

                <li class="browse-category-slide-holder">
                  <a href="{{ url(route('events.show', ['slug' => $event['slug']])) }}">
                    @if (isset($event['isSponsored']) && $event['isSponsored'] == 1)
                    <p class="sponsored-badge">Sponsored</p>
                    @endif

                    @if (isset($event['featuredPhoto']))
                      <img src="{{ $event['featuredPhoto']['url']['318x190'] }}" alt="{{ $event['title'] }}" class="eventbrowsepic">
                    @else

                      <div class="home-page-default-image">

                      </div>

                    @endif
                    <div class="home-category-item-content">
                      {{-- Event title --}}
                      <h5>{{ $event['title'] }}</h5>

                      {{-- Location and next event --}}
                      <p><span class="fa fa-map-marker fa-fw"></span>{{$event['city']}}, {{ strtr($event['state'], $stateAbbreviations) }}</p>

                      <p><span class="fa fa-calendar fa-fw"></span>{{ date('D, M. j', strtotime($event['times'][0]['start'])) }}</p>
                    </div>
                  </a>
                </li>
                @endif
              @endforeach

            </ul>
          </div>
        </div>

      @endforeach


    </div>
  </section>
  {{-- Map --}}
  <div id="map-sect">
    <div class="container">

      <div class="row">
        <div class="col-md-12">
          <div class="browse-title-sm home-map-title">Search Events Near You</div>
            <button ng-click="setMap()" type="button" class="btn-suaray btn-lg btn-suaray-category home-category-button home-find-location"><span class="ion-android-locate"></span>Find Location</i></button>
        </div>
      </div>
    {{-- </div> --}}
      <div class = "row">

        <div class="col-md-12">

          <div id="map"></div>

          <div id='loader'>
            <span class='message'><i class="fa-li fa fa-spinner fa-pulse"></i></span>
          </div>

        </div>

      </div>

    </div>
  </div>
</div>
@stop
