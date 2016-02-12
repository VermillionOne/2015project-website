

    <div class="my-events">

      <div class="search-results-title">
        <h2 class="results-title-txt">{{ Auth::user()->first_name }}'s Events</h2>
      </div>

      <div class="row">
        @if(count($events))

          @foreach ($events as $event)

            <div class="col-xs-12 col-sm-4 col-md-3">
              <div class="my-event-results">

                {{-- Events linked image if no image, shows generating--}}
                <a href="{{ url('events') }}/{{ $event['slug'] }}" name="view-by-image-{{ $event['id'] }}">
                  @if (! empty($event['photos']))
                    <img src="{{ $event['photos'][0]['url']['318x190'] }}" alt="Suaray | Shindiig Slide {{ $event['photos'][0]['id'] }}" width="100%">
                  @else
                    <div class="my-events-default-image">

                    </div>
                  @endif
                </a>

                <div class="search-results-txt">

                  <a class="my-events-event-title" href="{{ url('events') }}/{{ $event['slug'] }}" name="view-by-slug-{{ $event['id'] }}">{{ str_limit($event['title'], 44) }}</a>

                  <p class="my-events-event-update">
                    Updated: {{ date('F d', strtotime($event['updatedAt'])) }}
                  </p>

                  <p class="my-events-event-published">

                    @if ($event['isPublished'])
                      This Event is : </br><span class="label label-success">Published</span>
                    @else
                      This Event is : <span class="label label-danger">Not Published</span>
                    @endif
                  </p>
                  <p>
                    @if ($event['times'] === 1)
                      {{ count($event['times'])}} upcoming date.
                    @elseif ($event['times'] > 1)
                      {{ count($event['times'])}} upcoming dates.
                    @else
                      There are no future dates for this event
                    @endif

                  </p>

                </div>
              </div>

              <div class="my-event-options dropdown" role="group" aria-label="...">

                <a type="button" href="#" class="btn btn-block my-event-options-button dropdown-toggle" id="menu1" data-toggle="dropdown"><i class="fa fa-cog fa-lg"></i>&nbsp;Manage Event</a>

                {{-- Drop up for edit options --}}
                <ul class="dropdown-menu my-event-options-dropdown" role="menu" aria-labelledby="menu1">

                  {{-- Shows dashboard details with manage options --}}
                  <li role="presentation">
                    <a href="{{ route('events.show', ['id' => $event['slug']]) . '/updateevent' }}" name="event-edit-details-{{ $event['id']}}" class="manage-link">Details</a>
                  </li>

                  {{-- Index of upcoming dates, can create and edit --}}
                  <li role="presentation">
                    <a href="{{ route('schedules.index', ['id' => $event['id']]) }}" class="manage-link" name="event-edit-schedules-{{ $event['id']}}">Schedules</a>
                  </li>

                  {{-- Index of tickets, can create and edit --}}
                  <li role="presentation">
                    <a href="{{ route('tickets.index', ['id' => $event['id']]) }}" name="event-edit-tickets-{{ $event['id']}}" class="manage-link">Tickets</a>
                  </li>

                  {{-- Shows photos with options to edit --}}
                  <li role="presentation">
                    <a href="{{ route('events.gallery', ['id' => $event['slug']]) }}" class="manage-link" name="event-edit-photos-{{ $event['id']}}">Photos</a>
                  </li>

                  {{-- Shows dashboard details with manage options --}}
                  <li role="presentation">
                    <a href="{{ route('events.details', ['id' => $event['id']]) }}" class="manage-link" name="event-view-analytics-{{ $event['id']}}">Analytics</a>
                  </li>

                  {{-- Shows dashboard details with manage options --}}
                  <li role="presentation">
                    <a confirmationNeeded ng-href="{{ route('events.delete', ['id' => $event['id']]) }}" class="manage-link" name="event-view-analytics-{{ $event['id']}}">Delete Event</a>
                  </li>

                </ul>

              </div>
            </div>

          @endforeach

        @else

          <div class="col-md-12">
            <div class="poll-card manage-link">

              {{-- If no events, default message will show --}}
              <div class="default-schedule-message">
                <h5 class="text-muted">No events created yet</h5>
                <h5 class="text-muted">Create your event <a href="{{ url(route('events.create')) . '?tab=freemium'}}">here</a> or go to <a href="{{ route('my-tickets') }}">My Tickets</a> </br> to view your purchased tickets!</h5>
              </div>

            </div>
          </div>

        @endif
      </div>

      {{-- Pagination for more than twenty events --}}
      <div class = "container">
        <div class="row">
          <div class="col-xs-12 dash-padding">

            @include('pages.default-pagination', ['paginator' => $events])
          </div>
        </div>
      </div>

    </div>
