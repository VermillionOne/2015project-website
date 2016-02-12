/*jslint unparam: true*/
/*global debugging*/
/*global app,suarayConfig, window*/
app.controller('FeedbackTabController', function ($scope, $window, $http, $location) {

  // Invoke strict mode
  "use strict";

  // Clears search form text and checkbox
  $(".clear-form").click(function () {
    $(".form-control").val('');
    $('input[type="checkbox"]').prop('checked', false);
  });


    // Initiate after page ready
  angular.element(document).ready(function () {

    // Function for Date picker
    angular.element('[data-role="date-picker"]').datepicker({
      format: 'mm/dd/yyyy',
      startDate: '-3d'
    });

  });

  // Does not allow digits in input
  $scope.nameValidation = {
    name: /^(\D)+$/
  };

  $scope.userValidation = {
    email: /^([\w\-\.\+])+@([\w\-]+\.)+[\w\-]{2,4}$/
  };

  $scope.sendFeedback = function (description, name, email, url) {

    // Posts feedback
    $http({
      method: 'post',
      url: suarayConfig.api.url + 'feedback/website',
      data: {
        description: description,
        name: name,
        email: email,
        url: url,
      }
    })
      .success(function (response, status, headers, config) {
        if (response.success === true) {

        }
      })
      .error(function (response, status, headers, config) {

      });

  };

  // User not allowed to enter negative values
  $(".no-negatives").keypress(function (event) {

    if ((event.which !== 46 || $(this).val().indexOf('.') !== -1) && (event.which < 48 || event.which > 57)) {
      event.preventDefault();
    }

    if (event.which === 45) {
      event.preventDefault();
    }
  });

  $(".int-only").keypress(function (event) {

    if ((event.which < 48 || event.which > 57)) {
      event.preventDefault();
    }
  });

  //Prevents user from entering numeric value in text field
  $('.feedback-name').keydown(function (e) {
    if (e.ctrlKey || e.altKey) {
      e.preventDefault();
    } else {
      var key = e.keyCode;

      if (!((key === 8) || (key === 32) || (key === 9) || (key === 46) || (key >= 35 && key <= 40) || (key >= 65 && key <= 90))) {
        e.preventDefault();
      }
    }
  });

  // Opens feedback modal
  $(".btn-float").click(function () {
    $(".feedback-modal").show("fold", 500);
  });

  // Closes feedback modal when close is clicked
  $(".close").click(function () {
    $(".feedback-modal").hide("fold", 500);
    $(".form-control").val('');
  });

  // When submit clicked switches to confirmation
  $(".btn-feedback").click(function () {
    $(".feedback-modal").hide("clip", 500);
    $(".feedback-modal-conf").show("clip", 500);
    $(".form-control").val('');
  });

  // Closes confirmation of feedback sent modal
  $(".close, .confirmation-close").click(function () {
    $(".feedback-modal-conf").hide("fold", 500);
  });

});
