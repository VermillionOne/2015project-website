/*jslint unparam: true, indent: 2*/
/*jslint plusplus: true */
/*global angular,console,document,app, google, L, handleNoGeolocation, alert, window*/
app.controller('SellTicketsController', function ($scope, $http, $timeout, $location, HelperProvider) {

  // Invoke strict mode
  "use strict";

  // determine image size dependant on screen width
  $scope.findViewportWidth = function () {
    if (angular.element(window).width() >= 661) {
      angular.element('[data-role="viewport-large"]').click();
    } else if (angular.element(window).width() >= 461 && angular.element(window).width() <= 660) {
      angular.element('[data-role="viewport-medium"]').click();
    } else if (angular.element(window).width() >= 320 && angular.element(window).width() <= 460) {
      angular.element('[data-role="viewport-small"]').click();
    } else if (angular.element(window).width() <= 320) {
      angular.element('[data-role="viewport-xSmall"]').click();
    }
  };

  angular.element(window).resize(function () {
    $scope.findViewportWidth();
  });

  angular.element(document).ready(function () {
    $scope.findViewportWidth();
  });
});
