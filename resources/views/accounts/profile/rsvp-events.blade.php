<div class = "profile-sliders">

  <div class="profile-event-name ">Upcoming RSVP’d Events</div>

  {{-- If the user does not have any events --}}
  @if (count($userEventsTimesAttending) == 0)
    <h1 class="text-center text-muted pb50">No Events</h1>
  @else

    <ul data-role="bx-slider-upcoming-events">

    @foreach ($userEventsTimesAttending as $time)

      @if (isset($time['event']))

        {{-- If there is not a pphoto avail for this event --}}
        @if (isset($time['event']['auth']['isAttending']) && ($time['event']['auth']['isAttending'] == 'yes'))

          {{-- Show the event image --}}
          <a href="{{ route('events.show', ['slug' => $time['event']['slug']]) }}">

            <div class="my-events-txt">

              {{-- Show the events title --}}
              <div class="event-title title-text-event"><span href="{{ route('events.show', ['slug' => $time['event']['slug']]) }}">{{ $time['event']['title'] }}</span></div>

            </div>

            <img src="{{ $time['event']['featuredPhoto']['url']['218x190'] }}" alt="Suaray | Shindiig Slide {{ $time['event']['featuredPhoto']['id'] }}" width="100%">

          </a>

        @else
          {{-- Show temp image --}}
          <a href="{{ route('events.show', ['slug' => $time['event']['slug']]) }}">

            <div class="my-events-txt">

              {{-- Show the events title --}}
              <div class="event-title title-text-event"><span href="{{ route('events.show', ['slug' => $time['event']['slug']]) }}">{{ $time['event']['title'] }}</span></div>

            </div>

            <img src="{{ ViewHelper::asset('assets/img/transcoding/photo/image-not-yet-available-218x190.png') }}">

          </a>

        @endif

      @endif

    @endforeach

  @endif

</div>
