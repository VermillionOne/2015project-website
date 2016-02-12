/*jslint unparam: true, indent: 2 */
/*global angular,$,console,document,app,Evaporate,suarayConfig*/
app
  .controller('EventsCreateController', function ($scope, $log, $http, $location, $timeout, HelperProvider, ScheduleProvider) {

    // Invoke strict mode
    "use strict";

    // Calls to save input as    angular.element('..;

    // Calls popover to display nformation for user
    angular.element(function () {
      angular.element('[data-toggle="popover"]').popover();
    });

    //Prevents user from entering numeric value in text field
    angular.element('[data-role="feedback-name"]').keydown(function (e) {
      if (e.ctrlKey || e.altKey) {
        e.preventDefault();
      } else {
        var key = e.keyCode;

        if (!((key === 8) || (key === 32) || (key === 9) || (key === 46) || (key >= 35 && key <= 40) || (key >= 65 && key <= 90))) {
          e.preventDefault();
        }
      }
    });

    // Only allows user to input numeric value
    angular.element('[data-role="zipcode"]').keypress(function (e) {
      var a, k, i;
      a = [];
      k = e.which;

      for (i = 48; i < 58; i += 1) {
        a.push(i);
      }

      if (($.inArray(k, a) >= 0)) {
        e.preventDefault();
      }
    });

    // Setting scopes
    $scope.name = 'World';
    $scope.event = [];
    //scope for summary listings
    $scope.summaryItems = {};
    // used in update function
    $scope.master = {};
    // Address related
    $scope.update = function (event) {
      $scope.master = angular.copy(event);
    };
    // this function is necessary for event image upload in the create event form
    angular.element('[data-role = "eventTitle"]').blur(function () {
      $timeout(function () {
        $scope.event.title = angular.element('[data-role = "eventTitle"]').val();
      });
    });

    // Initiate after page ready - called at bottom of this document
    function pageReady() {

      // Variable delarations
      var files, evap, i, tab, dayKeyValue, endFinalDateInput, repeatIntervalFrequencyValue, standardEndTime;

      repeatIntervalFrequencyValue = angular.element('[data-role="repeatIntervalFrequency"]').val();

      standardEndTime = angular.element('select.standardEndTime');

      // Checking for old data to properly show premium view and data
      // if (angular.element('[data-role="premium"]')[0]) {
      //   if (angular.element('[data-role="premium"]')[0].value > 0) {
      //     angular.element('[data-role="premiumButton"]')[0].click();
      //     angular.element('.payment-section').find('input').addClass('ng-dirty');
      //   } else {
      //     $scope.free = true;
      //   }
      // }

      // Add .active class to Create Events button
      angular.element('[data-role="createEvent"]').addClass('active');

      // Code for validating the date picker
      angular.element("#txtFromDate").datepicker({
        minDate: 0,
        numberOfMonths: 1,
        dateFormat: 'D, MM d, yy',
        changeMonth: true,
        changeYear: true,
        onSelect: function (selected) {
          angular.element("#txtToDate").datepicker("option", "minDate", selected);
          $scope.event.startDate = selected;
          // Add and remove the classes for ng-validate
          angular.element("#txtFromDate").removeClass('ng-pristine ng-untouched ng-invalid ng-invalid-required');
          angular.element("#txtFromDate").addClass('ng-touched ng-valid ng-valid-required ng-dirty ng-valid-parse');
        }
      });
      angular.element("#txtToDate").datepicker({
        minDate: 0,
        numberOfMonths: 1,
        dateFormat: 'D, MM d, yy',
        changeMonth: true,
        changeYear: true,
        onSelect: function (selected) {
          angular.element("#txtFromDate").datepicker("option", "maxDate", selected);
          $scope.event.endDate = selected;
          // Add and remove the classes for ng-validate
          angular.element("#txtToDate").removeClass('ng-pristine ng-untouched ng-invalid ng-invalid-required');
          angular.element("#txtToDate").addClass('ng-touched ng-valid ng-valid-required ng-dirty ng-valid-parse');
        }
      });
      angular.element("#txtFinalDate").datepicker({
        minDate: 0,
        numberOfMonths: 1,
        dateFormat: 'D, MM d, yy',
        changeMonth: true,
        changeYear: true,
        onSelect: function (selected) {
          $timeout(function () {
            $scope.finalEndTimeSelected = true;
          });
          angular.element("#txtFinalDate").removeClass('ng-pristine ng-untouched ng-invalid ng-invalid-required');
          angular.element("#txtFinalDate").addClass('ng-touched ng-valid ng-valid-required ng-dirty ng-valid-parse');
          angular.element('[data-role="endFinalTime"]').val(standardEndTime.val());
          angular.element('input[data-role="endFinalMeridian"]').val($scope.endAmPm);
        }
      });

      // Sets default date to current if not already set
      if (angular.element("input.date-pick").val() === '') {
        angular.element("#txtFromDate").datepicker('setDate', new Date());
        angular.element("#txtToDate").datepicker('setDate', new Date());
      }

      $scope.ccFormatting = /^3(?:[47]\d([\-]?)\d{4}(?:\1\d{4}){2}|0[0-5]\d{11}|[68]\d{12})$|^4(?:\d\d\d)?([\-]?)\d{4}(?:\2\d{4}){2}$|^6011([\-]?)\d{4}(?:\3\d{4}){2}$|^5[1-5]\d\d([\-]?)\d{4}(?:\4\d{4}){2}$|^2014\d{11}$|^2149\d{11}$|^2131\d{11}$|^1800\d{11}$|^3\d{15}$/;

      $scope.ccSecurity = /^([0-9]{3,4})$/;

      $scope.ccExpDate = /^(\d{1,2}\/\d{1,2})$/;

      // Function for view nav
      angular.element('[data-role="view-nav"]').click(function () {
        angular.element(this).closest('[data-role="view-nav-container"]').find('[data-role="show-nav"]').collapse('toggle');
      });

      // Function for Option Collapsible panel
      angular.element('[data-role="show-info-button"]').click(function () {
        angular.element(this).closest('[data-role="show-info-container"]').find('[data-role="show-info"]').collapse('toggle');
      });

      // Code for Suaray Popover
      angular.element('[data-toggle="suaray-popover"]').popover({
        // This determines the HTML template of the popover
        template: '<div class="popover sauray-popover" role="tooltip"><div class="arrow"></div><h3 class="popover-title"></h3><div class="popover-content"></div></div>',
        viewport: {selector: 'body', padding: 0},
        placement: 'top'
      });

      endFinalDateInput = angular.element('input.endFinalDate');

      $scope.event.repeat = {};

      function getRecurringSchedule() {

        // Declare variable
        var repeats, isRecurring, fauxSelect;
        fauxSelect = {
          option : false
        };

        isRecurring = angular.element('[data-role="recurringSchedule"]');

        if (isRecurring.prop('checked')) {

          $timeout(function () {
            $scope.event.repeat.enabled = true;
          });

          // Send through ScheduleProvider and grab resulting array
          repeats = ScheduleProvider.checkRepeats(angular.element('[data-role="repeatInterval"]')[0]);

          // Set schedule inputs according to interval
          if (repeats.interval === 'daily') {
            // Automatically set the daily value to assist in successful form submission
            if (repeatIntervalFrequencyValue === 1) {
              $timeout(function () {
                $scope.repeatIntervalFrequency = 1;
              });
            }
            $timeout(function () {
              $scope.repeatInterval = 'daily';
            });

          } else if (repeats.interval === 'weekly') {
            // Automatically set the weekly value to assist in successful form submission
            if (repeatIntervalFrequencyValue === 1) {
              $timeout(function () {
                $scope.repeatIntervalFrequency = 1;
              });
            }
            $timeout(function () {
              $scope.repeatInterval = 'weekly';
            });

          } else if (repeats.interval === 'monthly') {
            // Automatically set the monthly value to assist in successful form submission
            if (repeatIntervalFrequencyValue === 1) {
              $timeout(function () {
                $scope.repeatIntervalFrequency = 1;
              });
            }
            $timeout(function () {
              $scope.repeatInterval = 'monthly';
            });

          } else if (repeats.interval === 'yearly') {
            // Automatically set the yearly value to assist in successful form submission
            if (repeatIntervalFrequencyValue === 1) {
              $timeout(function () {
                $scope.repeatIntervalFrequency = 1;
              });
            }
            $timeout(function () {
              $scope.repeatInterval = 'yearly';
            });
          }

          // Assign string value of days selected to hidden input
          angular.element('[data-role="daysOfWeek"]')[0].value = repeats.daysSelectedString;

        } else {

          $timeout(function () {
            $scope.event.repeat.enabled = false;
          });

          repeats = ScheduleProvider.checkRepeats(fauxSelect);
          // Assign string value of days selected to hidden input
          if (angular.element('[data-role="daysOfWeek"]')[0]) {
            angular.element('[data-role="daysOfWeek"]')[0].value = '';
          }

        }
      }

      getRecurringSchedule();

      // Funcion for turning off and on recurring schedules
      angular.element('[data-role="recurringSchedule"]').change(function () {
        getRecurringSchedule();
      });

      // function for daysOfWeek string value concatenation
      // Upon a change of any of the days of the week checkboxes
      angular.element('[data-role^="day-of-week-"]').change(function () {
        // Declare Variables
        var daysSelected, dayIndex;

        $scope.recurringDaysOfWeek = {};

        // Get string value from Schedule Provider
        daysSelected = ScheduleProvider.checkDaysOfWeek(this);
        // Assign string value of days selected to hidden input
        angular.element('[data-role="daysOfWeek"]')[0].value = daysSelected.string;

        $scope.daysSelected = [];
        $scope.daysSelected = daysSelected.array;

        for (dayIndex = 0; dayIndex < $scope.daysSelected.length; dayIndex += 1) {

          dayKeyValue = $scope.daysSelected[dayIndex];
          $scope.recurringDaysOfWeek[dayKeyValue] = true;

        }

      });

      // Check repeating base interval
      // Controls schedule values dependant on whether repeating value is 'daily', 'weekly', etc.
      angular.element('[data-role="repeatInterval"]').change(function () {
        // Declare variable
        var repeats, repeatIntervalFrequency;
        // Send through ScheduleProvider and grab resulting array
        repeats = ScheduleProvider.checkRepeats(this);
        repeatIntervalFrequency = angular.element('[data-role="repeatIntervalFrequency"]').val();
        // Assign repeats interval value to update scope to give the user proper feedback
        // $timeout required to instantiate scope update
        $timeout(function () {
          $scope.repeatInterval = repeats.interval;
        });
        // If repeatIntervalFrequency value is not set, automatically set it to 1
        if (repeatIntervalFrequency <= 0) {
          angular.element('[data-role="repeatIntervalFrequency"]').val(1);
        }
        // Assign string value of days selected to hidden input
        angular.element('[data-role="daysOfWeek"]')[0].value = repeats.daysSelectedString;
      });

      // Function for determing whether interval is plural
      angular.element('[data-role="repeatIntervalFrequency"]').change(function () {
        $timeout(function () {
          $scope.repeatIntervalFrequency = angular.element('[data-role="repeatIntervalFrequency"]').val();
        });
      });

      // The following small group of functions adds style for after data is input into from elements
      angular.element('input, textarea').keypress(function () {
        angular.element(this).addClass('ng-dirty');
      });
      angular.element('select').on('click', function () {
        angular.element(this).addClass('ng-dirty');
      });

      standardEndTime.change(function () {
        angular.element('[data-role="endFinalTime"]').val(standardEndTime.val());
      });

      /**
       * Function for setting start time AM/PM
       */
      $scope.setStartMeridian = function (meridian) {
        $timeout(function () {
          $scope.startAmPm = meridian;
          $scope.getSummaryScope();
        });
      };
      /**
       * Function for setting end time AM/PM
       */
      $scope.setEndMeridian = function (meridian) {
        $timeout(function () {
          $scope.endAmPm = meridian;
          $scope.getSummaryScope();
        });
        if ($scope.finalEndTimeSelected === true) {
          angular.element('input[data-role="endFinalMeridian"]').val(meridian);
          $scope.getSummaryScope();
        }
      };

      /**
       * Set Scope for start meridians if old data is present
       */
      if (angular.element('[data-role$="Start"]:checked').val() === 'am') {
        $timeout(function () {
          $scope.setStartMeridian('am');
        });
      } else if (angular.element('[data-role$="Start"]:checked').val() === 'pm') {
        $timeout(function () {
          $scope.setStartMeridian('pm');
        });
      }
      /**
       * Set Scope for end meridians if old data is present
       */
      if (angular.element('[data-role$="End"]:checked').val() === 'am') {
        $timeout(function () {
          $scope.setEndMeridian('am');
        });
      } else if (angular.element('[data-role$="End"]:checked').val() === 'pm') {
        $timeout(function () {
          $scope.setEndMeridian('pm');
        });
      }

      angular.element('[data-role$="-on"]:checked').parent().click();
      angular.element('[data-role$="-off"]:checked').parent().click();

      // function for clearing the final end date
      $scope.clearEndFinalDate = function () {
        endFinalDateInput.val('');
        angular.element('[data-role="endFinalTime"]').val('');
        angular.element('input[data-role="endFinalMeridian"]').val('');
        endFinalDateInput.removeClass('ng-dirty');
        $timeout(function () {
          $scope.finalEndTimeSelected = false;
        });
      };

      // Evaporate for media upload
      // use settings from suaray api endpoint (/v1/settings/upload)
      evap = new Evaporate({
        signerUrl: (suarayConfig.upload && suarayConfig.upload.signerUrl) || null,
        aws_key: (suarayConfig.upload && suarayConfig.upload.key) || null,
        aws_url: (suarayConfig.upload && suarayConfig.upload.bucketUrl) || null,
        cloudfront: true,
        bucket: (suarayConfig.upload && suarayConfig.upload.bucket) || null
      });

      // Get event query param from url
      tab = $location.search().tab;
      if (tab !== '') {
        $scope.tab = tab;
        angular.element('[data-role="tab-' + tab + '"]').trigger('click');
      }

      // Upload, rename and save media files
      angular.element('[data-role="files"]').change(function (evt) {

        // Variable declaration
        var newFileName, newFileExtension, dotPosition, slug, userId;

        // Set files selected
        files = evt.target.files;

        // For every file
        for (i = 0; i < files.length; i += 1) {

          // Grab the users ID
          userId = angular.element(this).attr('data-user-id');

          // Set dot in filename position
          dotPosition = files[i].name.lastIndexOf('.');

          // Check if current file has a dot
          if (dotPosition !== -1) {

            // Set file extension
            newFileExtension = files[i].name.substring(files[i].name.lastIndexOf('.') + 1);

          }

          // Do we need to override the slug ?
          if (suarayConfig.upload.overrideSlug) {

            // Set slug to override
            slug = suarayConfig.upload.overrideSlug;

          } else {

            // Use slug from attribute field
            slug = angular.element(this).attr('data-slug');
          }

          // Sluggify that sucker
          slug =  HelperProvider.slugify(slug);
          // Grab the users ID
          userId = angular.element(this).attr('data-user-id');

          // Set unique filename
          newFileName = userId + '_' + slug + '_' + Math.floor(1000000000 * Math.random()) + '.' + newFileExtension;

          evap.add({
            name: newFileName,
            file: files[i],
            fileIndex: i,
            signParams: {
              foo: 'bar'
            },
            complete: function (r) {

              // Hide progress bar
              angular.element('[ng-data-role="progress-' + this.fileIndex + '"]').css({display: 'none'});

              // idk
              angular.element('[ng-data-role="upload-sucessful-' + this.fileIndex + '"]').css({display: 'inline'});
            },
            progress: function (progress) {

              // idk
              angular.element('[ng-data-role="progress-bar-' + this.fileIndex + '"]').css({width: Math.floor(progress * 100) + '%'});
            }
          });
        }

        // Sets the filename in media tab
        // angular.element(evt.target).val(i);

      });
    }

    var userCardId = angular.element('[data-role="card-id"]').val();

    $scope.premiumOption = function (value) {
      if (value === 1) {
        $scope.tab = 'payment';
        $scope.event.premium = value;
        angular.element('[data-role="card-id"]').val(userCardId);
        angular.element('[data-role="premium"]').val(value);
      } else if (value === 0) {
        $scope.tab = 'details';
        $scope.event.premium = value;
        angular.element('[data-role="card-id"]').val(null);
        angular.element('[data-role="premium"]').val(value);
      }
    };

    var nextHour = {
      "12:00": "1:00",
      "12:15": "1:15",
      "12:30": "1:30",
      "12:45": "1:45",
      "1:00": "2:00",
      "1:15": "2:15",
      "1:30": "2:30",
      "1:45": "2:45",
      "2:00": "3:00",
      "2:15": "3:15",
      "2:30": "3:30",
      "2:45": "3:45",
      "3:00": "4:00",
      "3:15": "4:15",
      "3:30": "4:30",
      "3:45": "4:45",
      "4:00": "5:00",
      "4:15": "5:15",
      "4:30": "5:30",
      "4:45": "5:45",
      "5:00": "6:00",
      "5:15": "6:15",
      "5:30": "6:30",
      "5:45": "6:45",
      "6:00": "7:00",
      "6:15": "7:15",
      "6:30": "7:30",
      "6:45": "7:45",
      "7:00": "8:00",
      "7:15": "8:15",
      "7:30": "8:30",
      "7:45": "8:45",
      "8:00": "9:00",
      "8:15": "9:15",
      "8:30": "9:30",
      "8:45": "9:45",
      "9:00": "10:00",
      "9:15": "10:15",
      "9:30": "10:30",
      "9:45": "10:45",
      "10:00": "11:00",
      "10:15": "11:15",
      "10:30": "11:30",
      "10:45": "11:45",
      "11:00": "12:00",
      "11:15": "12:15",
      "11:30": "12:30",
      "11:45": "12:45"
    };

    //
    angular.element('[data-role="startTime"]').click(function () {
      // Enables end time select and am/pm buttons
      $scope.timeSelected = true;
      var startValue, startTime = angular.element('[data-role="startTime"]').val();
      startValue = startTime.replace(/:/g, '');
      angular.element('[data-role="endTime"]').val(nextHour[startTime]);

      if (startValue === '1200') {
        // Angular cannot properly handle these calls due to $apply/$digest issues without the $timeout enabled
        // Enable PM for Start time
        $timeout(function () {
          angular.element('[data-role="pmStart"]').click();
          $scope.event.startMeridian = 'pm';
          if ($scope.finalEndTimeSelected === true) {
            angular.element('input[data-role="endFinalMeridian"]').val('am');
          }
        }, 0);
        // Enable PM for End time
        $timeout(function () {
          angular.element('[data-role="pmEnd"]').click();
          $scope.event.endMeridian = 'pm';
          if ($scope.finalEndTimeSelected === true) {
            angular.element('input[data-role="endFinalMeridian"]').val('pm');
          }
        }, 0);

        // These are to validate the form and enable proper styling
        angular.element('[data-role="startTime"]').removeClass('ng-pristine ng-untouched ng-invalid');
        angular.element('[data-role="startTime"]').addClass('ng-valid ng-dirty ng-touched');
        angular.element('[data-role="endTime"]').removeClass('ng-pristine ng-untouched ng-invalid');
        angular.element('[data-role="endTime"]').addClass('ng-valid ng-dirty ng-touched');

      }
    });

    // Function for setting end time and median buttons automatically based on selected start time
    angular.element('[data-role="startTime"]').change(function () {

      // Enables end time select and am/pm buttons
      $scope.timeSelected = true;

      // Define startValue as a variable
      var startValue, startTime;

      startTime = angular.element('[data-role="startTime"]').val();
      angular.element('[data-role="endTime"]').val(nextHour[startTime]);
      // startValue is the time with colon removed
      startValue = startTime.replace(/:/g, '');

      // These are to validate the form and enable proper styling
      angular.element('[data-role="startTime"]').removeClass('ng-pristine ng-untouched ng-invalid');
      angular.element('[data-role="startTime"]').addClass('ng-valid ng-dirty ng-touched');
      angular.element('[data-role="endTime"]').removeClass('ng-pristine ng-untouched ng-invalid');
      angular.element('[data-role="endTime"]').addClass('ng-valid ng-dirty ng-touched');

      // This if statement enacts the proper am/pm button determined by time of day clicked as an added ease of use for the user
      // If the time is between 1:00 and 9:00, or after 12:00
      if (startValue >= 1200 || startValue < 900) {

        // Angular cannot properly handle these calls due to $apply/$digest issues without the $timeout enabled
        // Enable PM for Start time
        $timeout(function () {
          angular.element('[data-role="pmStart"]').click();
          $scope.event.startMeridian = 'pm';
        }, 0);
        // Enable PM for End time
        $timeout(function () {
          angular.element('[data-role="pmEnd"]').click();
          $scope.event.endMeridian = 'pm';
          if ($scope.finalEndTimeSelected === true) {
            angular.element('input[data-role="endFinalMeridian"]').val('pm');
          }
        }, 0);

      // If the time is between 9:00 and 11:00
      } else if (startValue >= 900 && startValue < 1100) {

        // Enable AM for Start time
        $timeout(function () {
          angular.element('[data-role="amStart"]').click();
          $scope.event.startMeridian = 'am';
        }, 0);
        // Enable AM for End time
        $timeout(function () {
          angular.element('[data-role="amEnd"]').click();
          $scope.event.endMeridian = 'am';
          if ($scope.finalEndTimeSelected === true) {
            angular.element('input[data-role="endFinalMeridian"]').val('am');
          }
        }, 0);

      // If the time is between 11:00 and 12:00
      } else if (startValue >= 1100 && startValue < 1200) {

        // Enable AM for Start time
        $timeout(function () {
          angular.element('[data-role="amStart"]').click();
          $scope.event.startMeridian = 'am';
        }, 0);
        // Enable PM for End time
        $timeout(function () {
          angular.element('[data-role="pmEnd"]').click();
          $scope.event.endMeridian = 'pm';
          if ($scope.finalEndTimeSelected === true) {
            angular.element('input[data-role="endFinalMeridian"]').val('pm');
          }
        }, 0);

      }

    });

    /**
     * This method creates the summary view data that displays for the user
     */
    $scope.getSummaryScope = function () {

      var tags, tagsArray, daysOfWeek;

      // Turns the tag string in input from into an array for ng-repeat in the Summary page
      if (angular.element('[data-role="tagsInput"]') && angular.element('[data-role="tagsInput"]').length > 0) {
        tags = angular.element('[data-role="tagsInput"]').val();
        tagsArray = tags.split(',');
      }

      if (angular.element('[data-role="daysOfWeek"]') && angular.element('[data-role="daysOfWeek"]').length > 0) {
       daysOfWeek = angular.element('[data-role="daysOfWeek"]')[0].value;
      }

      $scope.summaryItems = {
        'title': angular.element('[data-role="eventTitle"]').val(),
        'description': angular.element('[data-role="eventDescription"]').val(),
        'timeZoneId': angular.element(".selected-timezone option:selected").text(),
        'startDate': angular.element('#txtFromDate').val(),
        'startTime': angular.element('[data-role="startTime"]').val(),
        'startMeridian': $scope.startAmPm,
        'endDate': angular.element('#txtToDate').val(),
        'endTime': angular.element('[data-role="endTime"]').val(),
        'endMeridian': $scope.endAmPm,
        'isIndoor': angular.element('[data-role="isIndoor"]').is(':checked'),
        'isOutdoor': angular.element('[data-role="isOutdoor"]').is(':checked'),
        'isAge0': angular.element('[data-role="isAge0"]').is(':checked'),
        'isAge13': angular.element('[data-role="isAge13"]').is(':checked'),
        'isAge16': angular.element('[data-role="isAge16"]').is(':checked'),
        'isAge18': angular.element('[data-role="isAge18"]').is(':checked'),
        'isAge21': angular.element('[data-role="isAge21"]').is(':checked'),
        'venue': angular.element('[data-role="eventVenueName"]').val(),
        'address': angular.element('[data-role="eventAddress"]').val(),
        'city': angular.element('[data-role="eventCity"]').val(),
        'state': angular.element('[data-role="eventState"]').val(),
        'zipcode': angular.element('[data-role="eventZipcode"]').val(),
        'billingCardName': angular.element('[data-role="billingCardName"]').val(),
        'billingCardNumber': angular.element('[data-role="billingCardNumber"]').val(),
        'billingCardCvv': angular.element('[data-role="billingCardCvv"]').val(),
        'billingCardExpMonth': angular.element('[data-role="billingCardExpMonth"]').val(),
        'billingCardExpYear': angular.element('[data-role="billingCardExpYear"]').val(),
        'billingCardAddress': angular.element('[data-role="billingCardAddress"]').val(),
        'billingCardCity': angular.element('[data-role="billingCardCity"]').val(),
        'billingCardState': angular.element('[data-role="billingCardState"]').val(),
        'billingCardZipcode': angular.element('[data-role="billingCardZipcode"]').val(),
        'repeatEnabled': $scope.event.repeat.enabled,
        'repeatRepeats': angular.element('[data-role="repeatInterval"]').val(),
        'repeatIntervalFrequency': angular.element('[data-role="repeatIntervalFrequency"]').val(),
        'repeatWeekdays': daysOfWeek,
        'finalEndDate': angular.element('input.endFinalDate').val(),
        'finalEndTime': angular.element('input[data-role="endFinalTime"]').val(),
        'finalEndMeridian': angular.element('input[data-role="endFinalMeridian"]').val(),
        'tags': tagsArray,
        'category': angular.element('[data-role="eventCategory"] option:selected').text(),
        'videoEmbed': {
          'youtube': angular.element('[data-role="youtube-video-code"]').val().replace(/\s+/g, '').split(','),
          'vimeo': angular.element('[data-role="vimeo-video-code"]').val().replace(/\s+/g, '').split(',')
        }
        // 'repeatMonths':  angular.element('[data-role="months"]').val()
      };
    };

    // Function for gathering input values for the summary page
    $scope.openSummary = function () {
      // Set tab scope to Summary
      $scope.tab = 'summary';
      $scope.getSummaryScope();
    };

    $scope.youtubeCodes = '';
    $scope.vimeoCodes = '';
    /**
     * method for adding videos to the event
     */
    $scope.addVideo = function (channel) {
      $scope.getSummaryScope();

      // Declare Variables for the iframe templates
      var youtubeCodes, vimeoCodes, youtubeEmbed, vimeoEmbed, i;

      console.log($scope.summaryItems.videoEmbed.youtube);

      youtubeCodes = $scope.summaryItems.videoEmbed.youtube;
      vimeoCodes = $scope.summaryItems.videoEmbed.vimeo;

      if (channel === 'youtube') {
        $scope.youtubeCodes = youtubeCodes;
        angular.element('.video-holder.youtube').html('');
          for (i = 0; i < youtubeCodes.length; i += 1) {
            if (youtubeCodes[i] === ''){
              $scope.youtubeCodes = '';
              angular.element('.video-holder.youtube').html('');
            } else {
            youtubeEmbed = '<div class="video-iframe-holder"><p class="embed-video-header">' + 'YouTube: "' + youtubeCodes[i] + '"></span></p><iframe src="https://www.youtube.com/embed/' + youtubeCodes[i] + '" frameborder="0" allowfullscreen></iframe></div>';
            angular.element(angular.element('[data-role="youtube-embed-video-preview"]')).append(youtubeEmbed);
          }
        }
      } else if (channel === 'vimeo') {
        $scope.vimeoCodes = vimeoCodes;
        angular.element('.video-holder.vimeo').html('');
          for (i = 0; i < vimeoCodes.length; i += 1) {
            if (vimeoCodes[i] === ''){
              $scope.vimeoCodes = '';
              angular.element('.video-holder.vimeo').html('');
            } else {
            vimeoEmbed = '<div class="video-iframe-holder"><p class="embed-video-header">' + 'Vimeo: "' + vimeoCodes[i] + '"></span></p><iframe src="https://player.vimeo.com/video/' + vimeoCodes[i] + '" frameborder="0" allowfullscreen></iframe></div>';
            angular.element(angular.element('[data-role="vimeo-embed-video-preview"]')).append(vimeoEmbed);
          }
        }
      }
      angular.element('iframe').css('width', '100%');
    };

    angular.element(document).ready(function () {

      var youtubeVideoInput, vimeoVideoInput;

      youtubeVideoInput = angular.element('[data-role="youtube-video-code"]').val();
      vimeoVideoInput = angular.element('[data-role="vimeo-video-code"]').val();

      pageReady();
      $scope.getSummaryScope();
      if (youtubeVideoInput !== '') {
        $scope.addVideo('youtube');
      }
      if (vimeoVideoInput !== '') {
        $scope.addVideo('vimeo');
      }
    });

    $scope.freeEvent = function () {
      $scope.billing = "";
      $scope.free = "true";
    };

  });
