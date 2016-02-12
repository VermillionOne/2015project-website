/*jslint unparam: true, indent: 2*/
/*jslint plusplus: true */
/*global angular,$,app,document,console,google, L, handleNoGeolocation, alert, window*/
app.controller('PageLoaderController', function ($scope, $timeout, $window, $http, $location) {

  // Invoke strict mode
  "use strict";

  $scope.pageReady = function () {

    /**
     * AJAX function for watching page load
     */
    $.ajax({
      xhr: function () {
        var xhr = new window.XMLHttpRequest();
        //Download progress
        xhr.addEventListener("progress", function (evt) {

          if (evt) {
            angular.element('[data-role="page-loader"]').find('.loading-bar').animate({
              width: '50%'
            }, 750, function () {
                angular.element('[data-role="page-loader"]').animate({
                  top: '-100%',
                  opacity: '0'
                });
            });
          }

        });
        return xhr;
      }
    });
  };

  angular.element(document).on('ready', function () {
    $scope.pageReady();
  });

});
