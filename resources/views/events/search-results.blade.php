@extends('layouts.master')

@section('javascript-includes')
@stop

@section('content')

<div class="results-body view-event-create">

	@include('events.search-form')

	<div class="container" ng-controller="SearchResultsController">

		<div class="search-results-title">

      <div class="row">

        {{-- Search results header --}}
        <div class="col-md-8 col-sm-6 col-xs-10">

          <div class="results-title-txt">Search Results</div>

        </div>

      </div>

		</div>

	<div class="grid-results grid">
		<div class="row">

			@if (isset($events['success']) && $events['success'] === false)

				<h1 class="text-center text-muted pb50">{{ $events['error'] }}</h1>

			@else

				{{-- Loop through all events --}}
		    @foreach ($events as $event)


					<div class="search-result col-xs-12 col-sm-4 col-md-3">

						{{-- Events linked image if no image, shows generating--}}
						<a href="{{ url('events') }}/{{ $event['slug'] }}" name="{{$event['id']}}-by-image">

							@if (! empty($event['photos']))
								<img src="{{ $event['photos'][0]['url']['460x275'] }}" alt="Suaray | Shindiig Slide {{ $event['photos'][0]['id'] }}" width="100%">
							@else
                <div class="search-results-default-image">

                </div>
							@endif

						</a>

            <div class="search-grid-info-container">

							{{-- Show the events title --}}
							<p class="search-results-event-title">
								<a href="{{ url('events') }}/{{ $event['slug'] }}">{{ str_limit($event['title'], 44) }}</a>
							</p>

							@if (! empty($event['city']) || ! empty($event['state']))

								{{-- Show the events city and state --}}
								<p class="search-results-event-location"><span class="glyphicon glyphicon-map-marker search-listview-icon"></span> {{ $event['city'] }}, {{ strtr($event['state'], $trans) }}</p>
							@else

								@if (isset($event['venueName']))
									<span class="glyphicon glyphicon-map-marker"></span> {{ $event['venueName'] }}
								@endif

							@endif

              {{-- If times exist, show first upcoming date --}}
              @if ($event['nextEvent'])

                <p class="search-dates">
                  <i class="fa fa-calendar search-listview-clock"></i> {{ date('M d', strtotime($event['nextEvent']['start'])) }} @ {{ date('g:i a', strtotime($event['nextEvent']['start'])) }}
                </p>

              @else

                <p class="search-dates">
                  <i class="fa fa-calendar search-listview-clock"></i> Event Dates Passed
                </p>

              @endif

              @if (($event['times']) && ($event['nextEvent']))

                <!-- Button trigger modal -->
                <button type="button" class="search-more-times-button btn-suaray btn-xs btn-suaray-positive" data-toggle="modal" data-target="#modal-{{$event['slug']}}">
                  View More Times
                </button>

                <!-- Modal -->
                <div class="modal fade" id="modal-{{$event['slug']}}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                  <div class="modal-dialog modal-sm" role="document">
                    <div class="modal-content">
                      <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="myModalLabel">{{$event['title']}}</h4>
                      </div>
                      <div class="modal-body">

                        @foreach($event['times'] as $time)
                          <p>{{date('M d y', strtotime($time['start']))}} | {{date('g:i a', strtotime($time['start']))}}</p>
                        @endforeach

                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn-suaray btn-sm btn-suaray-plain" data-dismiss="modal">Close</button>
                        <a type="button" href="{{ url('events') }}/{{ $event['slug'] }}" class="btn-suaray btn-sm btn-suaray-primary" >View event</a>
                      </div>
                    </div>
                  </div>
                </div>

              @else

                <p class="search-no-dates label label-danger">No Upcoming Events</p>

              @endif

							{{-- The events description --}}
							<p class="search-results-view-event-info">
								{{ str_limit($event['description'], 65) }}
							</p>

							{{-- Event tags, space still exists if no tags--}}
							<div class="event-tags-holder-grid">
								@if ($event['tags'])

									@foreach ($event['tags'] as $tag)
										<p class="search-results-tags"><a class="btn btn-github btn-xs" href="{{ route('events.search', ['q' => $tag['tag']]) }}">{{ $tag['tag'] }}</a></p>
									@endforeach

								@endif
							</div>
              <div class="search-result-user-holder">
                {{-- Event owner avatar --}}
                <img class="search-listview-user-image" src="{{ $event['user']['avatar'] }}" height="100%" />

                {{-- Event owner name --}}
                <p>
                  <a class="manage-link" href="{{ route('profile.public', ['username' => $event['user']['username']]) }}">By {{ $event['user']['firstName']}} {{ $event['user']['lastName']}}</a>
                </p>
              </div>
            </div>

            <a class="search-results-view-event" href="{{ url('events') }}/{{ $event['slug'] }}" name="view-{{$event['slug']}}">View Event</a>


					</div>

		    @endforeach

	  	@endif

		</div>
	</div>


	<div class="grid-results list">
		<div class="row">

			@if (isset($events['success']) && $events['success'] === false)

				<h1 class="text-center text-muted pb50">{{ $events['error'] }}</h1>

			@else

				{{-- Loop through all events --}}
		    @foreach ($events as $event)

					<div class="col-md-12 search-listview-container">

            <div class="row search-listview-row">

              <div class="search-listview-image-container col-md-2 col-sm-2 col-xs-2"> <!-- Start of user -->

                <a class="search-results-list-image" href="{{ url('events') }}/{{ $event['slug'] }}">

									{{-- Events linked image if no image, shows generating with view event button --}}
									@if (! empty($event['photos']))

										<img src="{{ $event['photos'][0]['url']['218x190'] }}" alt="Suaray | Shindiig Slide {{ $event['photos'][0]['id'] }}" width="100%">
										<p>View Event</p>

									@else

                    <div class="search-results-default-image-list">

                    </div>
										<p>View Event</p>

									@endif

                </a>

              </div>

              <div class="col-md-4 col-sm-4 col-xs-4">
                <div class="search-results-text">

                  {{-- Link to event from request --}}
                  <p class="search-results-event-title"><a href="{{ url('events') }}/{{ $event['slug'] }}">{{ str_limit($event['title'], 44) }}</a></p>

                  {{-- If city is not null, will display --}}
                  <p class="search-listview-subtext">{{ str_limit($event['description'], 80) }}</p>

                  {{-- Event owner avatar--}}
                  <div class="search-listview-user-image">
                    	<img src="{{ $event['user']['avatar'] }}" height="100%" />
                  </div>

                  {{-- Event owner name --}}
                  <p class="search-listview-created-by">
                    <a class="manage-link" href="{{ route('profile.public', ['username' => $event['user']['username']]) }}">By {{ $event['user']['username']}}</a>
                  </p>

                </div>
              </div>

              <div class="col-md-3 col-sm-3 col-xs-3">
                <div class="search-results-text">
  								{{-- Event city --}}
  								<div class="search-results-event-location">
  									@if (! empty($event['city']) || ! empty($event['state']))
  										{{-- Show the events city and state --}}
  										<span class="glyphicon glyphicon-map-marker search-listview-icon"></span> {{ $event['city'] }}, {{ strtr($event['state'], $trans) }}
  									@else

  										@if (isset($event['venueName']))
  											<span class="glyphicon glyphicon-map-marker search-listview-icon"></span> {{ $event['venueName'] }}
  										@endif

  									@endif
  								</div>

  								{{-- If times exist, show first upcoming date --}}
  								@if (isset($event['nextEvent']))

  									<p class="search-dates">
  										<i class="fa fa-calendar search-listview-clock"></i> {{ date('F d', strtotime($event['nextEvent']['start'])) }} | {{ date('g:i a', strtotime($event['nextEvent']['start'])) }}
  									</p>

                  @else

                    <p class="search-no-dates label label-danger">No Upcoming Events</p>

  								@endif

                  @if (($event['times']) && ($event['nextEvent']))

                    <!-- Button trigger modal -->
                    <button type="button" class="search-more-times-button btn-suaray btn-xs btn-suaray-positive" data-toggle="modal" data-target="#row-modal-{{$event['slug']}}">
                      View More Times
                    </button>

                    <!-- Modal -->
                    <div class="modal fade" id="row-modal-{{$event['slug']}}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                      <div class="modal-dialog modal-sm" role="document">
                        <div class="modal-content">
                          <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title" id="myModalLabel">{{$event['title']}}</h4>
                          </div>
                          <div class="modal-body">

                            @foreach($event['times'] as $time)
                              <p>{{date('M d y', strtotime($time['start']))}} | {{date('g:i a', strtotime($time['start']))}}</p>
                            @endforeach

                          </div>
                          <div class="modal-footer">
                            <button type="button" class="btn-suaray btn-sm btn-suaray-plain" data-dismiss="modal">Close</button>
                            <a type="button" href="{{ url('events') }}/{{ $event['slug'] }}" class="btn-suaray btn-sm btn-suaray-primary" >View event</a>
                          </div>
                        </div>
                      </div>
                    </div>

                  @endif
                </div>

          		</div>

              <div class="col-md-3 col-sm-3 col-xs-3 event-tags-holder">

								@if ($event['tags'])

									@foreach ($event['tags'] as $tag)

										<p class="search-results-tags">
                      <a class="btn btn-github btn-xs" href="{{ route('events.search', ['q' => $tag['tag']]) }}">{{ $tag['tag'] }}</a>
                    </p>

									@endforeach

								@endif

              </div>

            </div>

      		</div>

		    @endforeach

	  	@endif

		</div>
	</div>



		{{-- Previous and next buttons to match width of results --}}
		<div class="container">
		  <div class="col-md-12 pagi-padding">
				{{-- Pagination for more than twenty photos in gallery --}}
   			@include('pages.default-pagination', ['paginator' => $events])
   		</div>
   	</div>

	</div>

</div>
@stop
