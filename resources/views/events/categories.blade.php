@extends('layouts.master')

@section('javascript-includes')
@stop

@section('content')

<div class="category-body" ng-controller="CategoriesController">

  <div class="container">

    <div class="col-md-12">
      <div class="search-results-title">
        <h2 class="results-title-txt">Categories</h2>
      </div>
    </div>

    <div class="row">

      @if (isset($tags['success']) && $tags['success'] === false)

        <h1 class="text-center text-muted pb50">{{ $tags['error'] }}</h1>

      @else
        {{-- Loop through all events --}}
        @for ($n = 0; $n < count($tags); $n++)

        @if (isset($tags[$n]))


          <div class="col-xs-6 col-sm-4 col-md-3 xs-cat-list">
            <div class="cat-bg-holder" style="background-image: url(assets/img/category/{{  str_slug($tags[$n]['tag']) }}-bg.jpg)">
              <a href="events/search?q={{ $tags[$n]['tag'] }}">
                <div class="tags-holder">
                  <div class="cat-icon">
                    <i class="{{ $tags[$n]['icon'] }}"></i>
                  </div>
                </div>
              </a>
            </div>
            <div class="search-category-view-event">
              <a href="events/search?q={{ str_slug($tags[$n]['tag']) }}" name="{{ $tags[$n]['tag'] }}">
                {{ $tags[$n]['tag'] }}
              </a>
            </div>
          </div>
          @endif

        @endfor

      @endif

    </div>

  </div>
</div>
@stop
