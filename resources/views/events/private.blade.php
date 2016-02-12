@extends('layouts.master')

@section('javascript-includes')
  <script src="{{ ViewHelper::asset('assets/dist/lib-events-show.js') }}"></script>
@stop

@section('content')

{{-- Wrapper for all of the event deatils page --}}
<div class = "event-detail-container">

  <div class="container view-event-show event-gallery-container">

      <div align="center">
        <h1 class="glyphicon glyphicon-lock"></h1>
        <h1>{{ $privateMessage }}</h1>
      </div>

  </div>
</div>
@stop
