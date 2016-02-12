/*jslint unparam: true*/
/*global app, document, $*/
app.controller('EventsGalleryController', ['$scope', function () {

    // Invoke strict mode
    "use strict";

    // Function that will allow for defualt image to show when transcoding has not been completed
    $(document).ready(function () {

        $(".default-picture-error").error(function () {

            $(this).attr('src', '../assets/img/transcoding/photo/generating-318x190.png');

        });

    });


}]);
