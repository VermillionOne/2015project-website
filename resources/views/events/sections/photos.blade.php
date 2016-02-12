 {{-- User Pic section --}}
  <div class = "event-detail-col">
    <div class = "events-detail-title">{{ isset($events['meta']['photos']['label']) ? $events['meta']['photos']['label'] : 'Photos' }}</div>
    @if(empty($events['photos']))
      <h1 class="text-center text-muted pb50">No photos</h1>
    @else
    {{-- {{ dd($events['photos']) }} --}}
      <ul data-role="bx-slider-user-pics">
      @foreach ($events['photos'] as $photos)
        <li>
          <a class="grouped_elements" rel="group1" href="{{$photos['url']['original']}}"><img src="{{$photos['url']['318x190']}}" alt="{{$photos['createdAt']}}" class="eventhomepic"></a>
        </li>
      @endforeach
      </ul>
    @endif

    <a href="{{ route('events.gallery', ['slug' => $events['slug']]) }}" class="relative-link">View All</a>

  </div>
