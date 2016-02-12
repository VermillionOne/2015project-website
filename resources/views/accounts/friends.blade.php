@extends('layouts.master')

@section('javascript-includes')
{{-- Call for array that populates friends information --}}
  <script type="text/javascript">

    var suarayDataFriends = JSON.parse('{!! str_replace("'", "\'", json_encode($friend)) !!}');

    var suarayEventInvites = JSON.parse('{!! str_replace("'", "\'", json_encode($eventInvites)) !!}');

    var suarayFriendRequests = JSON.parse('{!! str_replace("'", "\'", json_encode($friendRequest)) !!}');

    var suarayDataEvents = JSON.parse('{!! str_replace("'", "\'", json_encode($event)) !!}');

  </script>
@stop

@section('content')

<div class="view-event-create" data-ng-controller="AccountsFriendsController">

  {{-- Create Events Form Navbar --}}
  @include('accounts.friends.step')

  <div class="create-event-container">

    {{-- Session Message --}}
    @include('includes.sessionStatus')

    <!-- Start of notification page, commenting out until further notice -->
    <div class="container" ng-show="tab === 'notifications'">
      @include('accounts.friends.notifications')
    </div>

    <!-- Start of friends page -->
    <div class="container" ng-show="tab === 'friends'">
      @include('accounts.friends.friends_list')
    </div>

    <!-- Start of invite page -->
    <div class="container" ng-show="tab === 'invite'">
      @include('accounts.friends.invite')
    </div>

  </div>

</div>

@stop
