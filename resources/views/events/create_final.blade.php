@extends('layouts.master')

@section('content')
<div class="container view-event-crate_photo">
  @include('events.create.step')
  <hr class="mt0">
  <div class="row pt30">
    <h1 class="text-center">{{ $event['title'] }}</h1>
    <h4>Event Schedule:</h4>
    <div style="max-height: 200px; overflow-y: auto;">
      @if (isset($eventTimes['eventScheduleMeta']))
        This is a {{ $eventTimes['eventScheduleMeta']['type'] }} event starting at <div>{{ date("m/d/Y &#64; g:i", strtotime($eventTimes['eventScheduleMeta']['oneTimeStart'])) }} - {{ date("m/d/Y &#64; g:i", strtotime($eventTimes['eventScheduleMeta']['oneTimeEnd'])) }} (UTC)</div>
      @endif
    </div>
    <hr/>
    <p class="well well-sm">{{ $event['description'] }}</p>
    <div class="mt40 events-create-slide">
        @if(count($photos) < 1)
          <h1 class="text-center text-muted pb50">No photos</h1>
        @else
          <ul class="event-detail-bxslider">
            @foreach($photos as $slideNumber => $photo)
              <li><img src="{{ $photo['url']['318x190'] }}"></li>
            @endforeach
          </ul>
          <script type="text/javascript">
            $('.event-detail-bxslider').bxSlider({
              minSlides: 3,
              maxSlides: 20,
              slideWidth: 940,
              slideMargin: 10
            });
          </script>
        @endif
    </div>
  </div>
  <hr>
  <div class="text-right">
    <a href="{{ action('EventsController@doCreateFinal', ['id' => $event['id']]) }}" class="btn btn-primary"><span class="glyphicon glyphicon-hand-right"></span> Publish</a>
  </div>
</div>
@stop
