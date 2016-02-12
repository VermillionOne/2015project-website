<div class="profile-sponsored-ad-card">

  <div class="section-card">

    <div class="profile-sponsored-event-title">SPONSORED</div>

    {{-- If there arent any sponsored events --}}
    @if (empty($sponsoredEvents))
      <h1 class="text-center text-muted pb50">No Events</h1>
    @else

      {{-- Loop through all events --}}
      @foreach ($sponsoredEvents as $event)

        {{-- Show the event image with details --}}
        <div class="sponsored-profile-col">

          <div class="profile-sponsored-ad">

            {{-- If we have photos for this event --}}
            @if (isset($event['featuredPhoto']))

              {{-- Else show the actual event image --}}
              <a href="{{ url('events') }}/{{ $event['slug'] }}">
                <img src="{{ $event['featuredPhoto']['url']['218x190'] }}" alt="Event Photo" class="eventhomepic">
              </a>

            @else

              {{-- Show the placehold image --}}
              <a href="{{ url('events') }}/{{ $event['slug'] }}">
                <img src="{{ ViewHelper::asset('assets/img/transcoding/photo/image-not-yet-available-318x190.png') }}">
              </a>

            @endif

          </div>

          {{-- Sponsored events detail snip --}}
          <div class="profile-sponsored-name">{{ str_limit($event['title'], 15) }}</div>

          <div class = "profile-sponsored-txt">{{ str_limit($event['description'], 50) }}</div>

        </div>

      @endforeach

    @endif

  </div>

</div>
