<div id = "comments">

  <div class = "event-detail-acitivity-post">

    {{-- Begin Comments Section --}}
    <div class = "events-detail-title">{{ isset($events['meta']['comments']['label']) ? $events['meta']['comments']['label'] : 'Comments' }}</div>

    <div class = "profile-activity-event-detail-desc">
      {{-- Loop through Comments --}}

      @foreach ($events['comments'] as $comments)
        <div class = "event-detail-guest-comments">
          <div class = "event-detail-guest-info">
            <div class ="profile-activity-details">
              <div class ="profile-default-image-thumb">
                <img src = "{{ $comments['user']['avatar'] }}" border="0" />
              </div>
            </div>
            <div class = "profile-activity-guest-comment-sect">
              <div class = "profile-activity-username">
                {{ $comments['user']['firstName'] }} {{ $comments['user']['lastName'] }}
              </div>
              <div class = "profile-activity-info-txt">
                {{ $comments['comment'] }}
              </div>
              <div class = "profile-activity-post-date">
                {{ $comments['createdAt'] }} {{-- TODO: needs top be in seconds --}}
              </div>
            </div>
          </div>
        </div>
      @endforeach

    </div>

    @if (Auth::check())

      {{-- Input Area for comments --}}
      <div class = "event-detail-user-comment">

        <div class ="profile-activity-details">
          <div class ="profile-default-image-thumb">
            <img src = "{{ Auth::user()->avatar }}" border="0" />
          </div>
        </div>

        {!! ViewHelper::formOpen(['action' => ['EventsController@doComments', 'slug' => $events['slug']]]) !!}

        {!!  Form::hidden('eventId', ($events['id'])) !!}

        <div class = "profile-activity-user-wc">

          <div class = "comments-form-sect">
            <input type="text" name="comment" class="form-control" placeholder="Write a comment" aria-describedby="basic-addon1" required>
          </div>

          <div class = "comments-form-btn">
            <button class="btn btn-suaray btn-suaray-primary" name="submit comment">Submit</button>
          </div>

        </div>

      </div>
      {!! Form::close() !!}

    @else

      <div class = "profile-activity-user-wc">
        <div class = "comments-form-sect">
          Log In To Leave Comments
        </div>
      </div>

    @endif

  </div>

</div>
