/*jslint white: true, browser: true, devel: true*/
/*jslint unparam: true*/
/*global app,angular,$,window,suarayDataEventInvite*/
app.controller('EventsShowController', function ($scope, $http, $filter, $location) {

  // Invoke strict mode
  "use strict";

  $scope.suarayEventInvite = suarayDataEventInvite;

  // Send invites to event
  $scope.doSendInvite = function (authId, eventId, id) {

    $http({
      method: 'post',
      url: $location.url() + '/create/event-invites',
      data: {
        requesterId: authId,
        eventId: eventId,
        userId: id
      }
    })
    .success(function (response, status, headers, config) {

    });
  };

  // Affix Sidebar
  // determine image size dependant on screen width
  $scope.findViewportWidth = function () {
    if (angular.element(window).width() >= 992) {
      angular.element('[data-role="affixed-sidebar"]').affix({
        offset: {
          top: 100,
        }
      });
    } else {
      angular.element('aside').find('.affix').removeClass('affix');
    }
  };
  angular.element(window).resize(function () {
    $scope.findViewportWidth();
  });

  // Initiate after page ready
  angular.element(document).ready(function () {

    $scope.findViewportWidth();

    // Variable Declarations for Event Countdown
    var eventDate, diff, timeLeft, clock, now;

    // Initiates fancy box galleries
    angular.element("a.grouped_elements").fancybox();

    // Initiate bxSlider
    angular.element('[data-role^="bx-slider-"]').bxSlider({
      minSlides: 3,
      maxSlides: 6,
      slideWidth: 318,
      slideMargin: 1
    });

    // Parsing event date from Database
    eventDate = '2015-05-27 12:42:01';
    eventDate = eventDate.replace(/-/g, '/');
    eventDate = Date.parse(eventDate);
    eventDate = new Date(eventDate);
    // Get time right now
    now = new Date();

    // Difference between now and event date
    diff = eventDate.getTime() - now.getTime();

    // Minutes left until event
    timeLeft = Math.abs(diff / 1000) / 60;

    // Initialize Flip Clock Plugin
    clock = $('.clock').FlipClock({
      autoStart: false,
      clockFace: 'DailyCounter',
      countdown: true
    });

    clock.setTime(timeLeft);

    // Start clock countdown
    clock.start();
  });

  $scope.email = {text: 'me@example.com'};

  $scope.userValidation = {
    email: /^([\w\-\.\+])+@([\w\-]+\.)+[\w\-]{2,4}$/
  };

  // Opens invite by email modal
  $scope.showModal = false;
  $scope.toggleModal = function () {
    $scope.showModal = !$scope.showModal;
    $('.modal-content').removeClass('modal-friend-select');
  };

  // Opens invite by friends list modal
  $scope.showModal2 = false;
  $scope.toggleModal2 = function () {
    $scope.showModal2 = !$scope.showModal2;
    $('.modal-content').addClass('modal-friend-select');
  };

  $scope.eventPreview = true;
  $scope.toggleThis = function () {
    $scope.eventPreview = !$scope.eventPreview;
  };

  $scope.eventLink = true;
  $scope.toggleLink = function () {
    $scope.eventLink = !$scope.eventLink;
  };

  $scope.socialDropdown = function (action) {
    $scope.socialOpen = action;
  };

});

app.directive('modal', function () {
  'use strict';
  return {
    template: '<div class="modal fade">' +
        '<div class="modal-dialog modal-min-width modal-height">' +
          '<div class="modal-content">' +
            '<div class="modal-header">' +
              '<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>' +
              '<h4 class="modal-title">{{ title }}</h4>' +
            '</div>' +
            '<div class="modal-body" ng-transclude></div>' +
          '</div>' +
        '</div>' +
      '</div>',
    restrict: 'E',
    transclude : true,
    replace : true,
    scope : true,
    link : function postLink(scope, element, attrs) {
      scope.title = attrs.title;

      scope.$watch(attrs.visible, function (value) {
        if (value === true) {
          $(element).modal('show');
        } else {
          $(element).modal('hide');
        }
      });

      $(element).on('shown.bs.modal', function () {
        scope.$apply(function () {
          scope.$parent[attrs.visible] = true;
        });
      });

      $(element).on('hidden.bs.modal', function () {
        scope.$apply(function () {
          scope.$parent[attrs.visible] = false;
        });
        $(this)
          .find("email,textarea")
          .val('')
          .end();
      });
    }
  };
});
