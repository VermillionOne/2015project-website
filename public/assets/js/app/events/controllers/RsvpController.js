/*jslint unparam: true*/
/*global app,alert,rsvpTimeData*/
app.controller('RsvpController', function ($scope, $http, $location, $timeout, RsvpFeedbackFactory) {

  // Invoke strict mode
  "use strict";

  var data, i;

  // Function receives userId, eventTimeId, and rsvpOption from RSVP section buttons to provide appropriate feedback to the user
  $scope.rsvpResponse = function (userId, eventTimeId, rsvpOption) {
  // If there is a question mark '?' after the URL, it will return a 405 error


    data = {
      userId: userId,
      eventTimeId: eventTimeId
    };

    $scope.data = data;

    // For loop through each index of rsvpTimeData
    for (i = 0; i < rsvpTimeData.length; i += 1) {

      // If the eventTimeId for the current option and the index ID match
      if (parseInt(eventTimeId, 10) === parseInt(rsvpTimeData[i].id, 10) && rsvpTimeData[i].auth) {
        rsvpTimeData[i].auth.isAttending = rsvpOption;
      }
    }

    // If responder answered 'yes', set 'rsvpIs' to 'isYes'
    if (rsvpOption === 'yes') {
      data.isYes = true;
    } else if (rsvpOption === 'maybe') {

      data.isMaybe = true;
    } else if (rsvpOption === 'no') {

      data.isNo = true;
    }

    // Create request object
    $http({
      method: 'post',
      url: $location.url() + '/rsvp',
      data: data
    })
      // If post for 'yes' is successful, responds with confirmation of respondent attending
      .success(function (response, status, headers, config) {

        if (data.isYes) {

          $scope.rsvpSent = true;
        } else if (data.isMaybe) {

          $scope.rsvpSent = true;
        } else if (data.isNo) {

          $scope.rsvpSent = true;
        }
        // $scope.rsvpMessage = 'You chose to not attend. Hope to see you next time!!';

        // // Setting $scope for angular elements to render
        // // Rendering to occur:
        // $scope.rsvpYes   = false;
        // $scope.rsvpMaybe = false;
        // $scope.rsvpNo    = true;
      })
      .error(function (response, status, headers, config) {
      });

  };

  $scope.reopenRsvp = function () {

    if (data && data.isYes === true) {
      delete data.isYes;
    } else if (data && data.isMaybe === true) {
      delete data.isMaybe;
    } else if (data && data.isNo === true) {
      delete data.isNo;
    }
    $scope.rsvpSent = false;
  };

  // Check for RSVP status on default RSVP date select option
  $scope.getRsvp = function () {

    var isAttending;
    isAttending = RsvpFeedbackFactory.getRsvp();

    $scope.reopenRsvp();

    $timeout(function () {
      if (isAttending === 'Attending') {

        angular.element('[data-role="rsvp-yes"]').click();

      } else if (isAttending === 'Maybe Attending') {

        angular.element('[data-role="rsvp-maybe"]').click();

      } else if (isAttending === 'Not Attending') {

        angular.element('[data-role="rsvp-no"]').click();
      }
    }, 0, false);

  };

  angular.element(document).ready(function () {
    $scope.getRsvp();
  });

});
