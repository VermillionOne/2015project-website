/*jslint unparam: true*/
/*global debugging*/
/*global app , angular*/
app.controller('PaymentController', function ($scope, $cacheFactory) {

    // Invoke strict mode
    "use strict";

    var initialLoad, paymentAccount;

    initialLoad = function () {
        $scope.paymentAccountView = "direct-deposit-summary";
    };

    initialLoad();

    // Show Direct to Debit form
    $scope.showDirectToDebit = function () {
        // Go to view
        $scope.paymentAccountView = "direct-to-debit";
    };

    // Show Direct-Deposit form
    $scope.showDirectDeposit = function () {
        // Go to view
        $scope.paymentAccountView = "direct-deposit";
    };

    // Show Direct Deposit summary
    $scope.showDirectDepositSummary = function () {
        // Go to view
        $scope.paymentAccountView = "direct-deposit-summary";
    };

    $scope.paymentAccount = [];

    $scope.cache = $cacheFactory('paymentAccount');

    paymentAccount = $scope.paymentAccount;

    angular.element('input').blur(function () {
        paymentAccount.firstName = angular.element("[data-role='paymentAccount-firstName']")[0].value;
        paymentAccount.lastName = angular.element("[data-role='paymentAccount-lastName']")[0].value;
        paymentAccount.dobMonth = angular.element("[data-role='paymentAccount-dobMonth']")[0].value;
        paymentAccount.dobDay = angular.element("[data-role='paymentAccount-dobDay']")[0].value;
        paymentAccount.dobYear = angular.element("[data-role='paymentAccount-dobYear']")[0].value;
        paymentAccount.email = angular.element("[data-role='paymentAccount-email']")[0].value;
        paymentAccount.routingNumber = angular.element("[data-role='paymentAccount-routingNumber']")[0].value;
        paymentAccount.accountNumber = angular.element("[data-role='paymentAccount-accountNumber']")[0].value;
        paymentAccount.accountType = angular.element("[data-role='paymentAccount-accountType']")[0].value;
        paymentAccount.country = angular.element("[data-role='paymentAccount-country']")[0].value;
    });

  // $scope.addToPaymentAccount = function () {
  //   console.log("Testing Put");
  //   $scope.paymentAccount = paymentAccount;
  //   console.log($scope.paymentAccount);
  // };

  // angular.element(".num-only").keypress(function (event) {

  //   if ((event.which < 48 || event.which > 57)) {
  //     event.preventDefault();
  //   }
  // });

  // //Prevents user from entering numeric value in text field
  // angular.element('.payment-name').keydown(function (e) {
  //   if (e.shiftKey || e.ctrlKey || e.altKey) {
  //     e.preventDefault();
  //   } else {
  //     var key = e.keyCode;

  //     if (!((key === 8) || (key === 32) || (key === 9) || (key === 46) || (key >= 35 && key <= 40) || (key >= 65 && key <= 90))) {
  //       e.preventDefault();
  //     }
  //   }
  // });

  // // Initiate after page ready
  // angular.element(document).ready(function () {

  //   // Set validation Regular Expressions for Payment Account form
  //   $scope.validMob = /^\d{2}$/;
  //   $scope.validDob = /^\d{2}$/;
  //   $scope.validYob = /^\d{4}$/;
  //   $scope.validEmail = /^([\w\-\.\+])+@([\w\-]+\.)+[\w\-]{2,4}$/;
  //   $scope.validRoutingNumber = /^\d/;
  //   $scope.validAccountNumber = /^\d/;
  //   $scope.validAccountCountry = /^\w/;

  // });

});
