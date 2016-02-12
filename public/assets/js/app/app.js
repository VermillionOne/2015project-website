/*global angular,Bugsnag,suarayConfig*/
/*jslint unparam: true,indent: 2*/

// Bugsnag service
Bugsnag.apiKey = "5c637f12430adcf514db7ebddbd65f0b";
Bugsnag.releaseStage = suarayConfig.env;
Bugsnag.notifyReleaseStages = ['production', 'staging'];
angular.module('exceptionOverride', []).factory('$exceptionHandler', function () {

return function (exception, cause) {
    Bugsnag.notifyException(exception, {diagnostics: {cause: cause}});
  };
});

var app;

// Suaray module
app = angular.module('suaray', []);

// Configure app
app.config(function ($locationProvider, $sceDelegateProvider) {

  "use strict";

  // Enable HTML5 mode
  $locationProvider.html5Mode({
    'enabled': true,
    'rewriteLinks': false
  });

  // Set whitelisted domains for
  $sceDelegateProvider.resourceUrlWhitelist([
    // Allow same origin resource loads.
    'self',
    // Allow loading from our whitelisted domains.
    'http://*.cloudfront.net/**',
    'https://*.cloudfront.net/**',
    'http://*.*.amazonaws.com/**',
    'https://*.*.amazonaws.com/**',
    'http://*.*.compute.amazonaws.com/**',
    'https://*.*.compute.amazonaws.com/**'
  ]);
});

// Disables button element if ng-click is pressed in attribute
app.directive('button', function () {

  return {
    restrict: 'E',
    link: function (scope, elem, attrs) {

      // Disable blank href's or hash attributes
      if (attrs.ngClick) {
        elem.on('click', function (e) {
          e.preventDefault();
        });
      }
    }
  };
});

// Binds file upload for use with EvaporateJS
app.directive('filesBind', function () {

  return function (scope, elm, attrs) {
    elm.bind('change', function (evt) {
      scope.$apply(function () {
        scope[attrs.name] = evt.target.files;
      });
    });
  };
});

