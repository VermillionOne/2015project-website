/*jslint unparam: true*/
/*global app, schedules*/
app.controller('SchedulesController', function ($scope, $log, $http, $location, ScheduleProvider) {

  // Invoke strict mode
  "use strict";

  var i;

  $scope.error = false;
  // Grabs all schedules for current event
  $scope.schedules = schedules;

  // Deletes schedule
  $scope.deleteSchedule = function (eventId, scheduleId, times) {

    $http({
      method: 'post',
      url: $location.url() + '/delete/schedule/' + scheduleId,
      data: {
        eventId: eventId,
        scheduleId: scheduleId,
      }
    })
      .success(function (response, status, headers, config) {
        var index;

        if (response.success === true) {

          for (i = 0; i < $scope.schedules.length; i += 1) {

            if ($scope.schedules[i].id === scheduleId) {

              index = $scope.schedules.indexOf(times);
            }
          }

          // Removes schedule without reload of page
          $scope.schedules.splice(index, 1);

          $scope.successMessage = "The selected schedule has been successfully deleted";

        } else {

          $scope.errorMessage = response.error;

        }
      });
  };

  // Deletes single date in recurring schedule
  $scope.deleteSingleDate = function (timeId, eventId, scheduleId, time) {

    $http({
      method: 'post',
      url: $location.url() + '/delete/date/' + timeId,
      data: {
        eventId: eventId,
        timeId: timeId,
      }
    })
    .success(function (response, status, headers, config) {
      var index;

      if (response.success === true) {

        for (i = 0; i < $scope.schedules.length; i += 1) {

          if ($scope.schedules[i].id === scheduleId) {

            // Gets schedule that is matched with chosen date
            $scope.newSchedule = $scope.schedules[i];

             for (i = 0; i < $scope.newSchedule.times.length; i += 1) {

                index = $scope.newSchedule.times.indexOf(time);
                console.log(index);

             }
          }
        }

        // Removes individual date without reload of page
        $scope.newSchedule.times.splice(index, 1);

        // Display success message
        $scope.successMessage = "The selected date has been successfully deleted";

      } else {

        // Will display error message
        $scope.errorMessage = response.error;

      }

    });

  };

})
.filter('parseTimeDataFilter', function () {
  // filter for displaying the start time in plain, readable Text

  // Invoke strict mode
  "use strict";

  return function (startTime) {

    startTime = startTime.replace(/-/g, '/');
    startTime = Date.parse(startTime);

    return startTime;
  };
});
