@extends('layouts.master')

@section('content')
<div class="container view-event-show">
  <h1 class="page-header"># {{ $tagName }}</h1>
  @if(count($tags) < 1)
  <h1 class="text-center text-muted">Sorry there are no events for the tag #{{$tagName}}</h1>
  @else

    <table class="table table-hover">
    @foreach($tags as $event)
      <tr>
        <td><a href="{{ URL::to('/') }}/event/{{ $event['slug'] }}">{{ $event['title'] }}</a></td>
      </tr>
   @endforeach
    </table>
  @endif
  <div class="text-center">
  </div>
</div>
@stop
