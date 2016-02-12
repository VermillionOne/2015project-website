{{-- Will only allow attendees to chow if there are more than 10 --}}
@if ($attendeeList['attendeesCount'] > 9)

  <!-- {{$events['title']}} event description-->
  <div class="section-card">

    <div class = "events-detail-title">Attendees</div>

      @if (isset($events['meta']['rsvp']['enabled']) && $events['meta']['rsvp']['enabled'] === true)
      {{-- Guests Attending --}}
      <p class="center-text">{{ $attendeeList['attendeesCount'] }} RSVP'ed
      </p>
    @endif

    <div class="attendee-slide">

      <?php $index = 0; ?>

      @foreach($attendeeInfo as $attendees)

        {{-- Will only display 10 users --}}
        <?php if($index == 10) break; ?>

          {{-- Links to user profile --}}
          <a class="manage-link center-text" href="{{ route('profile.public', ['username' => $attendees['user']['username']]) }}">

            <div class ="profile-activity-details">

              <div class ="profile-default-image-thumb">

                {{-- User avatar --}}
                <img src="{{$attendees['user']['avatar']}}" border="0">

              </div>

            </div>

          </a>

      <?php $index++; ?>

      @endforeach

    </div>

  </div>

@endif
