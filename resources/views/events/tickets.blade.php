@extends('layouts.master')

@section('javascript-includes')
  <script src="{{ ViewHelper::asset('assets/dist/lib-events-show.js') }}"></script>
@stop

@section('content')
<div class = "tickets-sect">
	<div class = "container">
		<div class = "row">
			<div class = "col-md-9">
				<div class="tickets-sect-hd">
          @include('events.sections.carousel')
      	</div>
			</div>
			<div class = "col-md-3">
				<div class = "tickets-sect-hd">

					<div class = "tickets-title-sect">
						<div class = "tickets-title-txt">Pricing Details</div>
					</div>
					@if ($purchased===null)

						@if (\Session::has('fail_message'))
							<div class = "tickets-type-title">{{ $many }} TICKETS</div>

							<div class = "tickets-order-sect">
								<div class = "tickets-order-title">Order Total</div>

								<div class = "tickets-order-number">

									@if (Input::old('amount') > 0)
										$ {{ number_format(Input::old('amount'), 2) }} USD
									@else
										$ 0 USD
									@endif

								</div>
							</div>
						@else
							<div class = "tickets-type-title">NO TICKETS</div>

							<div class = "tickets-order-sect">
								<div class = "tickets-order-title">Order Total</div>

								<div class = "tickets-order-number">
								  $ 0 USD
								</div>
							</div>
						@endif

					@else

						{{-- Loop through each purchased ticket --}}
						@foreach ($purchased as $event)
								<div class = "tickets-type-title">{{ $event['name'] }}</div>
								<div class = "tickets-type-amount">{{ $event['qty'] }} Tickets	</div>
						@endforeach

						<div class = "tickets-order-sect">

							<div class = "tickets-order-title">Order Total</div>

							<div class = "tickets-order-number">

								$ {{ number_format($cost, 2) }} USD

							</div>
						</div>

					@endif

				</div>

				<div class = "tickets-note-sect">
					*Note: The amount shown includes the ticket/item price plus, when applicable, service fee, facility charge, and additional taxes. The ticket or tickets will be emailed to your accounts address
				</div>
			</div>
		</div>

		{!! ViewHelper::formOpen(array('action' => ['EventsTicketsController@doCharge', $events['slug']])) !!}

			<div class = "payment-sect-hd">
				<div class = "row">
					<div class = "col-md-12">
							<div class = "tickets-payment-title-txt">Payment</div>
					</div>
				</div>
				<div class = "row">

					@if(Session::has('fail_message'))
					  <div class="alert alert-danger alert-txt" align="center">
					    {{ Session::get('fail_message') }}
					  </div>
					@endif

					@if (isset($updateErrors))
				    <div class="alert alert-warning" align="center">
				      @foreach ($updateErrors as $error)
				        {{ $error }}<br>
				      @endforeach
				    </div>
				  @endif

					<div class = "col-md-6 col-sm-12 col-xs-12">

					    {{-- Credit Card Number Input Field --}}
					    <div class="form-group  tickets-name-fields @if ($errors->has('first_name')) has-error @endif">
				        {!! Form::label('name', 'Card Holder', ['class' => 'clearfix control-label']) !!}
				        {!! Form::text('name', Input::old('name'), ['class' => 'form-control', 'placeholder' => 'Full Name', 'required', 'tab-index' => '1']) !!}

								@if (Auth::check())
				        	{!! Form::text('userId', \Auth::user()->id, ['class' => 'hidden']) !!}
								@endif

								@if (isset($nameError))
									{{ $nameError }}
								@endif

				        {{ $errors->first('name', '<p class="help-block">:message</p>') }}
					    </div>

					    <div class="form-group tickets-name-fields @if ($errors->has('email_address')) has-error @endif">
				        {!! Form::label('email', 'Email', ['class' => 'clearfix control-label']) !!}
				        {!! Form::email('email', Input::old('email'), ['class' => 'form-control', 'placeholder' => 'Email Address', 'required', 'tab-index' => '2']) !!}
				        {!! Form::text('eventId', $events['id'], ['class' => 'hidden']) !!}
				        {{ $errors->first('email', '<p class="help-block">:message</p>') }}
					    </div>

					    <div class="form-group tickets-credit-card-fields @if ($errors->has('card_number')) has-error @endif">
	              <div class = "credit-card-placeholder">
	                <i class="fa fa-cc-visa"></i> <i class="fa fa-cc-discover"></i> <i class="fa fa-cc-mastercard"></i> <i class="fa fa-cc-amex"></i>
	              </div>
				        {!! Form::label('number', 'Card Number', ['class' => 'clearfix control-label']) !!}
				        {!! Form::text('billing[number]', null, ['class' => 'form-control', 'placeholder' => '0000-0000-0000-0000', 'required', 'tab-index' => '3']) !!}
				        {{ $errors->first('number', '<p class="help-block">:message</p>') }}
					    </div>

			      	<div class="form-group tickets-name-fields @if ($errors->has('cvv_number')) has-error @endif">
				        {!! Form::label('cvc', 'CVV', ['class' => 'clearfix control-label ']) !!}
				          <i class="fa fa-question-circle fa-lg suaray-popover" tabindex="0" role="button" data-toggle="suaray-popover" data-trigger="focus" title="CVV Code" data-content="For VISA, MasterCard and Discover, the CVV number is the last 3 digits on the Signature Panel on the back of the card."></i>

			        	{!! Form::text('billing[cvc]', null, ['class' => 'form-control ', 'placeholder' => '000', 'required', 'tab-index' => '4']) !!}
			        	{{ $errors->first('cvc', '<p class="help-block ">:message</p>') }}
			      	</div>
			      	<div class="form-group tickets-name-fields @if ($errors->has('Expiration Date')) has-error @endif">
				        {!! Form::label('month', 'Expiration Date', ['class' => 'clearfix control-label tickets-payment-expiration-title']) !!}
				        <div class = "tickets-exp-date-field">
				        {!! Form::text('billing[month]', null, ['class' => 'form-control ', 'data-date-format' => 'mm', 'placeholder' => 'MM', 'required', 'tab-index' => '5']) !!}
				        </div>
				        <div class = "tickets-exp-date-field">
				        {!! Form::text('billing[year]', null, ['class' => 'form-control ', 'data-date-format' => 'yyyy', 'placeholder' => 'YYYY', 'required', 'tab-index' => '6']) !!}
				        </div>
				        {{ $errors->first('month', '<p class="help-block ">:message</p>') }}
				    	</div>

					</div>
					<div class = "col-md-6 col-sm-12 col-xs-12">

						<div class="form-group tickets-credit-card-fields @if ($errors->has('billing_address1')) has-error @endif">
				        {!! Form::label('address', 'Billing Address', ['class' => 'clearfix control-label']) !!}
				        {!! Form::text('address', null, ['class' => 'form-control', 'placeholder' => 'Building Number / Street Name', 'required', 'tab-index' => '7']) !!}
				        {{ $errors->first('address', '<p class="help-block">:message</p>') }}
					    </div>

					      {{-- City --}}
					    <div class="form-group tickets-name-fields @if ($errors->has('billing_city')) has-error @endif">
				        {!! Form::label('city', 'City', ['class' => 'clearfix control-label']) !!}
				        {!! Form::text('city', null, ['class' => 'form-control', 'placeholder' => 'City Name', 'required', 'tab-index' => '8']) !!}
				        {{ $errors->first('city', '<p class="help-block">:message</p>') }}
					    </div>

					    {{-- State --}}
				     	<div class="form-group tickets-name-fields @if ($errors->has('billing_state')) has-error @endif">
			        	{!! Form::label('state', 'State', ['class' => 'clearfix control-label']) !!}
			        	{!! Form::select('state', $stateList, 'Select State', ['class' => 'form-control', 'placeholder' => 'Select State', 'required', 'tab-index' => '9']) !!}
			        	{{ $errors->first('state', '<p class="help-block">:message</p>') }}
				      	</div>

					      {{-- Zip Code --}}
					    <div class="form-group  tickets-name-fields @if ($errors->has('billing_zipcode')) has-error @endif">
				        {!! Form::label('zip', 'Zip Code', ['class' => 'clearfix control-label']) !!}
				        {!! Form::text('zip', null, ['class' => 'form-control', 'placeholder' => '00000', 'required', 'tab-index' => '10']) !!}

				        @if ($purchased===null)

									{!! Form::hidden('purchasedId', Input::old('purchasedId')) !!}
				        	{!! Form::hidden('amount', Input::old('cost')) !!}
				        	{!! Form::hidden('qty', Input::old('qty')) !!}

				        @else

									{!! Form::hidden('purchasedId', implode(',', $purchasedId)) !!}
									{!! Form::hidden('qty', implode(',', $qty)) !!}
									{!! Form::hidden('amount', $cost) !!}

								@endif

								{!! Form::text('totalQuantity', $many, ['class' => 'hidden']) !!}
				        {{ $errors->first('zip', '<p class="help-block">:message</p>') }}
					    </div>
					    	{!! Form::hidden('eventTimeId', $eventTimeId) !!}

					</div>
				</div>
			</div>

		<div class = "payment-sect-hd">
			<div class = "row">
				<div class = "col-md-8 col-xs-12 submit-txt">
				By clicking the "Submit Order" button, you are agreeing to the Suaray Purchase Policy and Privacy Policy. All orders are subject to credit card approval and billing address verification. Please contact customer service if you have any questions regarding your order.
				</div>
				<div class = "col-md-4 col-sm-6 col-xs-12 tickets-buy-btn">
				{!! Form::token() !!}
		      	{!! Form::submit('Place Your Order', ['class' => 'btn btn-suaray btn-suaray-positive btn-lg btn-block', 'type' => 'submit', 'name' => 'place-order']) !!}
				</div>
			</div>
		</div>

		{!! Form::close() !!}

	</div>
</div>
@stop
