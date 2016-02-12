/*jslint white: true, browser: true, devel: true*/
/*jslint unparam: true*/
/*global app*/
app.controller('CategoriesController', function ($scope, $http, $filter) {

  // Invoke strict mode
  "use strict";

  // Initiate after page ready
  angular.element(document).ready(function () {
    // Add .active class to My Events button
    angular.element('[data-role="categories"]').addClass('active');
  });

});

