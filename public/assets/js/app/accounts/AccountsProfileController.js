/*jslint unparam: true*/
/*global debugging*/
/*global app,event,location,Evaporate,suarayConfig, suarayEventCalendar*/
app.controller('AccountsProfileController', function ($scope, $http, $location) {

  // Invoke strict mode
  "use strict";

  var files, evap, i;

  $scope.edit = false;

  // Grabs events users have rsvp'd to
  $scope.suarayEventCalendar = suarayEventCalendar;

  //Prevents user from entering numeric value in text field
  $('.profile-name').keydown(function (e) {
    if (e.shiftKey || e.ctrlKey || e.altKey) {
      e.preventDefault();
    } else {
      var key = e.keyCode;

      if (!((key === 8) || (key === 32) || (key === 9) || (key === 46) || (key >= 35 && key <= 40) || (key >= 65 && key <= 90))) {
        e.preventDefault();
      }
    }
  });

  // Initiate after page ready
  angular.element(document).ready(function () {

    // For selecting the profile image file
    $scope.profileFileUpload = function (event) {
      angular.element('input[type="file"]').click();
    };

    $scope.isUpdated = function (user) {
      // Create request object
      $http({
        method: 'post',
        url: $location.url() + '/photo'
      })
        .success(function (response, status, headers, config) {
          if (response.success === true) {
            setTimeout(function () { location.reload(); }, 2000);
          }
        })
        .error(function (response, status, headers, config) {
        });
    };

    // Sets the filename in media tab
    // angular.element(evt.target).val(i);
    angular.element('#calendar').fullCalendar({
      header: {
        left: 'prev title next',
        center: '',
        right: 'month,agendaWeek,agendaDay'
      },
      editable: false,
      eventLimit: true, // allow "more" link when too many events
      events: $scope.suarayEventCalendar
    });

    // Initiate bxSlider
    angular.element('[data-role^="bx-slider-"]').bxSlider({
      minSlides: 3,
      maxSlides: 6,
      slideWidth: 318,
      slideMargin: 1
    });

    $(function () {
      angular.element('[data-toggle="tooltip"]').tooltip();
    });

    // Evaporate for media upload
    // use settings from suaray api endpoint (/v1/settings/upload)
    evap = new Evaporate({
      signerUrl: (suarayConfig.upload && suarayConfig.upload.signerUrl) || null,
      aws_key: (suarayConfig.upload && suarayConfig.upload.key) || null,
      aws_url: (suarayConfig.upload && suarayConfig.upload.bucketUrl) || null,
      cloudfront: true,
      bucket: (suarayConfig.upload && suarayConfig.upload.bucket) || null
    });

    // Upload, rename and save media files
    angular.element('[data-role="files"]').change(function (evt) {

      $scope.avatarPrep = true;

      // Variable declaration
      var newFileName, newFileExtension, dotPosition, userId, time;

      // Set files selected
      files = evt.target.files;

      time = new Date().getTime();

      // For every file
      for (i = 0; i < files.length; i += 1) {

        // Set file extension
        newFileExtension = '';

        // Set dot in filename position
        dotPosition = files[i].name.lastIndexOf('.');

        // Check if current file has a dot
        if (dotPosition !== -1) {

          // Set file extension
          newFileExtension = files[i].name.substring(files[i].name.lastIndexOf('.') + 1);

        }

        // Grab the users ID
        userId = angular.element(this).attr('data-user-id');

        // Set unique filename
        newFileName = 'avatar_' + userId + '_' + time + '.' + newFileExtension;

        $scope.waitForRefresh = function () {
          event.preventDefault();
          setTimeout(function () {
            location.reload();
          }, 5000);
        };

        evap.add({
          name: newFileName,
          file: files[i],
          fileIndex: i,
          signParams: {
            foo: 'bar'
          },
          complete: function (r) {

            angular.element('[data-role="submit-avatar-image"]').click();

          },
          progress: function (progress) {

          }
        });
      }

    });

  });

});
