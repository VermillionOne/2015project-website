@extends('layouts.master')

@section('javascript-includes')
  <script src="{{ ViewHelper::asset('assets/dist/lib-events-show.js') }}"></script>
  <script>
    $(function () {
      $("a.grouped_elements").fancybox();
    });
  </script>
@stop

@section('content')

{{-- Wrapper for all of the event deatils page --}}
<div class = "create-event-container" ng-controller="EventsGalleryController">

  {{-- Session Message --}}
  @include('includes.sessionStatus')

  <div class="container view-event-show event-gallery-container">

    <div class = "row gallery-header-btns">

      <div class = "col-xs-3">
        <a href="{{ route('events.show', ['slug' => $events['slug']]) }}">
          <button type="button" class="btn btn-default btn-gallery-l">Back To Event</button>
        </a>
      </div>

      {{-- Event Title and Location Text--}}
      <div class = "col-xs-6">
        <div class = "event-gallery-photo-title hidden-mobile">{{ $events['title'] }}</div>
        <div class = "event-gallery-photo-address hidden-mobile">{{ $events['address1'] }}</div>
      </div>

      <div class = "col-xs-3">
        @if ($isAdmin)
        <a href="{{ route('events.update-images', ['slug' => $events['slug']]) }}">
          <button type="button" class="btn btn-default btn-gallery-r">Add Photos</button>
        </a>
        @endif
      </div>

    </div>

    <div class = "row">

      <div class = "row gallery-header-border">Featured Event Photo</div>

      <div class="col-md-12 image">
        {{--If there is a featured photo for event, will show featured --}}
        @if (isset($events['featuredPhoto']))
          <img src="{{ $events['featuredPhoto']['url']['660x320'] }}" alt="Event Photo" class="event-gallery-pic photo-gallery-main-image-position">
        @else
          <div class="image"><img src="{{ ViewHelper::asset('assets/img/transcoding/photo/generating-660x320.png') }}" alt="Event Photo" class="event-gallery-pic photo-gallery-main-image-position"></div>
        @endif

      </div>
    </div>

    {{-- Show all images from the event --}}
    @if(empty($photo))

      <h1 class="text-center text-muted pb50">No photos</h1>

    @else

      <div class = "row">

        <div class = "row gallery-header-border">{{$events['title']}} Photos</div>

        @foreach ($photo as $photos)

          <div class="col-xs-12 col-sm-4 col-md-4 event-gallery-photo">
            @if ($isAdmin)
              <div class="overlay">

                <a href="{{$photos['url']['original']}}" class="grouped_elements gallery-dates" rel="group1"></a>
                {{-- {{$photos['eventTimeId']}} --}}
                <div class="btn-gallery">
                  <a href="{{ route('events.galleryDelete', ['event' => $events['id'], 'id' => $photos['id']]) }}" type="button" class="btn btn-sm btn-gallery-delete">Delete</a>
                  {{-- <a href="{{ route('events.galleryDelete', ['event' => $events['id'], 'id' => $photos['id']]) }}" class="btn btn-sm btn-gallery-delete"  type="button">Delete</a> --}}
                  <span class="btn-gallery-delete">&middot;</span>

                  @if ($photos['isFeatured'] === true)
                    <span class="btn-sm btn-gallery-delete">Featured Photo</span>
                  @else
                    <a href="{{ route('events.galleryFeatured', ['event' => $events['id'], 'id' => $photos['id']]) }}" type="button" alt="{{$photos['createdAt']}}" class="btn btn-sm btn-gallery-delete">Make Featured</a>
                  @endif

                </div>
              </div>
            @endif

            <a class="grouped_elements" rel="group1" href="{{$photos['url']['original']}}">

              <img class='default-picture-error event-gallery-pic' src="{{$photos['url']['318x190']}}" alt="{{$photos['createdAt']}}">

            </a>

          </div>

        @endforeach

      </div>

    {{-- Previous and next buttons to match width of results --}}
    <div class="container">
      <div class="col-md-12 gallery-padding">
        {{-- Pagination for more than twenty photos in gallery --}}
        @include('pages.default-pagination', ['paginator' => $photo])
      </div>
    </div>

    @endif
  </div>

</div>
@stop
