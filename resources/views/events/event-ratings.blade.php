@extends('layouts.master')


@section('content')
<div class = "tickets-sect" data-ng-controller="EventsRatingsController">
  <div class = "container ratings-height">




      <div class = "row">
        <div class = "col-md-12">
          <div class = "tickets-sect-hd">
            <div class = "tickets-title-txt">
              <h4 class="rate-more">Rate More</h4>
            </div>
            <div class = "back-event-btn">
              <a href="{{ url(route('events.show', ['slug' => $slug])) }}">
                <button type="button" class="btn btn-default btn-gallery-l">Back To Event</button>
              </a>
            </div>
          </div>
        </div>
      </div>

            <!-- Loops through events attended -->
            @foreach ($eventsAttending as $eventsAttendingYes)
                <div class="row" class="more-padding">
                  <div class="col-md-12">
                    <div class = "tickets-sect-hd">
                      <ul class="list-unstyled friends-list-populate">
                        <li>
                          <div class="col-md-4" > <!-- Start of rating -->
                            <div class="event-review-image">
                              <div class="event-padding">
                                @if (isset($eventsAttendingYes['photos'][0]))
                                  <img src = "{{ $eventsAttendingYes['photos'][0]['url']['218x190'] }}" border="0" />
                                @endif
                              </div>
                            </div>
                          </div>
                            <div class="col-md-8">
                              <!-- Event title -->
                              <div class="review-text">{{ $eventsAttendingYes['title'] }} </div>
                                <div class="friend-accept-deny-btn">
                                  By: <a>{{$eventsAttendingYes['user']['firstName']}} {{$eventsAttendingYes['user']['lastName']}}</a>
                                </div>

                                  <!-- Checking user auth to allow for review post -->
                                  @if (Auth::check())

                                    <!-- Pulling in specific event data to direct review post -->
                                    {!! ViewHelper::formOpen(['action' => ['EventRatingsController@doReviews' ]]) !!}

                                    <!-- Getting event id as well as user id to post review -->
                                    {!! Form::hidden('userId', Auth::user()->id ) !!}
                                   {{-- {!! Form::hidden('rating', ($rating[]) ) !!}  --}}

                                    <div class="review-padding">
                                    {!! Form::textarea('review', null, ['class' => 'form-control', 'size' => '15x3', 'placeholder' => 'Leave Your Review!']) !!}
                                    </div>

                                    <!-- Star rating section -->
                                    <div class="row">
                                      <div class="col-md-6">
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

                                      <div class="rating-page-submit-btn">
                                        <div class="review-post">
                                          {!! Form::submit('Submit Review', ['class' => 'btn btn-suaray btn-suaray-primary']) !!}
                                        </div>
                                      </div>
                                    {!! Form::close() !!}
                                    @endif
                                  </div>
                            </div>  <!-- End of review -->
                        </li>
                      </ul>
                    </div>
                  </div> <!-- End of review row -->
                </div>  <!-- End of review page -->
              @endforeach
    </div>
  </div>
@stop
