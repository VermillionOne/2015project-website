/*jslint unparam: true*/
/*global app*/
app.controller('EventsRatingsController', ['$scope', function ($scope, $log, $http, jQuery) {

  // Invoke strict mode
  "use strict";

  /* $scope.rateEvents = true;
  $scope.toggleReviews = function () {
    $scope.rateEvents = !$scope.rateEvents;
  };

  $(function (active) {
    $('.rating-select .btn').on('mouseover', function () {
      $(this).removeClass('btn-gallery-l').addClass('btn-warning');
      $(this).prevAll().removeClass('btn-gallery-l').addClass('btn-warning');
      $(this).nextAll().removeClass('btn-warning').addClass('btn-gallery-l');
    });

    $('.rating-select').on('mouseleave', function () {
      active = $(this).parent().find('.selected');
      if (active.length) {
        active.removeClass('btn-gallery-l').addClass('btn-warning');
        active.prevAll().removeClass('btn-gallery-l').addClass('btn-warning');
        active.nextAll().removeClass('btn-warning').addClass('btn-gallery-l');
      } else {
        $(this).find('.btn').removeClass('btn-warning').addClass('btn-gallery-l');
      }
    });

    $('.rating-select .btn').click(function () {
      if ($(this).hasClass('selected')) {
        $('.rating-select .selected').removeClass('selected');
      } else {
        $('.rating-select .selected').removeClass('selected');
        $(this).addClass('selected');
      }
    });
  });
  */

}]);
