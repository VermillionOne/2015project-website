@extends('layouts.master')

@section('javascript-includes')

  {{-- For evaporate.js settings --}}
  <script>suarayConfig.upload = {@foreach($uploadSettings as $key => $value){!! "{$key}:'{$value}'," !!}@endforeach overrideSlug:'{{ $event['id'] }}'};</script>

  <script src="{{ ViewHelper::asset('assets/dist/lib-events-create.js') }}"></script>
@stop

@section('content')
{{-- User accesses upload media from admin / dash --}}
<div class="view-event-create">
  <div class="results-body" data-ng-controller="EventsCreateController">

    <div class="container">

      <div class="dashboard-titles">

        <div class="row">

          <div class="col-md-8 col-sm-8 col-xs-8">

            <div class="results-title-txt">Upload Media</div>

          </div>

          <div class="col-md-4 col-sm-4 col-xs-4">

            @include('accounts.dashboard.includes.editnav')

          </div>

        </div>

      </div>

      {{-- Upload media include --}}
      @include('events.create.media')

    </div>

  </div>
</div>

@stop
