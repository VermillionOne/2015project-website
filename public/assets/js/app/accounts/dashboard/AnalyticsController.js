/*jslint unparam: true*/
/*global app*/
app.controller('AnalyticsController', function ($scope, $log, $http, $location) {

    // Invoke strict mode
    "use strict";

    var percentageDegrees, createGradient, percentage, degrees, $this, i;

    // p is set to get the value of data, and return the percentage
    // d is set to take the percentage and turn into degrees
    percentageDegrees = function (p) {
      p = (p >= 100 ? 100 : p);
      var d = 3.6 * p;
      return d;
    };

    // Degree is then taken and set radial to specified degree
    createGradient = function (elem, d) {
      if (d <= 180) {
        d = 90 + d;
        elem.css('background', 'linear-gradient(90deg, #CCCCCC 50%, transparent 50%), linear-gradient(' + d + 'deg, #3496cf 50%, #CCCCCC 50%)');
      } else {
        d = d - 90;
        elem.css('background', 'linear-gradient(-90deg, #3496cf 50%, transparent 50%), linear-gradient(' + d + 'deg, #CCCCCC 50%, #3496cf 50%)');
      }
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
        $scope.tab = 'tickets';
        angular.element('[data-role="tab-tickets"]').trigger('click');

      }

    });

    // Execute the above
    $('.pie').each(function () {

      $this = $(this);
      percentage = $this.data('percentage');
      degrees = percentageDegrees(percentage);
      createGradient($this, degrees);

    });

});
