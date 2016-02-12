<?php if (!isset($payment)) $payment = 'create_event_payment'; ?>
<?php if (!isset($paymentLocked)) $paymentLocked = true; ?>

@if ($userCard != null)

  <div class="create-event-form-box">
    <div class="row">
      <h3 class="col-sm-12 create-event-form-main-title">Your card information is already saved, continue to the next step</h3>

      {{-- Hidden input to add the card data --}}
      {!! Form::hidden('cardId', $userCard, ['data-role' => 'card-id']) !!}

      {{-- 'NEXT >' Button to continue along form --}}
      <div class="form-group text-right">

        <div class="col-sm-10 col-sm-offset-1">
          <button class="btn btn-suaray btn-suaray-primary btn-lg pull-right" ng-click="tab = 'details'">Next <span class="glyphicon glyphicon-chevron-right"></span></button>
        </div>

      </div>
    </div>
  </div>

@else

  <div class="create-event-form-box payment-section">

    <div class="row">
      {{-- BEGIN Event Method of Payment input Fields --}}
      <h3 class="col-sm-12 create-event-form-main-title">Method of Payment</h3>

      {{-- First Name Input Field --}}
      <div class="form-group col-sm-10 col-sm-offset-1 @if ($errors->has('name')) has-error @endif }}">
        {!! Form::label('billing[name]', 'Name on Card', ['class' => 'clearfix control-label']) !!}<span class="text-danger">&#42;</span>
        @if(Input::old('billing[name]'))
          {!! Form::text('billing[name]', Input::old('billing[name]'), ['class' => 'form-control ng-dirty', 'data-role' => 'billingCardName', 'placeholder' => 'Full name as seen on card', 'required', 'ng-disabled' => 'event.premium === 0']) !!}
        @else
          {!! Form::text('billing[name]', null, ['class' => 'form-control', 'data-role' => 'billingCardName', 'placeholder' => 'Full name as seen on card', 'required', 'ng-disabled' => 'event.premium === 0']) !!}
        @endif
        {{ $errors->first('billing[name]', '<p class="help-block">:message</p>') }}
      </div>

      {{-- Credit Card Number Input Field --}}
      <div class="form-group col-sm-5 col-sm-offset-1 create-event-cc-number @if ($errors->has('card_number')) has-error @endif }}">
        {!! Form::label('billing[number]', 'Card Number', ['class' => 'clearfix control-label']) !!}<span class="text-danger">&#42;</span>
        @if(Input::old('billing[number]'))
          {!! Form::text('billing[number]', Input::old('billing[number]'), ['class' => 'form-control ng-dirty', 'data-role' => 'billingCardNumber', 'ng-pattern' => 'ccFormatting','placeholder' => '0000-0000-0000-0000', 'required', 'ng-disabled' => 'event.premium === 0']) !!}
        @else
        {!! Form::text('billing[number]', null, ['class' => 'form-control', 'data-role' => 'billingCardNumber', 'ng-pattern' => 'ccFormatting','placeholder' => '0000-0000-0000-0000', 'required', 'ng-disabled' => 'event.premium === 0']) !!}
        @endif
        {{ $errors->first('card_number', '<p class="help-block">:message</p>') }}
      </div>

      {{-- Credit Card CVC Number Input Field --}}
      <div class="form-group col-sm-2 @if ($errors->has('cvv_number')) has-error @endif }}">
        {!! Form::label('billing[cvc]', 'CVV', ['class' => 'clearfix control-label ']) !!}<span class="text-danger">&#42;</span>
          <i class="fa fa-question-circle fa-lg suaray-popover" tabindex="0" role="button" data-toggle="suaray-popover" data-trigger="hover" title="CVV Code" data-content="For VISA, MasterCard and Discover, the CVV number is the last 3 digits on the Signature Panel on the back of the card."></i>
          @if(Input::old('billing[cvc]'))
            {!! Form::text('billing[cvc]', Input::old('billing[cvc]'), ['class' => 'form-control ng-dirty', 'data-role' => 'billingCardCvv', 'ng-pattern' => 'ccSecurity','placeholder' => '000', 'required', 'ng-disabled' => 'event.premium === 0']) !!}
          @else
            {!! Form::text('billing[cvc]', null, ['class' => 'form-control ', 'data-role' => 'billingCardCvv', 'ng-pattern' => 'ccSecurity','placeholder' => '000', 'required', 'ng-disabled' => 'event.premium === 0']) !!}
          @endif
        {{ $errors->first('cvc', '<p class="help-block ">:message</p>') }}
      </div>

      {{-- Credit Card Expiration Date Input Field --}}
      <div class="form-group col-sm-3 @if ($errors->has('Expiration Date')) has-error @endif }}">
        <div class="create-payment-exp-date-">
          {!! Form::label('billing[month]', 'Exp. Date', ['class' => 'clearfix control-label ']) !!}<span class="text-danger">&#42;</span>
        </div>
        @if(Input::old('billing[month]'))
          {!! Form::text('billing[month]', Input::old('billing[month]'), ['class' => 'create-event-expiration-month create-exp form-control ng-dirty', 'data-role' => 'billingCardExpMonth', 'ng-pattern' => 'ccExpMonth', 'placeholder' => 'MM', 'required', 'ng-disabled' => 'event.premium === 0']) !!}
        @else
          {!! Form::text('billing[month]', null, ['class' => 'create-event-expiration-month create-exp form-control', 'data-role' => 'billingCardExpMonth', 'ng-pattern' => 'ccExpMonth', 'placeholder' => 'MM', 'required', 'ng-disabled' => 'event.premium === 0']) !!}
        @endif
        {{ $errors->first('month', '<p class="help-block ">:message</p>') }}

        @if(Input::old('billing[year]'))
          {!! Form::text('billing[year]', Input::old('billing[year]'), ['class' => 'create-event-expiration-year create-exp form-control ng-dirty', 'data-role' => 'billingCardExpYear', 'ng-pattern' => 'ccExpYear', 'placeholder' => 'YYYY', 'required', 'ng-disabled' => 'event.premium === 0']) !!}
        @else
          {!! Form::text('billing[year]', null, ['class' => 'create-event-expiration-year create-exp form-control', 'data-role' => 'billingCardExpYear', 'ng-pattern' => 'ccExpYear', 'placeholder' => 'YYYY', 'required', 'ng-disabled' => 'event.premium === 0']) !!}
        @endif
        {{ $errors->first('year', '<p class="help-block ">:message</p>') }}
      </div>

      {{-- BEGIN Form Elements | Location --}}
      <h3 class="col-sm-12 create-event-form-main-title">Billing Address</h3>

      {{-- Address Line 1 --}}
      <div class="form-group col-sm-10 col-sm-offset-1 @if ($errors->has('address1')) has-error @endif }}">
        {!! Form::label('billing[address]', 'Address Line 1', ['class' => 'clearfix control-label']) !!}<span class="text-danger">&#42;</span>
        @if(Input::old('billing[address]'))
          {!! Form::text('billing[address]', Input::old('billing[address]'), ['class' => 'form-control ng-dirty', 'data-role' => 'billingCardAddress', 'placeholder' => 'Building Number / Street Name', 'required', 'ng-disabled' => 'event.premium === 0']) !!}
        @else
          {!! Form::text('billing[address]', null, ['class' => 'form-control', 'data-role' => 'billingCardAddress', 'placeholder' => 'Building Number / Street Name', 'required', 'ng-disabled' => 'event.premium === 0']) !!}
        @endif
        {{ $errors->first('address1', '<p class="help-block">:message</p>') }}
      </div>

      {{-- City --}}
      <div class="form-group col-sm-4 col-sm-offset-1 @if ($errors->has('city')) has-error @endif }}">
        {!! Form::label('billing[city]', 'City', ['class' => 'clearfix control-label']) !!}<span class="text-danger">&#42;</span>
        @if(Input::old('billing[city]'))
          {!! Form::text('billing[city]', Input::old('billing[city]'), ['class' => 'form-control ng-dirty', 'data-role' => 'billingCardCity', 'placeholder' => 'City Name', 'required', 'ng-disabled' => 'event.premium === 0']) !!}
        @else
          {!! Form::text('billing[city]', null, ['class' => 'form-control', 'data-role' => 'billingCardCity', 'placeholder' => 'City Name', 'required', 'ng-disabled' => 'event.premium === 0']) !!}
        @endif
        {{ $errors->first('city', '<p class="help-block">:message</p>') }}
      </div>

      {{-- State --}}
      <div class="form-group col-sm-3 @if ($errors->has('state')) has-error @endif }}">
        {!! Form::label('billing[state]', 'State', ['class' => 'clearfix control-label']) !!}<span class="text-danger">&#42;</span>
        @if(Input::old('billing[state]'))
          {!! Form::text('billing[state]', Input::old('billing[state]'), ['class' => 'form-control ng-dirty', 'data-role' => 'billingCardState', 'placeholder' => 'State Name', 'required', 'ng-disabled' => 'event.premium === 0']) !!}
        @else
          {!! Form::text('billing[state]', null, ['class' => 'form-control', 'data-role' => 'billingCardState', 'placeholder' => 'State Name', 'required', 'ng-disabled' => 'event.premium === 0']) !!}
        @endif
        {{ $errors->first('state', '<p class="help-block">:message</p>') }}
      </div>

      {{-- Zip Code --}}
      <div class="form-group col-sm-3 @if ($errors->has('zipcode')) has-error @endif }}">
        {!! Form::label('billing[zip]', 'ZIP Code', ['class' => 'clearfix control-label']) !!}<span class="text-danger">&#42;</span>
        @if(Input::old('billing[zip]'))
          {!! Form::text('billing[zip]', null, ['type' => 'number', 'class' => 'form-control ng-dirty', 'data-role' => 'zipcode', 'data-role' => 'billingCardZipcode', 'placeholder' => '00000', 'required', 'ng-disabled' => 'event.premium === 0']) !!}
        @else
          {!! Form::text('billing[zip]', null, ['type' => 'number', 'class' => 'form-control', 'data-role' => 'zipcode', 'data-role' => 'billingCardZipcode', 'placeholder' => '00000', 'required', 'ng-disabled' => 'event.premium === 0']) !!}
        @endif
        {{ $errors->first('zipcode', '<p class="help-block">:message</p>') }}
      </div>

      {{-- 'NEXT >' Button to continue along form --}}
      <div class="form-group text-right">

        <div class="col-sm-10 col-sm-offset-1">
          <button class="btn btn-suaray btn-suaray-primary btn-lg pull-right" ng-click="tab = 'details'">Next <span class="glyphicon glyphicon-chevron-right"></span></button>
        </div>

      </div>

    </div>

  </div>

@endif
