<div id="wallcomments"></div>

<div class = "profile-ad-and-activity">

<div class = "profile-activity-wall">

  <div class = "activity-wall-user">

    <div class="profile-event-name">Activity Wall</div>

    @if ($isAccessedPublicly && $isFriend === false)

    @elseif(\Auth::user() || $isFriend === true)
      <div class = "profile-activity-wall-post">

        <div class="profile-wall-status">

          <div class="row">

            {!! ViewHelper::formOpen(['action' => ['AccountsController@doWallComments']]) !!}

            {{-- Grab friend id and id of user whom the profile belongs to --}}
            @if($isFriend === true)

              {!!  Form::hidden('friendId', ($friendPost['id'])) !!}
              {!!  Form::hidden('userId', ($user['id'])) !!}

            @else

              {{-- Sends auth users id --}}
              {!!  Form::hidden('userId', ($user['id'])) !!}

            @endif

            {!!  Form::hidden('updatesTime', ($user['createdAt'])) !!}

            <div class = "col-xs-9 col-md-10">
                <input type="text" name="message" class="form-control" placeholder="Comments" aria-describedby="basic-addon1">
            </div>

            <div class = "col-xs-2 col-md-2">
              <button class="btn btn-suaray btn-suaray-primary">Post</button>

            </div>

            {!! Form::close() !!}

            <div class = "col-xs-12">
              @if (isset($failedwall))
                {{{ $failedwall }}}
              @endif
            </div>

          </div>

        </div>

      </div>
    @endif

    </div>

    {{-- If we have user updates --}}
    @if (isset($user['updates']))

      @foreach ($user['updates'] as $update)

        {{-- If post was made by a friend, grab friend information --}}
        @if(isset($friendPost['id']) && $friendPost['id'] === $update['friendId'])

          <div class = "profile-acitivity-post">

            <div class ="profile-activity-details">

              <div class ="profile-default-image-thumb">
                <img src = "{{ $friendPost['avatar']}}" border="0" />
              </div>

            </div>

            <div class = "profile-activity-event-desc">

              <div class = "profile-activity-username">
                {{ $friendPost['firstName'] }} {{ $friendPost['lastName'] }}

                @if(!$isAccessedPublicly)
                  <a href="{{ route('wallComments.delete', ['id' => $update['id']]) }}" type="button" class="comment-delete"><i class="fa fa-times-circle"></i></a>
                @endif

              </div>

              <div class = "profile-activity-event-title">
                {{ $update['message'] }}
              </div>

              <div class = "profile-activity-post-date">
                {{ $update['createdAt'] }}
              </div>
              {{-- <img src = "{{ ViewHelper::asset('assets/img/sample-pic-1.jpg') }}"> --}}

            </div>

          </div>

        @else

          {{-- If post was made by owner of profile, grab auth user information --}}
          <div class = "profile-acitivity-post">

            <div class ="profile-activity-details">

              <div class ="profile-default-image-thumb">
                <img src = "{{ $user['avatar']}}" border="0" />
              </div>

            </div>

            <div class = "profile-activity-user-wc">

              <div class = "profile-activity-username">

                {{ $user['firstName'] }} {{ $user['lastName'] }}

              @if(!$isAccessedPublicly)
                <a href="{{ route('wallComments.delete', ['id' => $update['id']]) }}" type="button" class="comment-delete"><i class="fa fa-times-circle"></i></a>
              @endif

              </div>

              <div class = "profile-activity-event-title">
                {{ $update['message'] }}
              </div>

              <div class = "profile-activity-post-date">
                {{ $update['createdAt'] }}
              </div>
              {{-- <img src = "{{ ViewHelper::asset('assets/img/sample-pic-1.jpg') }}"> --}}

            </div>

          </div>

        @endif

      @endforeach

    @endif

  </div>

</div>
