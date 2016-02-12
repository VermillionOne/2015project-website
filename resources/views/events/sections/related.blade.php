{{-- Related events section --}}
<div class = "event-detail-col">
  <div class = "events-detail-title">Related Events</div>

    <ul data-role="bx-slider-related-pics">
      @foreach ($relatedEvents as $relatedEvent)

        <li>
          <a href="{{ url('events') }}/{{$relatedEvent['slug']}}">
            @if (isset($relatedEvent['photos']['0']))
              <img src="{{$relatedEvent['photos']['0']['url']['318x190']}}" alt="{{$relatedEvent['title']}}" class="eventhomepic">
            @elseif (isset($relatedEvent['featuredPhoto']['url']['318x190']))
              <img src="{{$relatedEvent['featuredPhoto']['url']['318x190']}}" alt="{{$relatedEvent['title']}}" class="eventhomepic">
            @endif
          </a>
        </li>
      @endforeach
    </ul>

</div>
