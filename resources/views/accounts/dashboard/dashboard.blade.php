@extends('layouts.master')

@section('javascript-includes')
  <script src="{{ ViewHelper::asset('assets/dist/lib-accounts-profile.js') }}"></script>
@stop

@section('content')

  {{-- Session Message --}}
  @include('includes.sessionStatus')

  {{-- User feedback --}}
  @include('pages.feedback-tab')

  <div ng-controller="DashboardController">

    <div class="create-event-container">

      <div class="dashboard-table-bg">

        @include('accounts.dashboard.events.index')

      </div>

    </div>

  </div>

@stop
