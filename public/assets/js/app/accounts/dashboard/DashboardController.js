/*jslint unparam: true*/
/*global app*/
app.controller('DashboardController', function ($scope, $log, $http, $location, ScheduleProvider) {

  // Invoke strict mode
  "use strict";

  var percentageDegrees, createGradient, percentage, degrees, $this, i;

  // p is set to get the value of data, and return the percentage
  // d is set to take the percentage and turn into degrees
  percentageDegrees = function (p) {
    p = (p >= 100 ? 100 : p);
    var d = 3.6 * p;
    return d;
  };

  // Degree is then taken and set radial to specified degree
  createGradient = function (elem, d) {
    if (d <= 180) {
      d = 90 + d;
      elem.css('background', 'linear-gradient(90deg, #CCCCCC 50%, transparent 50%), linear-gradient(' + d + 'deg, #3496cf 50%, #CCCCCC 50%)');
    } else {
      d = d - 90;
      elem.css('background', 'linear-gradient(-90deg, #3496cf 50%, transparent 50%), linear-gradient(' + d + 'deg, #CCCCCC 50%, #3496cf 50%)');
    }
  };

  // Execute the above
  $('.pie').each(function () {

    $this = $(this);
    percentage = $this.data('percentage');
    degrees = percentageDegrees(percentage);
    createGradient($this, degrees);

  });

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
      $scope.tab = 'upcoming';
      angular.element('[data-role="tab-upcoming"]').trigger('click');

    }

    // Funcion for turning off and on recurring schedules
    angular.element('[data-role="recurringSchedule"]').change(function () {

      // Declare variable
      var repeats, fauxSelect;
      fauxSelect = {
        option : false
      };

      if (this.checked === true) {
        // Send through ScheduleProvider and grab resulting array
        repeats = ScheduleProvider.checkRepeats(angular.element('[data-role="repeats"]')[0]);
        console.log(repeats);
        // Set schedule inputs accoriding to interval
        if (repeats.interval === 'weekly') {
          // Automatically set the weekly value to assist in successful form submission
          $scope.event.repeat.every = 1;
        }
        // Assign string value of days selected to hidden input
        angular.element('[data-role="daysOfWeek"]')[0].value = repeats.daysSelectedString;
      } else {
        repeats = ScheduleProvider.checkRepeats(fauxSelect);
        // Assign string value of days selected to hidden input
        angular.element('[data-role="daysOfWeek"]')[0].value = '';
      }
    });

    // function for daysOfWeek string value concatenation
    // Upon a change of any of the days of the week checkboxes
    angular.element('[data-role^="day-of-week-"]').change(function () {
      // Declare Variables
      var daysSelectedString;
      // Get string value from Schedule Provider
      daysSelectedString = ScheduleProvider.checkDaysOfWeek(this);

      console.log(daysSelectedString);
      // Assign string value of days selected to hidden input
      angular.element('[data-role="daysOfWeek"]')[0].value = daysSelectedString;
    });

    // Check repeating base interval
    // Controls schedule values dependant on whether repeating value is 'daily', 'weekly', etc.
    angular.element('[data-role="repeats"]').change(function () {
      // Declare variable
      var repeats;
      // Send through ScheduleProvider and grab resulting array
      repeats = ScheduleProvider.checkRepeats(this);
      console.log(repeats);
      // Set schedule inputs accoriding to interval
      if (repeats.interval === 'weekly') {
        // Automatically set the weekly value to assist in successful form submission
        $scope.event.repeat.every = 1;
      }
      // Assign string value of days selected to hidden input
      angular.element('[data-role="daysOfWeek"]')[0].value = repeats.daysSelectedString;
    });

    angular.element("[data-role='ticketEnabled']").click();

    // Code for validating the date picker
    angular.element("#txtFromDate").datepicker({
      minDate: 0,
      numberOfMonths: 1,
      onSelect: function (selected) {
        angular.element("#txtToDate").datepicker("option", "minDate", selected);
        $scope.startDate = selected;
        // Add and remove the classes for ng-validate
        angular.element("#txtFromDate").removeClass('ng-pristine ng-untouched ng-invalid ng-invalid-required');
        angular.element("#txtFromDate").addClass('ng-touched ng-valid ng-valid-required ng-dirty ng-valid-parse');
      }
    });
    angular.element("#txtToDate").datepicker({
      minDate: 0,
      numberOfMonths: 1,
      onSelect: function (selected) {
        angular.element("#txtFromDate").datepicker("option", "maxDate", selected);
        $scope.endDate = selected;
        // Add and remove the classes for ng-validate
        angular.element("#txtToDate").removeClass('ng-pristine ng-untouched ng-invalid ng-invalid-required');
        angular.element("#txtToDate").addClass('ng-touched ng-valid ng-valid-required ng-dirty ng-valid-parse');
      }
    });
    angular.element("#txtFinalDate").datepicker({
        minDate: 0,
        numberOfMonths: 1
    });

    // Sets default date to current
    angular.element(".date-pick").datepicker('setDate', new Date());


    // Add .active class to My Events button
    angular.element('[data-role="myEvents"]').addClass('active');



    // Function for Date picker
    angular.element('.datepicker').datepicker({
      dateFormat: "yy-mm-dd"
    });

  });

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

});
app.directive('confirmationNeeded', function () {
  "use strict";
  return {
    priority: 1,
    terminal: true,
    link: function (scope, element, attr) {
      var msg, clickAction;

      msg = attr.confirmationNeeded || "This will permanently delete selected item. Confirm delete?";

      clickAction = attr.ngClick;

      element.bind('click', function (e) {

        /*global confirm*/
        if (msg && !confirm(msg)) {
          e.stopImmediatePropagation();
          e.preventDefault();
        }
      });
    }
  };
});
