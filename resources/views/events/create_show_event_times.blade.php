@extends('layouts.master')

@section('content')
<div class="container">

  @foreach ($times as $time)
    <strong>Start:</strong> {{$time['start']}}<br>
    <strong>End:</strong> {{$time['end']}}<br><br>
  @endforeach
</div>
@stop

