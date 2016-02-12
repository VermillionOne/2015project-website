@extends('layouts.master')

@section('javascript-includes')

  {{-- For evaporate.js settings --}}
  <script>suarayConfig.upload = {@foreach($uploadSettings as $key => $value){!! "{$key}:'{$value}'," !!}@endforeach random:0};

    var suarayEventCalendar = JSON.parse('{!! $myEventsformatted !!}');

  </script>

  {{-- Google ad --}}
  <script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>

  <script src="{{ ViewHelper::asset('assets/dist/lib-accounts-profile.js') }}"></script>
@stop

@section('content')

{{-- TODO: Start comments here --}}
<div class = "profile-container" ng-init="avatarPrep = false" ng-cloak class="ng-cloak">

  {{-- Session Message --}}
  @include('includes.sessionStatus')

  @include('pages.feedback-tab')

  <div class="container" ng-controller="AccountsProfileController">

    <div class="row">

      <div class="col-md-3">

        {{-- Displays Editable Admin Account information --}}
        @if ($isAccessedPublicly)
          @include('accounts.profile.public-profile-info')
        @else
          @include('accounts.profile.auth-profile-info')
        @endif

        {{-- TODO: Keep the same until rsvp table is finished --}}
        @include('accounts.profile.sponsored-events')

      </div>

      {{-- Right Colunmn starts here --}}
      <div class="col-md-9">

        @if ($isAccessedPublicly)
          @include('accounts.profile.calendar')
        @else
          {{-- Begins Calendar --}}
          @include('accounts.profile.calendar')
        @endif

        @if (! empty($eventsCreatedByUser))

          {{-- Begin events created by this user --}}
          @include('accounts.profile.created-events')

        @endif

        {{-- Begin this users rsvp'ed events --}}
        @include('accounts.profile.rsvp-events')

        {{-- Begins Activity Wall section --}}
        @include('accounts.profile.wall-comments')

      </div>

      {{-- Commenting out until functionality added --}}
  {{--<div class="col-xs-12">

        <div class="section-card">

          @include('accounts.profile.weather')

        </div>

      </div> --}}

    </div>

  </div>

</div>

@stop
