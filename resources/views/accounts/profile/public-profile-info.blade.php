      <div class = "profile-info">

        {{-- Users name name and profile image location --}}
        <div class="profile-title-name ">{{ $user['firstName'] }} {{ $user['lastName'] }}</div>

        {{-- Begin a form to upload a profile image --}}
        <div class="">

          {{-- Users profile image --}}
          <div class="profile-round">

            @if (empty($user['avatar']))
              <img src="{{ ViewHelper::asset('assets/img/missing-profile-pic.jpg') }}">
            @else
              <img src="{{ $user['avatar'] }}">
            @endif

          </div>

        </div>

        {{-- If public user is auth user friend, show friends --}}
        @if ($isFriend === true || ($isFriend && $isRequested === true))

        <a href="" class="btn btn-suaray btn-suaray-primary btn-block disabled"><span class="glyphicon glyphicon-user"></span>&nbsp;Friends</a>

        {{-- If request has previously been sent, show request sent --}}
        @elseif ($isRequested === true && $isFriend === false)

        <a href="" class="btn btn-suaray btn-suaray-primary btn-block disabled"><span class="glyphicon glyphicon-user"></span>&nbsp;Request Sent</a>

        {{-- If no request or friend relationship, allow for request --}}
        @else

        <a href="{{ route('friends.store', ['id' => $user['id']])}}" class="btn btn-suaray btn-suaray-primary btn-block" type="submit"><span class="glyphicon glyphicon-user"></span>&nbsp;Friend Request</a>

        @endif
      </div>
