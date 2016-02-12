/*jslint unparam: true*/
/*for:true*/
/*global app,alert, suarayDataCode*/
app.controller('TicketsController', function ($scope, $timeout, $http, $location) {

  // Invoke strict mode
  "use strict";

  var quantityCounter;

  // Counter for used ticket incrementer initial 0
  quantityCounter = 0;

  // Do not show loader when admin enters code
  $scope.loading = false;

  $scope.ticketAdmit = {};

  $(function () {

    // When user enters information in input
    $('#codeInput').keyup(function () {

      // Resets success box
      $scope.success = false;

      // Resets error code if any
      $scope.codeLookup = false;

      // Resets returned ticket data
      $scope.ticketData = null;

    });

  });

  // Increase number of tickets being used
  // Parameter is the index value of the ticketData.types in ng-repeat
  $scope.increaseAdmit = function (index) {
    // Prevents counter from being more than available =>
    // If admitted ticket number is less than the length if the ticketData.types =>
    if (angular.element('[data-role="ticket-admit-' + index + '"]')[0].value < $scope.ticketData.types[index].qty.available) {
    // Increase value by 1
      angular.element('[data-role="ticket-admit-' + index + '"]')[0].value++;
      $timeout(function () {
        $scope.usedTicket = angular.element('[data-role="ticket-admit-' + index + '"]')[0].value;
      });
    }
  };

  // Decrease number of tickets being used
  $scope.decreaseAdmit = function (index) {
    // Prevents counter from being negative
    // If admitted ticket number is greater than 0 =>
    if (angular.element('[data-role="ticket-admit-' + index + '"]')[0].value > 0) {
      // Decrease value by 1
      angular.element('[data-role="ticket-admit-' + index + '"]')[0].value--;
      $timeout(function () {
        $scope.usedTicket = angular.element('[data-role="ticket-admit-' + index + '"]')[0].value;
      });
    }
  };

  // Call to grab input ticket code for check-in
  $scope.ajaxCode = function (code) {

    // Show spinning icon
    $scope.loading = true;

    $http({
      method: 'get',
      url: $location.url() + '/confirm',
      params: {
        code: code
      }
    })
      .success(function (response, status, headers, config) {

        // When success if true, ticket data sent to view
        if (response.success === true) {

          // Hide spinning icon
          $scope.loading = false;

          $scope.ticketData = response.data.order;

        } else {

          // When success false, will trigger error message to show
          $scope.codeLookup = true;

          // Hide spinning icon
          $scope.loading = false;
        }

      });

  };

  // Call to update ticket code from input with amount being used
  $scope.ajaxCodeUse = function (code, usedTicket, ticketInventoryId) {

    $http({
      method: 'post',
      url: $location.url() + '/use',
      params: {
        code: code,
        used: usedTicket,
        ticketInventoryId: ticketInventoryId
      }
    })
      .success(function (response, status, headers, config) {

        $scope.ticketData = response.data.order;

        if (response.success === true) {
          $scope.success = true;

          $http({
            method: 'get',
            url: $location.url() + '/update',
          })
            .success(function (response, status, headers, config) {

              // When success if true, ticket data sent to view
              $scope.userTickets = response.data.orders;
              $scope.ticketInventoryId = ticketInventoryId;

            });

        }

      });

  };

  // Initiate after page ready
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
      $scope.tab = 'checkin';
      angular.element('[data-role="tab-checkin"]').trigger('click');

    }

    $http({
      method: 'get',
      url: $location.url() + '/update',
    })
      .success(function (response, status, headers, config) {

        // When success if true, ticket data sent to view
        $scope.userTickets = response.data.orders;

      });

  });

  $scope.submit = function () {
    $scope.eventTimeId = $('[data-role="event-date"]').val();
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
