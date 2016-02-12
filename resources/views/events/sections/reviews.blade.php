{{-- Wrapper for reviews and comments --}}
<div class = "event-detail-col-reviews" data-ng-controller="EventsRatingsController">

  {{-- Title for reviews --}}
  <div class = "events-detail-title">{{ isset($events['meta']['reviews']['label']) ? $events['meta']['reviews']['label'] : 'Reviews' }}</div>

  <div class = "profile-activity-event-detail-desc">
    {{-- Loop through reviews --}}
    @foreach ($events['reviews'] as $event)

      {{-- Show a single review --}}
      <div class = "event-detail-guest-reviews">
        <div class = "event-detail-guest-review">
          <div class ="profile-activity-details">

            {{-- Reviewing users image --}}
            <div class ="profile-default-image-thumb">
              <img src = "{{ $event['user']['avatar'] }}" border="0" />
            </div>

          </div>
          <div class = "profile-activity-guest-comment-sect">

            {{-- The users name who reviewed --}}
            <div class = "profile-activity-username">
              {{ $event['user']['firstName'] }} {{ $event['user']['lastName'] }}
            </div>

            {{-- Review content of the user --}}
            <div class = "profile-activity-info-txt">
              @if (isset($event['review']))
                {{ $event['review'] }}
              @endif
            </div>
          </div>
        </div>

                    {{-- Event rating --}}
          <div class = "event-detail-ratings">
            @if (isset($event['rating']))
              <div class='rating_bar'>
                <div  class='rating-stars' style='width: {{ $event['rating'] }}0%;'></div>
              </div>
            @endif
          </div>

      </div>

    @endforeach

  </div>

  {{-- Input Area for comments --}}
  @if (Auth::check())

    <div class = "event-detail-user-review">

      <div id = "reviews"></div>
      {!! ViewHelper::formOpen(['action' => ['EventRatingsController@doReviews' ]]) !!}
        {!!  Form::hidden('eventId', ($events['id'])) !!}

        @if (\Auth::check())
          {!!  Form::hidden('userId', Auth::user()->id ) !!}
        @endif

        <div class="review-main-padding">
          {!! Form::textarea('review', null, ['class' => 'form-control', 'size' => '15x3',  'placeholder' => 'Leave Your Review!', 'required' => 'required']) !!}
        </div>

      <!-- Rating stars -->
      <div class="row">

        <div class="col-md-9">
          <div class = "rating-star-sect">
            <span class="rating">
              <input type="radio" class="rating-input" id="rating-input-1-5" value="10" name="rating"/>
              <label for="rating-input-1-5" class="rating-star"></label>

              <input type="radio" class="rating-input" id="rating-input-1-4" value="8" name="rating"/>
              <label for="rating-input-1-4" class="rating-star"></label>

              <input type="radio" class="rating-input" id="rating-input-1-3" value="6" name="rating"/>
              <label for="rating-input-1-3" class="rating-star"></label>

              <input type="radio" class="rating-input" id="rating-input-1-2" value="4" name="rating"/>
              <label for="rating-input-1-2" class="rating-star"></label>

              <input type="radio" class="rating-input" id="rating-input-1-1" value="2" name="rating"/>
              <label for="rating-input-1-1" class="rating-star"></label>
            </span>
          </div>
        </div>

        <div class="col-md-3">
          <div class="review-post">
            {!! Form::submit('Submit Review', ['class' => 'btn btn-suaray btn-suaray-primary', 'name' => 'submit review']) !!}
          </div>
        </div>

        {!! Form::close() !!}
      </div>
    </div>
  @else
    <div class = "profile-activity-user-wc">
      <div class = "comments-form-sect">
        Log In To Leave a Review
      </div>
    </div>
  @endif

</div>
