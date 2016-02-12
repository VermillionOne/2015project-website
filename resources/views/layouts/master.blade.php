<!doctype html>
<html lang="en" ng-app="suaray">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
    {{-- If description for event available will display in meta, if not, display default for site --}}
    <meta name="description" content="{{ isset($metaDescription) ? $metaDescription : 'Discover and create events including Concerts, Parties, Festivals, Flash Mobs and more! See when your friends are free and invite them to join you on Suaray!' }}">

    {{-- If keywords exist, will display, if not, will display default --}}
    <meta name="keywords" content="{{ isset($metaKeywords) ? $metaKeywords : 'Social Calendar, Browse Local Events, Create Events, Customizable Tickets' }}">

    {{-- If event title exists, will display as title, otherwise, default suaray will display--}}
    <meta name="title" content="{{ isset($metaTitle) ? $metaTitle : 'SUARAY - Discover Fun Events'}}">

    {{-- If event title exists, will display as title in tab, otherwise, default suaray will display--}}
    <title>{{ isset($metaTitle) ? $metaTitle : 'SUARAY - Discover Fun Events'}}</title>

  {{-- CSS files --}}
  <link href="{{ ViewHelper::asset('assets/dist/suaray.css') }}" rel="stylesheet">
  <base href="/">
</head>

{{-- Start the body content --}}
<body role="document">

  @if (Config::get('global.pageLoaderDisplay'))
    <div class="universal-page-loader" data-role="page-loader" ng-controller="PageLoaderController">
      <p class="loading-text"><span class='fa fa-2x fa-circle-o-notch fa-spin'></span></p>
      <p class="loading-bar"></p>
    </div>
  @endif

  @if (isset($user))
    @if ((! empty($user['ticketInventories']) && empty($user['stripeManagedAccounts'])) || (empty($user['stripeManagedAccounts'])))
      <div class="alert alert-warning no-margin-bottom-alert" align="center">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
        @if (\Auth::check())
          {{\Auth::user()->first_name}}, in order to begin selling tickets you must add payout information <a href="{{route('payment')}}">here</a>
        @endif
      </div>
    @endif
  @endif

  {{-- here is my header --}}
  <div id="header">
    <div class = "container">
      <div class="row">

        {{-- lets wrap  my nav --}}
        <nav class="navbar navbar-default">

          {{-- I am holding all the nav stuff --}}
          <div class="navbar-header">

            {{-- yup im a logo --}}
            <div class="logo"><a href="{{ url(route('home')) }}" title="Suaray" id="Suaray"><img src="{{ ViewHelper::asset('assets/img/main-logo.jpg') }}" alt="Shindiig" border="0" /></a></div>

            {{-- Disallows the nav button to show in login and register --}}
            @if ((empty($__env->getSections()['loginTitle'])))

              {{-- nav buttons --}}
              <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
              </button>
            @endif
          </div>

          <div id="navbar" class="navbar-collapse collapse added-nav-padding">

            {{-- Disallows the nav button to show in login and register --}}
            @if ((empty($__env->getSections()['loginTitle'])))

              {{-- Show logged in buttons to home and create an event --}}
              <ul class="nav master-nav nav-pills navbar-left">

                  {{-- home link --}}
                <li class="" data-role="home">
                  <a href="{{ url(route('home')) }}">
                    <span class="glyphicon glyphicon-home"></span> Home
                  </a>
                </li>

                  {{-- My events link --}}
                <li class="" data-role="myEvents">
                  <a href="{{ url(route('dashboard')) }}" >
                    <span class="glyphicon glyphicon-calendar"></span> My Events
                  </a>
                </li>

                {{-- My events link --}}
                <li class="" data-role="categories">
                  <a href="{{ url(route('events.categories')) }}" >
                    <span class="ion-android-apps"></span> Categories
                  </a>
                </li>

                  {{-- Create event link --}}
                <li class="" data-role="createEvent">
                  <a href="{{ url(route('events.create')) . '?tab=details'}}" >
                    <small class="glyphicon glyphicon-plus"></small> Create Event
                  </a>
                </li>

              </ul>

          {{-- if the user is logged in do something --}}
            @if (Auth::check())

              {{-- Show logged in buttons to profile and signout --}}
              <ul class="nav nav-pills navbar-right profile-links" ng-controller="UsersNavigationController">
                <li class="dropdown-menu-holder" ng-init="dropdown = false">

                  {{-- Dropdown start --}}
                  <a href="#" class="dropdown-toggle user-dropdown" ng-click="dropdown = true" data-toggle="dropdown">
                    @if (empty(Auth::user()->avatar))
                      <span class="glyphicon glyphicon-user"></span>
                      <span class="caret"></span>
                    @else
                      <img ng-class="{clicked: dropdown === true}" src="{{ Auth::user()->avatar }}">
                    @endif
                  </a>

                  {{-- Dropdown menu start --}}
                  <ul class="dropdown-menu account-links" role="menu">

                    <li class="dropdown-header">
                       {{ Auth::user()->email }}
                    </li>

                    <li class="text-left">

                      {{-- Profile link --}}
                      <a href="{{ url(route('profile')) }}" >
                        <span class="glyphicon glyphicon-user"></span> Profile
                      </a>

                    </li>

                    <li class="text-left">

                      {{-- Friends link --}}
                      <a href="{{ url(route('friends')) }}">
                        <i class="fa fa-users"></i> Friends
                      </a>

                    </li>

                    <li class="text-left ">

                      {{-- Settings link --}}
                      <a href="{{ url(route('settings')) }}">
                        <span class="glyphicon glyphicon-cog"></span> Settings
                      </a>

                    </li>

                    <li class="text-left ">

                      {{-- Dashboard link --}}
                      <a href="{{ url(route('dashboard')) }}">
                        <span class="glyphicon glyphicon-list-alt"></span> My Events
                      </a>

                    </li>

                    <li class="text-left ">

                      {{-- Profile link --}}
                      <a href="{{ url(route('events.check-in')) }}" >
                        <i class="fa fa-check"></i> Check In
                      </a>

                    </li>

                    <li class="text-left ">

                      {{-- Dashboard link --}}
                      <a href="{{ route('my-tickets') }}">
                        <span class="fa fa-ticket"></span> My Tickets
                      </a>

                    </li>

                    <li class="text-left ">

                      {{-- Payment link --}}
                      <a href="{{ url(route('payment')) }}">
                        <span class="glyphicon glyphicon-usd"></span> Payment
                      </a>

                    </li>

                    <li role="separator" class="divider"></li>

                    <li class="text-left ">

                      {{-- Sign out link --}}
                      <a href="{{ url(route('logout')) }}">
                        <span class="glyphicon glyphicon-off"></span> Sign Out
                      </a>

                    </li>

                  </ul>

                </li>
              </ul>

            @else

              {{-- Show logged in buttons to profile and signout --}}
              <ul class="nav nav-pills navbar-right profile-links" ng-controller="UsersNavigationController">
                <li class="dropdown-menu-holder" ng-init="dropdown = false">

                  {{-- Dropdown start --}}
                  <a href="#" class="dropdown-toggle user-dropdown social-login-icon" ng-click="dropdown = true" data-toggle="dropdown">
                    Login <i class="fa fa-sign-in fa-lg"></i>
                  </a>

                  {{-- Dropdown menu start --}}
                  <ul class="dropdown-menu account-links" role="menu">

                    <li class="text-left ">

                      <div class="social-login-badge social-margin-right">
                        <a href="{{ url('account/login/instagram') }}" class="btn social-btns btn-social btn-instagram">
                          <i class="fa fa-instagram"></i>
                        </a>
                      </div>

                      <div class="social-login-badge">
                        <a href="{{ url('account/login/google') }}" class="btn social-btns btn-social btn-google-plus">
                          <i class="fa fa-google-plus"></i>
                        </a>
                      </div>

                      <div class="social-login-badge">
                        <a href="{{ url('account/login/twitter') }}" class="btn social-btns btn-social btn-twitter">
                          <i class="fa fa-twitter"></i>
                        </a>
                      </div>

                      <div class="social-login-badge">
                        <a href="{{ url('account/login/facebook') }}" class="btn social-btns btn-social btn-facebook">
                          <i class="fa fa-facebook"></i>
                        </a>
                      </div>

                    </li>

                    <li class="text-left">
                      <a href="{{ url(route('register')) }}" class="signup-btn btn-warning btn-block">Sign Up</a>
                    </li>

                    <li class="text-left ">
                      <a href="{{ url(route('login')) }}" class="signin-btn btn-block">Sign In</a>
                    </li>

                  </ul>
                </li>
              </ul>

            @endif


              {{-- not logged in actions available --}}
{{--               <div class="nav-btn1"><a href="{{ url(route('register')) }}" class="btn btn-warning btn-block">Sign Up</a></div>
              <div class="nav-btn2"><a href="{{ url(route('login')) }}" class="btn signin-btn btn-block">Sign In</a></div>

              <div class="social-login-badge social-margin-right">
                <a href="{{ url('account/login/instagram') }}" class="btn social-btns btn-social btn-instagram">
                  <i class="fa fa-instagram"></i>
                </a>
              </div>

              <div class="social-login-badge">
                <a href="{{ url('account/login/google') }}" class="btn social-btns btn-social btn-google-plus">
                  <i class="fa fa-google-plus"></i>
                </a>
              </div>

              <div class="social-login-badge">
                <a href="{{ url('account/login/twitter') }}" class="btn social-btns btn-social btn-twitter">
                  <i class="fa fa-twitter"></i>
                </a>
              </div>

              <div class="social-login-badge">
                <a href="{{ url('account/login/facebook') }}" class="btn social-btns btn-social btn-facebook">
                  <i class="fa fa-facebook"></i>
                </a>
              </div>

              <div class="social-txt">
                <span>Login With :</span>
              </div> --}}

            @endif

          </div>

        </nav>

      </div>
    </div>
  </div>

  {{-- If the session has the required data then show the button and info message --}}
  @if (Session::has('successMessage'))
    <div class="alert alert-success alert-dismissible" role="alert">
      <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
      {{ Session::get('successMessage') }}
    </div>
  @endif

  {{-- If the session has the required data the show the button and info message --}}
  @if (Session::has('dangerMessage'))
    <div class="alert alert-danger alert-dismissible" role="alert">
      <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
      {{ Session::get('dangerMessage') }}
    </div>
  @endif

  {{-- If the session has the required data the show the button and info message --}}
  @if (Session::has('infoMessage'))
    <div class="alert alert-info alert-dismissible" role="alert">
      <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
      {{ Session::get('infoMessage') }}
    </div>
  @endif

  {{-- If the session has the required data the show the button and info message --}}
  @if (Session::has('warningMessage'))
    <div class="alert alert-warnin alert-dismissibleg" role="alert">
      <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
      {{ Session::get('warningMessage') }}
    </div>
  @endif

  {{-- grab the content section --}}
  @yield('content')

  {{-- Start the footer --}}
  <div id="footer">
    <div class = "container">

      {{-- footer content --}}
      <div class = "copyright-txt">&copy; 2015 SHINDiiG LLC. All Rights Reserved.</div>
      <div class = "footer-btns"><a href="{{ url(route('tos')) }}">Terms and Conditions</a> | <a href="{{ url(route('privacy')) }}">Privacy Policy</a></div>

    </div>
  </div>

  {{-- Global suaray config object, to be used in angular ajax calls --}}
  <script type="text/javascript">var suarayConfig = {env: '{!! env('APP_ENV') !!}', api: {url: '{!! env('API_URL') !!}'}};</script>

  {{-- Javascript libraries needed on every single view --}}
  <script src="{{ ViewHelper::asset('assets/dist/lib-global.js') }}"></script>

  {{-- Javascript snippets/libraries needed by invidual views --}}
  @yield('javascript-includes')

  {{-- Angular suaray app --}}
  <script src="{{ ViewHelper::asset('assets/dist/suaray.js') }}"></script>

  {{-- Google analytics --}}
  <script>
    (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
    (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
    m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
    })(window,document,'script','//www.google-analytics.com/analytics.js','ga');
    ga('create', 'UA-64664719-1', 'auto');
    ga('send', 'pageview');
    @if (Auth::check())ga('set', '&uid', '{{ Auth::user()->id }}');@endif
  </script>
</body>
</html>
