
{{-- Event Title --}}
{{-- <div class = "events-detail-title">{{ $events['title'] }}</div> --}}
<div class = "event-detail-hero-header">
  @if ( ! empty($defaultPhoto))
		<img src="{{ $defaultPhoto['url']['original'] }}" alt="{{ $events['title'] }}">
	@else
    <div class="event-detail-default-image">

    </div>
	@endif

  <div class="event-detail-hero">
		<h1>{{ $events['title'] }}</h1>

    @if (! empty($events['venueName']) && ! empty($events['city']) && ! empty($events['state']))
			<p><i class="fa fa-fw fa-building"></i>{{$events['venueName']}}</p>
			<p><i class="fa fa-fw fa-map-marker"></i>{{ $events['city'] }} , {{ $events['state'] }}</p>
    @else
			<p><i class="fa fa-fw fa-map-marker"></i>{{ $events['city'] }} , {{ $events['state'] }}</p>
    @endif

	</div>

</div>
