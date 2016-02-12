<span class="dropdown dropdown-more">

  <a href="#" class="dropdown-toggle search-clear" type="button" id="menu1" data-toggle="dropdown">
    More
  </a>
  <ul class="dropdown-menu-more dropdown-menu" role="menu" aria-labelledby="menu1">

    <li role="presentation">
      <a class="manage-link" href="{{ route('events.show', ['id' => $event['slug']]) . '/updateevent' }}">Edit : Details</a>
    </li>
    <li role="presentation">
      <a class="manage-link" href="{{ route('events.gallery', ['id' => $event['slug']]) }}">Edit : Photos</a>
    </li>
    <li role="presentation">
      <a class="manage-link" href="{{ route('schedules.index', ['id' => $event['id']]) }}">Edit : Schedules</a>
    </li>
    <li role="presentation">
      <a class="manage-link" href="{{ route('tickets.index', ['id' => $event['id']]) }}">Edit : Tickets</a>
    </li>
    <li role="presentation">
      <a class="manage-link" href="{{ url('events') }}/{{ $event['slug'] }}">View : Event</a>
    </li>
    <li role="presentation">
      <a class="manage-link" href="{{ route('events.details', ['id' => $event['id']]) }}">View : Analytics</a>
    </li>

  </ul>
</span>

