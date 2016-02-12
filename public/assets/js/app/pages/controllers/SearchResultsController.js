/*jslint unparam: true, indent: 2*/
/*jslint plusplus: true */
/*global angular,console,document,app, navigator, $, google, L, handleNoGeolocation, alert, window*/
app.controller('SearchResultsController', function () {

    // Invoke strict mode
    "use strict";

    var locationStats;

    function showPosition(position) {

        locationStats = position.coords.latitude + "," + position.coords.longitude;

    }

    function getLocation() {
        if (navigator.geolocation) {

            navigator.geolocation.getCurrentPosition(showPosition);

        } else {

            alert('Position could not be found, enable location on your browser to load events.');

        }
    }

    getLocation();

       // Initiate after page ready
       $(document).ready(function () {

       // If window is resized in browser to below 771px, will set to grid view
       $(window).resize(function () {

        if ($(window).width() < 771) {

          $('.grid').show();
          $('.list').hide();

        } else {
          $('.grid').hide();
          $('.list').show();

        }

      }).resize();

    });

});
