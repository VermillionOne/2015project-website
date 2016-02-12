@extends('layouts.master')

@section('javascript-includes')
  <script src="{{ ViewHelper::asset('assets/dist/lib-events-show.js') }}"></script>
  <script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
  {{-- Call for array that populates friends information --}}
  <script type="text/javascript">
    var rsvpTimeData = JSON.parse('{!! str_replace("'", "\'", json_encode($events['times'])) !!}');
    var suarayDataEventInvite = JSON.parse('{!! str_replace("'", "\'", json_encode($friends)) !!}');
  </script>
  <script type="text/javascript">
  var vglnk = { key: 'e45feae25cb6a60bae82a5ef604ca2ce' };
  (function(d, t) {
    var s = d.createElement(t); s.type = 'text/javascript'; s.async = true;
    s.src = '//cdn.viglink.com/api/vglnk.js';
    var r = d.getElementsByTagName(t)[0]; r.parentNode.insertBefore(s, r);
  }(document, 'script'));
  </script>
@stop

@section('content')

{{-- User feedback --}}
@include('pages.feedback-tab')

{{-- Wrapper for all of the event details page --}}
<div class = "view-event-show">

<div data-ng-controller="EventsShowController" ng-cloak class="ng-cloak">

  {{-- Session Message --}}
  @include('includes.sessionStatus')

  <div class = "event-detail-container">

    {{-- Wrapper for all main section event details --}}
    <div class = "container">

      <div class="row">

      	{{-- Event Slider area --}}
      	<div class = "col-md-9 col-xs-12">

      		{{-- Event admin only --}}
    			@if ($isAdmin)
    				@include('events.sections.event-admin')
    			@endif

	        <div class="section-hero-card">

            {{-- Bootstrap Carousel --}}

            {{-- Includes Countdown and Title --}}
            @include('events.sections.carousel')

        	</div>

          {{-- Event Tickets --}}
          @if (isset($tickets) && $tickets)
    		    @include('events.sections.tickets')
    			@endif

          {{-- If event has rsvp's, list will show --}}
          @if (!empty($attendeeList) && $attendeeList && isset($events['meta']['rsvp']['enabled']) && $events['meta']['rsvp']['enabled'] === true)

            @include('events.sections.attendees')

          @endif

	        <!-- {{$events['title']}} event description-->
    			<div class="section-card">

            <div class = "events-detail-title">Description</div>

            {{-- Event Description --}}

            <div class = "events-desc">

              {{-- If our description html is empty or not set --}}
              @if (empty($events['descriptionHtml']))

                {{-- Display the description text version --}}
                {{ $events['description'] }}

              @else

                {{-- Display the description html version unescaped --}}
                {!! $events['descriptionHtml'] !!}

              @endif

              {{-- Provides link to events external website if available --}}
              @if (!empty($events['linkback']))
                {!! Html::link($events['linkback'], "View this event's website", ['target' => '_blank']) !!}
              @endif

            </div>

    			</div>

          {{-- Section for vimeo and youtube videos --}}
          @include('events.sections.web-video')

  		  	{{-- Event Maps --}}
  		  	@if (isset($events['meta']['map']['enabled']) && $events['meta']['map']['enabled'] === true)
    				<div class="section-card">
    				  @include('events.sections.maps')
    				</div>
  		  	@endif

          {{-- Event Tickets --}}
          @if (isset($reservations) && $reservations)
            @include('events.sections.reservations')
          @endif

	        {{-- Event Reviews --}}
	        @if (isset($events['meta']['reviews']['enabled']) && $events['meta']['reviews']['enabled'] === true)
    				<div class="section-card">
              @include('events.sections.reviews')
			      </div>
	        @endif

         	{{-- Event Comments --}}
	        @if (isset($events['meta']['comments']['enabled']) && $events['meta']['comments']['enabled'] === true)
				    <div class="section-card">
	            @include('events.sections.comments')
		        </div>
          @endif

        </div>

        {{-- Start left sidebar --}}
        <aside class="col-md-3 col-xs-12">
          <div data-role="affixed-sidebar">

          	{{-- Event Times Sections --}}
    			  <div class="section-card">
    	        @include('events.sections.eventtime')
    			  </div>

          	{{-- RSVP Sections --}}
          	@if (isset($events['meta']['rsvp']['enabled']) && $events['meta']['rsvp']['enabled'] === true)
    	        <div class="section-card">
    	          @include('events.sections.rsvp')
            	</div>
          	@endif

            {{-- Invite Section --}}
            <div class="section-card">
              @include('events.sections.invite')
            </div>

          	{{-- Social Icons Sections --}}
  	        <div class="section-card">
          		@include('events.sections.social')
          	</div>

          	{{-- QR Code Section --}}
          	@if (isset($events['meta']['qr']['enabled']) && $events['meta']['qr']['enabled'] === true)
  		        <div class="section-card">
            		@include('events.sections.qrcode')
          		</div>
          	@endif

          </div>
        </aside>

      </div>

      <div class="row">

        @if (isset($events['meta']['videos']['enabled']) && $events['meta']['videos']['enabled'] === true)
	        <div class="col-md-9 col-xs-12">
		        <div class="section-card">
		          {{-- Event 360 Video --}}
		          @include('events.sections.video')
		        </div>
	        </div>
        @endif

      	@if (isset($events['meta']['photos']['enabled']) && $events['meta']['photos']['enabled'] === true)
	        <div class="col-md-9 col-xs-12">
		        <div class="section-card">
		          {{-- Photo Gallery --}}
		          @include('events.sections.photos')
		        </div>
	        </div>
      	@endif

        @if (isset($relatedEvents) && !empty($relatedEvents))
        <div class="col-md-9 col-xs-12">
	        <div class="section-card">
          	{{-- Related Events section --}}
          	@include('events.sections.related')
	        </div>
      </div>
        @endif

        {{--
  	    @if (isset($events['meta']['weather']['enabled']) && $events['meta']['weather']['enabled'] === true)
          <div class="col-xs-12">
  	        <div class="section-card">
  				   @include('events.sections.weather')
  	        </div>
          </div>
  	    @endif
        --}}

			</div>

		</div>

  </div>

</div>
</div>
@stop
