<div class = "profile-sliders">


  <div class="profile-event-name ">Events Created By

    @if ($isAccessedPublicly)
      {{ $user['firstName'] }}&nbsp;{{ $user['lastName'] }}
    @else
      You
    @endif

  </div>

  {{-- If the user does not have any events --}}
  @if ($eventsCreatedByUser === null)
    <h1 class="text-center text-muted pb50">No Events</h1>
  @else

    <ul data-role="bx-slider-personal-events">

      {{-- Loop through all events --}}
      @foreach ($eventsCreatedByUser as $index => $event)

        {{-- Show the event image with a class of 'Active' --}}
        <div class="item @if ($index == 0) active @endif ">

          {{-- If there is not a pphoto avail for this event --}}
          @if (isset($event['featuredPhoto']))

            {{-- Show the event image --}}
            <a href="{{ route('events.show', ['slug' => $event['slug']]) }}">

              <div class="my-events-txt">

                {{-- Show the events title --}}
                <div class="event-title title-text-event"><span href="{{ route('events.show', ['slug' => $event['slug']]) }}">{{ $event['title'] }}</span></div>

              </div>

              <img src="{{ $event['featuredPhoto']['url']['218x190'] }}" alt="Suaray | Shindiig Slide {{ $event['featuredPhoto']['id'] }}" width="100%">
            </a>

          @else

            {{-- Show temp image --}}
            <a href="{{ route('events.show', ['slug' => $event['slug']]) }}">

              <div class="my-events-txt">

                {{-- Show the events title --}}
                <div class="event-title title-text-event"><span href="{{ route('events.show', ['slug' => $event['slug']]) }}">{{ $event['title'] }}</span></div>

              </div>

              <img src="{{ ViewHelper::asset('assets/img/transcoding/photo/image-not-yet-available-218x190.png') }}">
            </a>

          @endif

        </div>

      @endforeach

    </ul>

  @endif

</div>
