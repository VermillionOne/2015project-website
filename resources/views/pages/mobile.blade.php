@extends('layouts.master')

@section('javascript-includes')
  <script src="{{ ViewHelper::asset('assets/dist/lib-mobile.js') }}"></script>
  <script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
@stop

@section('content')

<div class="mobile-app-landing-page" ng-controller="MobileAppLandingPageController">

  {{-- Hero Section --}}
  <div class="landing-page-hero-section">
    <div class="container">
      <img class="mobile-app-landing-page-hero-image" src="{{ ViewHelper::asset('assets/img/landing-pages/mobile-app/iphone-in-hand.png') }}" alt="Discover events in Suaray's mobile application">
      <div class="hero-content-holder">
        <h1>Discover <span class="highlight">Events</span> of all types <span class="highlight">around the world!</span></h1>

        <p>SUARAY = Event Concierge Service + Social Organizer + Business Promotion Tool</p>

        <p class="hero-button-group">
          <a target="_blank" href="https://itunes.apple.com/us/app/discover-share-promote-events/id1000112846?mt=8"><img class="app-store-icon" src="{{ ViewHelper::asset('assets/img/landing-pages/mobile-app/app-store-icon.png') }}"></a>
          <a target="_blank" href="https://play.google.com/store/apps/details?id=com.shindiig.suaray.production&hl=en"><img class="app-store-icon" src="{{ ViewHelper::asset('assets/img/landing-pages/mobile-app/google-play-icon.png') }}"></a>
        </p>
      </div>
    </div>
  </div>

  {{-- Browse Events Section --}}
  <div class="landing-page-foreground-section">
    <div class="container">

      <img class="search-preview" src="{{ ViewHelper::asset('assets/img/landing-pages/mobile-app/browse-monitor.png') }}">

      <div class="foreground-content-holder">
        <h2>Browse Events</h2>

        <p>Browse beautiful customized event pages and keep track of what you wish to attend in your social calendar.</p>

        <ul class="landing-page-feature-list">
          <li>Easy and sleek user interface</li>
          <li>Search by location, category, date</li>
          <li>Beautiful customized event detail pages with Photos, Videos, Description, QR code deals, Comments, Reviews, Customized Tickets, Transportation Links</li>
          <li>Crowd Surf: Awesome Tinder like browsing feature to swipe through interesting looking events </li>
        </ul>

        <a href="{{ url(route('home')) }}" class="btn-suaray btn-lg btn-suaray-primary" type="button">Search Events</a>
      </div>

    </div>
  </div>

  {{-- Find Local Events Section --}}
  <div class="landing-page-background-section">
    <div class="container">

      <div class="background-content-holder">
        <h2>Find Local Events</h2>
        <p>Based on GPS Location</p>
        <a target="_blank" href="https://itunes.apple.com/us/app/discover-share-promote-events/id1000112846?mt=8"><img class="app-store-icon" src="{{ ViewHelper::asset('assets/img/landing-pages/mobile-app/app-store-icon.png') }}"></a>
        <a target="_blank" href="https://play.google.com/store/apps/details?id=com.shindiig.suaray.production&hl=en"><img class="app-store-icon" src="{{ ViewHelper::asset('assets/img/landing-pages/mobile-app/google-play-icon.png') }}"></a>
      </div>

      <img class="map-preview" src="{{ ViewHelper::asset('assets/img/landing-pages/mobile-app/dimensional-map-preview.png') }}">

    </div>
  </div>

  {{-- Social Calendar Section --}}
  <div class="landing-page-foreground-section">
    <div class="container">
      <img class="calendar-preview" src="{{ ViewHelper::asset('assets/img/landing-pages/mobile-app/calendar-preview.png') }}">

      <div class="foreground-content-holder">
        <h2>Social Calendar</h2>

        <ul>
          <li>
            <img src="{{ ViewHelper::asset('assets/img/landing-pages/mobile-app/friends-square.png') }}">
            <h3>Share With Friends</h3>
            <p>Easily see what day your friends are free to invite them to join you.</p>
          </li>
          <li>
            <img src="{{ ViewHelper::asset('assets/img/landing-pages/mobile-app/updates-square.png') }}">
            <h3>Updates</h3>
            <p>Events you choose to attend update into your Profile Calendar.</p>
          </li>
          <li>
            <img src="{{ ViewHelper::asset('assets/img/landing-pages/mobile-app/time-management-square.png') }}">
            <h3>Time Management</h3>
            <p>Keep track of everything you plan to do.</p>
          </li>
        </ul>

        <a href="{{ url(route('home')) }}" class="btn-suaray btn-lg btn-suaray-discreet" type="button">Search Events</a>
      </div>
    </div>
  </div>


  {{-- Create Event / Sell Tickets Section --}}
  <div class="landing-page-bright-section">
     <div class="container">
        <div class="bright-content-holder">
          <h2>Create Events / <span class="highlight">Sell Tickets</span></h2>

          <p><span class="highlight">Easily</span> create customizable event pages.</p>
          <p>Premium features allow for <span class="highlight">powerful interaction</span> with the audience.</p>
          <p>Polls, Comments, Reviews, Photos, Videos, Text Description are all available.</p>
          <p><span class="highlight">Customizable Tickets</span> to create anything from General Admission and VIP tickets to Volunteer Positions for Charity and Non Profit Events.</p>

          {{-- if the user is logged in do something --}}
          @if (Auth::check())
            <a href="{{ url(route('events.create')) . '?tab=freemium'}}"  class="btn-suaray btn-lg btn-suaray-primary">Sell Tickets</a>
          @else
            <a href="{{ url(route('register')) }}" class="btn-suaray btn-lg btn-suaray-primary">Sell Tickets</a>
          @endif

        </div>

      <img class="create-event-preview" src="{{ ViewHelper::asset('assets/img/landing-pages/mobile-app/create-event-preview.png') }}">
    </div>
  </div>

  {{-- video sectio --}}
  <div class="landing-page-video-section">
    <iframe width="1280" height="720" src="https://www.youtube.com/embed/jJUCZAf24ag?rel=0&amp;showinfo=0" frameborder="0" allowfullscreen></iframe>
  </div>

  {{-- New Footer - Will likely be moved to new file --}}
  <div class="landing-page-footer">
    <div class="container">
      <h2>Get Started Quickly</h2>

      <p>Zero setup, monthly or hidden fees and low cost payment processing</p>

      {{-- if the user is logged in do something --}}
      @if (Auth::check())
        <a href="{{ url(route('events.create')) . '?tab=freemium'}}"  class="btn-suaray btn-lg btn-suaray-positive footer-get-started-button">Create An Event</a>
      @else
        <a href="{{ url(route('register')) }}" class="btn-suaray btn-lg btn-suaray-positive footer-get-started-button">Create An Event</a>
      @endif

      <p class="site-description">SUARAY.com is the connected website that allows anyone and especially businesses to access a professional desktop version of SUARAY to easily add official logos, photos, videos and brochures. Anyone can be an event organizer. Easily manage your events or discover and buy tickets millions of events worldwide. Suaray helps event organizers leverage the power of social networking to sell more tickets by helping them market events to their target audience.</p>

      <div class="row">

        <img class="footer-logo" src="{{ ViewHelper::asset('assets/img/landing-pages/mobile-app/grey-logo.png') }}">

        <div class="link-holder">

          {{-- if the user is logged in do something --}}
          @if (Auth::check())
            <p class="footer-link-1"><a {{ url(route('events.create')) . '?tab=freemium'}}>Discover</a></p>
          @else
            <p class="footer-link-1"><a {{ url(route('register')) }}>Discover</a></p>
          @endif

          <p class="footer-link-2">Support</p>
          <p class="footer-link-3">Privacy</p>
        </div>

        <div class="social-links">
          <a class="facebook-link" target="_blank" href="https://www.facebook.com/suarayapp?fref=nf"><img src="{{ ViewHelper::asset('assets/img/landing-pages/mobile-app/facebook-grey.png') }}"></a>
          <a class="twitter-link" target="_blank" href="https://twitter.com/suarayapp"><img src="{{ ViewHelper::asset('assets/img/landing-pages/mobile-app/twitter-grey.png') }}"></a>
        </div>

      </div>

    </div>
  </div>
</div>

@stop
