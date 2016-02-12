/*jslint unparam: true, indent:2*/
/*global angular,document,$,console,app*/
app.controller('UsersNavigationController', function () {

  // Invoke strict mode
  "use strict";

  // Initiate after page ready
  angular.element(document).ready(function () {

    // Set bootstrap dropdown
    angular.element('.dropdown-toggle').dropdown();

    $('.dropdown-menu-holder').on('show.bs.dropdown', function () {

    });

  });

});
