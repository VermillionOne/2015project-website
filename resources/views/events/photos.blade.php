@extends('layouts.master')

@section('javascript-includes')
  <script src="{{ ViewHelper::asset('assets/dist/lib-events-show.js') }}"></script>
@stop

@section('content')

{{-- Wrapper for all of the event deatils page --}}
<div class = "event-detail-container">

  {{-- Session Message --}}
  @include('includes.sessionStatus')

  <div class="container view-event-show event-gallery-container" data-ng-controller="EventsShowController">
    <div class = "row gallery-header-btns">
      <div class = "col-xs-3">
          <a href="{{ route('events.show', ['slug' => $events['slug']]) }}">
            <button type="button" class="btn btn-default btn-gallery-l">Back To Event</button>
          </a>
      </div>

      {{-- Event Title and Location Text--}}
      <div class = "col-xs-6">
        <div class = "event-gallery-photo-title">{{ $events['title'] }}</div>
        <div class = "event-gallery-photo-address">{{ $events['address1'] }}</div>
      </div>
      <div class = "col-xs-3">
          <button type="button" class="btn btn-default btn-gallery-r">Add Photos</button>
      </div>

    </div>

    {{-- Show Event Date --}}
    <div class = "event-gallery-photo-date">
      @foreach ($events['times'] as $times)
        {{ date('m.d.Y', strtotime($times['start'])) }} to {{ date('m.d.Y', strtotime($times['end'])) }}<br>
      @endforeach
    </div>

    {{-- Show all images from the event --}}
    @if(empty($events['photos']))
      <h1 class="text-center text-muted pb50">No photos</h1>
    @else
    <div class = "row">
      @foreach ($events['photos'] as $photos)
        <div class="col-xs-6 col-sm-3 col-md-4 event-gallery-photo">
          <a class="grouped_elements" rel="group1" href="{{$photos['url']['original']}}">
            <img src="{{$photos['url']['318x190']}}" alt="{{$photos['createdAt']}}" class="eventhomepic">
          </a>
        </div>
      @endforeach
    </div>
    @endif

  </div>
</div>
@stop
