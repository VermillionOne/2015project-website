<article class="event-detail-col-r-widget event-time-section">

	{{-- Start event details box --}}
	<h3 class="events-detail-title-r-col">Details</h3>

	@if (!empty($events['venueName']))
		<p class="event-venue"><span class="fa fa-fw fa-building"></span>{{$events['venueName']}}
		</p>
	@endif

	{{-- Event Location --}}
	<p class="event-location"><span class="fa fa-fw fa-map-marker"></span>{{ isset($events['address1']) ? $events['address1'] : '' }}
		<br>{{ isset($events['city']) ? $events['city']  : '' }} {{ isset($events['state']) ? $events['state'] : '' }} {{ isset($events['zipcode']) ? $events['zipcode'] : '' }}
	</p>

  @if (!empty($events['phone']))
		<p><span class="fa fa-fw fa-phone"></span>{{$events['phone']}}
		</p>
	@endif

	<p>See More Events By:
		<br><a href="events/search?q={{ $events['user']['username'] }}" name="search-user">{{ $events['user']['firstName']}} {{ $events['user']['lastName']}}</a>
	</p>

</article>
