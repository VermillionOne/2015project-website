{{-- Feedback form include --}}
<div ng-controller="FeedbackTabController">

  {{-- Initiate feedback modal --}}
  <div class="button-container">
    <button class="btn btn-float">
      Feedback
    </button>
  </div>

  {{-- Start of feedback modal --}}
  <div class="feedback-modal">
    {{-- Header and close button --}}
    <div class="row feedback">
      <div class="col-md-12">
        <div class="close-margin">
          <a type="button" class="close close-padding" aria-label="Close">
            <span aria-hidden="true" class="close-feedback">&times;</span>
          </a>
        </div>

        <p class="header-margin">Tell Us What You Like Or How We Can Improve</p>

      </div>
    </div>

    <form name="feedbackForm" novalidate>
      {{-- Text fields for feedback entry --}}
      <div class="feedback-modal-content">
        <div class="row">
          <div class="col-md-12 col-xs-12" ng-class="{ 'has-error' : feedbackForm.description.$invalid && !feedbackForm.description.$pristine }">

            {!! Form::label('description', 'Leave us a comment or question!') !!}

            {{-- Displays error description if user has not entered feedback --}}
            <p ng-show="feedbackForm.description.$required && !feedbackForm.description.$pristine" class="help-block help-position feedback-error-message">Enter your feedback to continue.</p>

            {{-- Displays error messages when description maxlength is met --}}
            <p ng-show="feedbackForm.description.$error.maxlength" class="help-block help-position feedback-error-message">Too many characters.</p>

            {!! Form::textarea('description', Input::old('description'), ['class' => 'form-control', 'maxlength' => '750', 'ng-model' => 'description', 'placeholder' => 'Feedback', 'name' => 'description', 'rows' => '4', 'required' => 'required']) !!}

          </div>
        </div>

        {{-- Optional fields --}}
        <div class="row">
          <div class="col-md-6 col-xs-12 close-padding">

            {!! Form::label('name', 'Name (optional)') !!}

            {!! Form::text('name', Input::old('name'), ['class' => 'form-control feedback-name', 'maxlength' => '50', 'ng-pattern' => "nameValidation.name", 'ng-model' => 'name', 'placeholder' => 'First Name']) !!}

          </div>

          <div class="col-md-6 col-xs-12 close-padding" ng-class="{ 'has-error' : feedbackForm.email.$invalid && !feedbackForm.email.$pristine }">
            {!! Form::label('email', 'Email (optional)') !!}

              {!! Form::email('email', Input::old('email'), ['class' => 'form-control', 'maxlength' => '100', 'ng-model' => 'email', 'ng-pattern' => 'userValidation.email', 'placeholder' => 'email@example.com']) !!}

              {{-- Displays error messages for invalid email input --}}
              <p ng-show="feedbackForm.email.$error.email" class="help-block help-position feedback-error-message-deux">Enter a valid email.</p>

              {{-- Displays error messages when email maxlength is met --}}
              <p ng-show="feedbackForm.email.$error.maxlength" class="help-block help-position feedback-error-message-deux">Too many characters.</p>
          </div>
        </div>

      </div>

        {{-- Gets page user is sending feedback from --}}
        {!! Form::hidden('url', env('API_URL') . Route::getCurrentRoute()->getPath(), ['ng-model' => 'url']) !!}

      <div class="form-submit">

        {{-- Submit feedback --}}
        {!! Form::submit('Submit Feedback', ['ng-click' => 'sendFeedback(description, name, email, url)', 'ng-disabled' => 'feedbackForm.$invalid', 'class' => 'btn btn-suaray btn-feedback btn-suaray-primary btn-md', 'name' => 'send-feedback']) !!}

      </div>
    </form>

  </div>

  {{-- Start of confirmation modal --}}
  <div class="feedback-modal-conf">

    {{-- Header and close button --}}
    <div class="row feedback">
      <div class="col-md-12">
        <div class="close-margin">
          <button type="button" class="close close-padding" aria-label="Close">
            <span aria-hidden="true" class="close-feedback">&times;</span>
          </button>
        </div>

        <p class="confirmation-modal-header">Thank You!</p>

      </div>
    </div>

    <div class="confirmation-container feedback-modal-content">

      {{-- Confirmation message --}}
      <div class = "close-padding">
        Your form has been successfully submitted!
      </div>

      <div class="close-padding">
        Your opinions and comments are very important to us and we read every message that we receive.
      </div>

      <div class="close-padding">
        Our goal is to improve our service any way we can, and we appreciate your taking the time to send us feedback.
      </div>

    </div>

    {{-- Close confirmation message --}}
    <div class="confirmation-modal-content">
      <button class="btn confirmation-close btn-suaray btn-suaray-primary btn-md">
        Close
      </button>
    </div>
  </div>

</div>




