{{-- <div class="dashboard-titles"> --}}

<div class="row dashboard-titles">

  <div class="col-md-4 col-sm-4 col-xs-12">

    <h2 class="results-title-txt">ADMIN</h2>

  </div>

  <div class="col-md-2 col-sm-2 col-xs-2">

    {{-- Choose event privacy settings --}}
    <span class="dropdown dropdown-more">
      <a href="#" class="dropdown-toggle manage-link search-clear" type="button" id="menu1" data-toggle="dropdown">
        <i class="fa fa-unlock-alt"></i>&nbsp;Private
      </a>
      <ul class="dropdown-menu" role="menu" aria-labelledby="menu1">

        @if ($events['meta']['private']['shareableLink'] == false)
          <li role="presentation"><a class="alert-success" role="menuitem" tabindex="-1" href="{{ route('events.doUpdateEvent', ['id' => $events['id'], 'data' => ['meta' => ['private' => ['shareableLink' => 1]]]]) }}">Shareable Link</a></li>
        @else
          <li role="presentation"><a class="alert-danger" role="menuitem" tabindex="-1" href="{{ route('events.doUpdateEvent', ['id' => $events['id'], 'data' => ['meta' => ['private' => ['shareableLink' => 0]]]]) }}">Shareable Link</a></li>
        @endif


        @if ($events['meta']['private']['friendsOnly'] == false)
          <li role="presentation"><a class="alert-success" role="menuitem" tabindex="-1" href="{{ route('events.doUpdateEvent', ['id' => $events['id'], 'data' => ['meta' => ['private' => ['friendsOnly' => 1]]]]) }}">Friends Only</a></li>
        @else
          <li role="presentation"><a class="alert-danger" role="menuitem" tabindex="-1" href="{{ route('events.doUpdateEvent', ['id' => $events['id'], 'data' => ['meta' => ['private' => ['friendsOnly' => 0]]]]) }}">Friends Only</a></li>
        @endif


        @if ($events['meta']['private']['byInvite'] == false)
          <li role="presentation"><a class="alert-success" role="menuitem" tabindex="-1" href="{{ route('events.doUpdateEvent', ['id' => $events['id'], 'data' => ['meta' => ['private' => ['byInvite' => 1]]]]) }}">By Invite</a></li>
        @else
          <li role="presentation"><a class="alert-danger" role="menuitem" tabindex="-1" href="{{ route('events.doUpdateEvent', ['id' => $events['id'], 'data' => ['meta' => ['private' => ['byInvite' => 0]]]]) }}">By Invite</a></li>
        @endif

      </ul>
    </span>

  </div>

  <div class="col-md-2 col-sm-2 col-xs-12">
    {{-- If event is published, show option to unpublish --}}
    @if ($events['isPublished'] === true)

      {!! ViewHelper::formOpen([ 'action' => ['EventsController@doEventPublish', 'id' => $events['id']]]) !!}

        {{-- Option Off Button --}}
       <label ng-click="isPublished = 'null'" class="event-preview publish-link manage-link search-clear"><i class="fa fa-share"></i>&nbsp;Unpublish
         {!! Form::radio('isPublished', 0, false,  ['autocomplete' => 'off', 'class' => 'publish-form', 'onClick' => 'this.form.submit()']) !!}
        </label>

        <button class="hidden" type="submit"></button>

      {!! Form::close()!!}

    @else

      {!! ViewHelper::formOpen([ 'action' => ['EventsController@doEventPublish', 'id' => $events['id']]]) !!}

        {{-- Option Off Button --}}
        <label ng-click="isPublished = 'null'" class="event-preview search-clear"><i class="fa fa-share"></i>&nbsp;Publish

          {!! Form::radio('isPublished', true, false,  ['autocomplete' => 'off', 'class' => 'publish-form', 'onClick' => 'this.form.submit()']) !!}

        </label>

        <button class="hidden" type="submit"></button>

      {!! Form::close()!!}

    @endif
  </div>

  <div class="col-md-2 col-sm-2 col-xs-12">

    {{-- View more button to get to edit options --}}
    <span class="dropdown dropdown-more">

      <a href="#" class="dropdown-toggle search-clear manage-link" type="button" id="menu1" data-toggle="dropdown">
        <i class="fa fa-ellipsis-v"></i>&nbsp;Features
      </a>
      <ul class="dropdown-features dropdown-menu" role="menu" aria-labelledby="menu1">

        <li role="presentation">

          {{-- If rsvp is enabled, show option to disable rsvp --}}
          @if ($events['meta']['rsvp']['enabled'] === true)

            {!! ViewHelper::formOpen([ 'action' => ['EventsController@doUpdateRsvp', 'id' => $events['id']]]) !!}

              {{-- Option Off Button --}}
             <label ng-click="enabled = 'null'" class="event-preview publish-link manage-link features-dropdown">Disable RSVP
               {!! Form::radio('meta[rsvp][enabled]', 0, false,  ['autocomplete' => 'off', 'class' => 'publish-form', 'onClick' => 'this.form.submit()']) !!}
              </label>

              <button class="hidden" type="submit"></button>

            {!! Form::close()!!}

          @else

            {!! ViewHelper::formOpen([ 'action' => ['EventsController@doUpdateRsvp', 'id' => $events['id']]]) !!}

              {{-- Option on Button --}}
              <label ng-click="enabled = 'null'" class="event-preview publish-link manage-link features-dropdown">Enable RSVP

                {!! Form::radio('meta[rsvp][enabled]', true, false,  ['autocomplete' => 'off', 'class' => 'publish-form', 'onClick' => 'this.form.submit()']) !!}

              </label>

              <button class="hidden" type="submit"></button>

            {!! Form::close()!!}

          @endif

        </li>

        <li role="presentation">

           {{-- If rsvp is enabled, show option to disable rsvp --}}
          @if ($events['meta']['comments']['enabled'] === true)

            {!! ViewHelper::formOpen([ 'action' => ['EventsController@doUpdateComments', 'id' => $events['id']]]) !!}

              {{-- Option Off Button --}}
             <label ng-click="enabled = 'null'" class="event-preview publish-link manage-link features-dropdown">Disable Comments
               {!! Form::radio('meta[comments][enabled]', 0, false,  ['autocomplete' => 'off', 'class' => 'publish-form', 'onClick' => 'this.form.submit()']) !!}
              </label>

              <button class="hidden" type="submit"></button>

            {!! Form::close()!!}

          @else

            {!! ViewHelper::formOpen([ 'action' => ['EventsController@doUpdateComments', 'id' => $events['id']]]) !!}

              {{-- Option on Button --}}
              <label ng-click="enabled = 'null'" class="event-preview publish-link manage-link features-dropdown">Enable Comments

                {!! Form::radio('meta[comments][enabled]', true, false,  ['autocomplete' => 'off', 'class' => 'publish-form', 'onClick' => 'this.form.submit()']) !!}

              </label>

              <button class="hidden" type="submit"></button>

            {!! Form::close()!!}

          @endif

        </li>

        <li role="presentation">

          {{-- If rsvp is enabled, show option to disable rsvp --}}
          @if ($events['meta']['map']['enabled'] === true)

            {!! ViewHelper::formOpen([ 'action' => ['EventsController@doUpdateMap', 'id' => $events['id']]]) !!}

              {{-- Option Off Button --}}
             <label ng-click="enabled = 'null'" class="event-preview publish-link manage-link features-dropdown">Disable Maps
               {!! Form::radio('meta[map][enabled]', 0, false,  ['autocomplete' => 'off', 'class' => 'publish-form', 'onClick' => 'this.form.submit()']) !!}
              </label>

              <button class="hidden" type="submit"></button>

            {!! Form::close()!!}

          @else

            {!! ViewHelper::formOpen([ 'action' => ['EventsController@doUpdateMap', 'id' => $events['id']]]) !!}

              {{-- Option on Button --}}
              <label ng-click="enabled = 'null'" class="event-preview publish-link manage-link features-dropdown">Enable Maps

                {!! Form::radio('meta[map][enabled]', true, false,  ['autocomplete' => 'off', 'class' => 'publish-form', 'onClick' => 'this.form.submit()']) !!}

              </label>

              <button class="hidden" type="submit"></button>

            {!! Form::close()!!}

          @endif

        </li>

        <li role="presentation">
           {{-- If rsvp is enabled, show option to disable rsvp --}}
          @if ($events['meta']['reviews']['enabled'] === true)

            {!! ViewHelper::formOpen([ 'action' => ['EventsController@doUpdateReviews', 'id' => $events['id']]]) !!}

              {{-- Option Off Button --}}
             <label ng-click="enabled = 'null'" class="event-preview publish-link manage-link features-dropdown">Disable Reviews
               {!! Form::radio('meta[reviews][enabled]', 0, false,  ['autocomplete' => 'off', 'class' => 'publish-form', 'onClick' => 'this.form.submit()']) !!}
              </label>

              <button class="hidden" type="submit"></button>

            {!! Form::close()!!}

          @else

            {!! ViewHelper::formOpen([ 'action' => ['EventsController@doUpdateReviews', 'id' => $events['id']]]) !!}

              {{-- Option on Button --}}
              <label ng-click="enabled = 'null'" class="event-preview publish-link manage-link features-dropdown">Enable Reviews

                {!! Form::radio('meta[reviews][enabled]', true, false,  ['autocomplete' => 'off', 'class' => 'publish-form', 'onClick' => 'this.form.submit()']) !!}

              </label>

              <button class="hidden" type="submit"></button>

            {!! Form::close()!!}

          @endif
        </li>

      </ul>
    </span>

  </div>

  <div class="col-md-2 col-sm-2 col-xs-12">
    {{-- View more button to get to edit options --}}
    <span class="dropdown dropdown-more">

      <a href="#" class="dropdown-toggle search-clear manage-link" type="button" id="menu1" data-toggle="dropdown">
      <i class="fa fa-plus"></i>&nbsp;More
      </a>
      <ul class="dropdown-menu-more dropdown-menu" role="menu" aria-labelledby="menu1">

        <li role="presentation">
          <a class="manage-link" href="{{ route('events.show', ['id' => $events['id']]) . '/updateevent' }}">Edit : Details</a>
        </li>
        <li role="presentation">
          <a class="manage-link" href="{{ route('events.gallery', ['id' => $events['slug']]) }}">Edit : Photos</a>
        </li>
        <li role="presentation">
          <a class="manage-link" href="{{ route('schedules.index', ['id' => $events['id']]) }}">Edit : Schedules</a>
        </li>
        <li role="presentation">
          <a class="manage-link" href="{{ route('tickets.index', ['id' => $events['id']]) }}">Edit : Tickets</a>
        </li>
        <li role="presentation">
          <a class="manage-link" href="{{ route('events.delete', ['id' => $events['id']]) }}">Edit : Delete Event</a>
        </li>
        <li role="presentation">
          <a class="manage-link" href="{{ route('events.details', ['id' => $events['id']]) }}">View : Analytics</a>
        </li>
      </ul>
    </span>
  </div>

</div>

{{-- </div> --}}
{{-- If event published, show confirmation message --}}
@if ($events['isPublished'] === true)

<div class="publish-card bg-success">
  <div class = "events-detail-subtitle">
    <ul class="admin-event-btn">
      <div class = "events-detail-title-r-col">
        <div class="text-success">
          <span> Your event is currently published</span>
        </div>
      </div>
    </ul>
  </div>
</div>

@else

<!-- If event not published, show confirmation message -->
<div class="publish-card bg-danger">
  <div class = "events-detail-subtitle">
    <ul class="admin-event-btn">
      <div class = "events-detail-title-r-col">
        <div class="publish-warning">
          <span class="text-danger">Your event is currently unpublished</span>
        </div>
      </div>
    </ul>
  </div>
</div>

@endif
