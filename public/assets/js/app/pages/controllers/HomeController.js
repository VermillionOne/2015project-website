/*jslint unparam: true, indent: 2*/
/*jslint plusplus: true */
/*global angular, console, $, document, app, google, L, handleNoGeolocation, alert, window*/
app.controller('HomeController', function ($scope, $timeout, $http, $location, HelperProvider) {

  // Invoke strict mode
  "use strict";

  var locationStats;

  // Function to define user coords
  function showPosition(position) {

      locationStats = position.coords.latitude + "," + position.coords.longitude;

  }

  // Gets location of user using navigator
  function getLocation() {

      if (navigator.geolocation) {

          // Calls show position to get coordinates which are set in locationStats variable to be saved as last location
          navigator.geolocation.getCurrentPosition(showPosition);

      }
  }

  getLocation();

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

  // Initiate after page ready
  angular.element(document).ready(function () {

    $scope.findViewportWidth();

    angular.element('[data-role="map"]');

    // Function for Date picker
    angular.element('[data-role="date-picker"]').datepicker({
      format: 'mm/dd/yyyy',
      startDate: '-3d'
    });

    // Add .active class to Home button
    angular.element('[data-role="home"]').addClass('active');

    // Initite bxSlider
    // data-role="bx-slider-row1", data-role="bx-slider-row2", etc
    angular.element('[data-role^="bx-slider-"]').bxSlider({
      infiniteLoop: false,
      maxSlides: 3,
      minSlides: 1,
      moveSlides: 3,
      nextText: '<span class="fa fa-chevron-right"></span>',
      oneToOneTouch: true,
      pager: false,
      prevText: '<span class="fa fa-chevron-left"></span>',
      responsive: true,
      slideMargin: 20,
      slideWidth: 310,
      touchEnabled: true
    });

  });

  var map, loader;

  // initiate map
  map = L.map('map').setView([36.1334396, -115.1655845], 13);

  // initate preloader spinning wheel
  loader = document.getElementById('loader');

  // hide loading wheel once map is done loading
  function finishedLoading() {
    // first, toggle the class 'done', which makes the loading screen
    // fade out
    loader.className = 'done';
    $timeout(function () {
      // then, after a half-second, add the class 'hide', which hides
      // it completely and ensures that the user can interact with the
      // map again.
      loader.className = 'hide';
    }, 500);
  }

  L.Icon.Default.imagePath = 'assets/bower_components/leaflet/dist/images';
  // Remove Leaflet watermark
  map.attributionControl.setPrefix(false);

  // variables being used threw out the page
  L.tileLayer('https://api.tiles.mapbox.com/v4/{id}/{z}/{x}/{y}.png?access_token={accessToken}', {
    id: 'suaray.9316f4e3',
    accessToken: 'pk.eyJ1Ijoic3VhcmF5IiwiYSI6ImRmOWExOGNjZmY1YmNiNWZhYjhiZjQwMjk3NTdhNzc3In0.i41eqQ5eqC3tzG5eWZ4UqQ',
  }).addTo(map).on('load', finishedLoading); // when the tiles load, remove the screen

  // load esri search plugin
  new L.Control.GeoSearch({
    provider: new L.GeoSearch.Provider.Esri()
  }).addTo(map);

  $scope.setMap = function (e) {

    // set map to you geolocation
    // Try HTML5 geolocation.
    if (navigator.geolocation) {

      map.locate({
        setView : true,
      });

      navigator.geolocation.getCurrentPosition(function(position) {
        var pos = {
          lat: position.coords.latitude,
          lng: position.coords.longitude
        };

      }, function() {
        handleLocationError(true, map.getCenter());
      });
    } else {

      // Browser doesn't support Geolocation
      handleLocationError(false, map.getCenter());
    }
  };

  function onLocationFound(e) {

    // create circle radius around your current location
    var radius = e.accuracy / 2;

    // add marker to the map for your geolocation
    L.marker(e.latlng).addTo(map)
      .bindPopup("You are here").openPopup();

    // add circle to the map
    L.circle(e.latlng, radius).addTo(map);

  }

  function onLocationError() {
    map = L.map('map').setView([36.11494050, -115.17202570], 13).bindPopup("Your Location <br/>Was Not Available").openPopup();
  }

  map.on('locationfound', onLocationFound);

  map.on('locationerror', onLocationError);

  // Get events by gps location
  $scope.getEventsByCoordinates = function () {

    var j, suarayIcons, eventTags, iconImage, LeafIcon, eventData, eventlat, eventlon, eventName, eventSlug, events, i, popup;

    LeafIcon = L.Icon.extend({
      options: {
        iconSize: [25, 37]
      }
    });

    // Make Api call
    $http({
      method: 'get',
      url: 'events/allevents',
    })
      .success(function (response) {

        // If we have events data
        if (response) {

          // make event info available through times
          events = response;
          //var dance = 'my dance';

          // loop threw all the rsvp'd events pulling from the database
          for (i = 0; i < events.length; i++) {

            // Do not iterate through functions
            if (typeof events[i] !== 'function') {

              // bring in the events array
              eventData = events[i];

              // load all the tags
              eventTags = eventData.tags;

              iconImage = 'default';

// dont move dance from this position
dance:
              for (j = 0; j < eventTags.length; j += 1) {
                // narrow the array to the first tag
                if (eventTags[j].isCategory && eventTags[j].isCategory === true) {
                  iconImage = HelperProvider.slugify(eventTags[j].tag);
                  break dance;
                }


              }


              // get events latitude from db for map coordinates
              eventlat = eventData.latitude;

              // get events longitude from db for map coordinates
              eventlon = eventData.longitude;

              // get events title name from db for tooltip
              eventName = eventData.title;

              // get event id
              eventSlug = eventData.slug;

              // load custom icons on the map
              suarayIcons = new LeafIcon({iconUrl: 'assets/img/map-markers/50x75/' + iconImage + '.png'});

              // set html data for info window
              popup = '<strong>' + eventData.title + '</strong><br>' + eventData.address1 + '<br>' + eventData.city + ', ' + eventData.state + ' ' + eventData.zipcode + '<br>' + eventData.nextEvent + '<br><a href="events/' + eventSlug + '">' + eventName + '</a>';

              // set markers for loaded events
              L.marker([eventlat, eventlon], {icon: suarayIcons}).addTo(map).bindPopup(popup);

            }

          }

        }


      });

  };

  // Load events
  $scope.getEventsByCoordinates();

});
