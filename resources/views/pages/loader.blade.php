@extends('layouts.master')

@section('javascript-includes')

  <script type="text/javascript">

    var redirectData = JSON.parse('{!! json_encode($data) !!}');

  </script>

@stop

@section('content')

  <div class="view-event-create" ng-controller="LoaderController" ng-cloak class="ng-cloak">

    <div class="container">

        <div class="row">

          <div class="col-md-12">

            <div class="schedule-card manage-link">

              <div class = "friends-invite-confirmation">

                <h5 class="text-muted">@{{redirectData.message}} <span class="loading-dots"><span>.</span><span>.</span><span>.</span></span>

              </div>

            </div>

          </div>

        </div>

    </div>

  </div>

@stop
