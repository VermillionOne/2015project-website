/*global app*/
app.controller('SettingsController', function ($scope, $location) {

  // Invoke strict mode
    "use strict";

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
            $scope.tab = 'account';
            angular.element('[data-role="tab-account"]').trigger('click');

        }

    });

});
