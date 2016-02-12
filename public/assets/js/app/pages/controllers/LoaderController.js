/*jslint unparam: true*/
/*global app, redirectData, location*/
app.controller('LoaderController', function ($scope) {

    // Invoke strict mode
    "use strict";

    // Url, time set before redirect, and message to be displayed
    $scope.redirectData = redirectData;

    // Timeout before refresh
    setTimeout(function () {

        // Location to redirect user
        location.href = $scope.redirectData.url;

    }, $scope.redirectData.time);

});
