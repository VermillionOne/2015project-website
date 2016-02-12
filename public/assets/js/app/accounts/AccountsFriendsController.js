/*jslint unparam: true*/
/*global app, suarayDataFriends, suarayEventInvites, suarayFriendRequests, suarayDataEvents*/
app.controller('AccountsFriendsController', function ($scope, $location, $http) {

  // Invoke strict mode
  "use strict";

  // Set users to empty array
  $scope.suarayDataUsers = [];

  // Users friends
  $scope.suarayFriends = suarayDataFriends;

  // Pulls in event requests that have been sent to user
  $scope.suarayInvites = suarayEventInvites;

  // Pulls in friend requests that have been sent to user
  $scope.suarayRequests = suarayFriendRequests;

  // User created events to pull in for invite
  $scope.suarayEvents = suarayDataEvents;

  // Default message if user search result returns nothing
  $scope.noMatchMessage = false;

  $scope.searchMatches = false;

  // Delays a method call for given milliseconds
  var delay = (function (callback, ms) {

    var timer = 0;

    return function (callback, ms) {

      clearTimeout(timer);

      timer = setTimeout(callback, ms);

    };

  })();

   // Make call with search query, callback if no results
  function searchForFriends(query, callback) {

    $http({
      method: 'get',
      url: $location.url() + '/users',
      params: {
        query: query
      }
    })
     .success(function (response, status, headers, config) {

      if (response.success === true) {

        // Sets error message to false if previously set to true
        $scope.noMatchMessage = false;

        $scope.searchMatches = true;

        // Shows user information if returned successfully
        $scope.suarayDataUsers = response.data.users;

      } else {

        // If no users found in search, show default no matches found message
        $scope.noMatchMessage = true;

        $scope.searchMatches = false;

      }

    });
  }

  // When user inputs information, calls search function with timeout
  $scope.doSearch = function (queryText) {

    var first, last, query, waitTime;

    query = '';

    // amount of time to delay call
    waitTime = 500;

    // if user enters space as to say last name, code separates by space and creates first and last name variables
    first = queryText.split(' ')[0];
    last = queryText.split(' ')[1];

    if (first && !last) {

      // set query to filter for first name
      query = 'filter[or][][first_name]=' + first + '*' + '&filter[or][][last_name]=' + first + '*' + '&sort[asc][]=id&sort[asc][]=email&sort[asc][]=first_name';

    } else {

      // reset query to filter for both first and last name
      query = 'filter[or][][first_name]=' + first + '*' + '&filter[or][][last_name]=' + last + '*' + '&sort[asc][]=id&sort[asc][]=email&sort[asc][]=first_name';

    }

    delay(function () {

      searchForFriends(query, function () {

        // reset query to filter for username
        query = 'filter[or][][username]=' + first + '*' + '&sort[asc][]=id&sort[asc][]=email&sort[asc][]=first_name';

        // search again with username
        searchForFriends(query, function () {

          // if still no results attemp email
          query = 'filter[or][][email]=' + first + '*' + '&sort[asc][]=id&sort[asc][]=email&sort[asc][]=first_name';

          searchForFriends(query);

        });

      });

    }, waitTime);

  };

  // Send invites to event
  $scope.updateRequest = function (eventId, inviteId, index) {

    // Removes request when clicked
    $scope.suarayInvites.splice(index, 1);

    $http({
      method: 'post',
      url: $location.url() + '/invites/update',
      data: {
        eventId: eventId,
        inviteId: inviteId,
      }
    })
      .success(function (response, status, headers, config) {

      });
  };

  // Send invites to event
  $scope.doSendInvite = function (authId, eventId, id) {

    $http({
      method: 'post',
      url: $location.url() + '/create/my-event-invites',
      data: {
        requesterId: authId,
        eventId: eventId,
        userId: id,
      }
    })
      .success(function (response, status, headers, config) {

      });
  };

  // Option to deny first friend request and remove friend element
  $scope.notification1 = true;
  $scope.toggleFirst = function () {
    $scope.notification1 = !$scope.notification1;
  };

  // Accept friend, adds to friends list
  $scope.toggleFriends = function (index) {
    $scope.friends.push($scope.requests[index]);
    $scope.requests.splice(index, 1);
  };

  // Deny friend clicked, will remove from list
  $scope.removeRequest = function (index) {
    $scope.suarayRequests.splice(index, 1);
  };

  //Hides friend choices on invite page when submit clicked, returns friend choices when back button clicked
  $scope.invitefriend = true;
  $scope.toggleFirst = function () {
    $scope.inviteFriend = !$scope.inviteFriend;
  };

  angular.element(document).ready(function () {

    // Variable declarations
    var tab;

    // Get event query param from url
    tab = $location.search().tab;
    if (tab !== undefined) {

      // Set selected tab
      $scope.tab = tab;
      angular.element('[data-role="tab-' + tab + '"]').trigger('click');

    } else {

      // set default tab
      $scope.tab = 'notifications';
      angular.element('[data-role="tab-notifications"]').trigger('click');

    }

  });
});

