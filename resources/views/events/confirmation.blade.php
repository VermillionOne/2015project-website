@extends('layouts.master')

@section('javascript-includes')
  <script src="{{ ViewHelper::asset('assets/dist/lib-events-show.js') }}"></script>
@stop

@section('content')

<div class="tickets-sect">
	<div class="container">

    <div class="search-results-title">

    	{{-- Takes user back to event detail page --}}
    	<a class="manage-link" href="{{ url('events') }}/{{ $events['slug'] }}">
      	<span class="confirmation-back-txt"><i class="fa fa-angle-left"></i>&nbsp;&nbsp;Back To {{ $events['title'] }}</span>
      </a>

      {{-- If user logged in, will show my tickets link--}}
      @if (Auth::check())

	      <a class="manage-link" href="{{ route('my-tickets') }}">
	      	<span class="confirmation-my-ticket-txt">View My Tickets&nbsp;&nbsp;<i class="fa fa-angle-right"></i></span>
	      </a>

	    @endif

    </div>

		<div class="row">
			<div class="col-md-9">
				<div class="tickets-sect-hd">
					@include('events.sections.carousel')
				</div>
			</div>

			<div class="col-md-3">
				<div class="confirmation-sect-hd">
					<div class="tickets-title-sect">
						<div class="tickets-title-txt">Order Details</div>
					</div>
					<div class="tickets-confirmation-sect">
						<div class="tickets-confirmation-title">Tickets</div>
						<div class="tickets-confirmation-number">${{ number_format($amount, 2) }}</div>
					</div>
					<div class="tickets-order-sect">
						<div class="tickets-confirmation-title-total">Total</div>
						<div class="tickets-confirmation-number-total">$ {{ number_format($amount, 2) }}</div>
					</div>
				</div>

				<div class="confirmation-sect-hd">
					<div class="tickets-title-sect">
						<div class="widgets-title-txt">Delivery Method</div>
						<div class="tickets-confirmation-desc">Instant eDelivery<br/> you will receive your tickets electronically via email</div>
					</div>
				</div>
				<div class="confirmation-sect-hd">
					<div class="tickets-title-sect">
						<div class="widgets-title-txt">Confirmation ID</div>
						<div class="tickets-confirmation-desc">123456789abcd</div>
					</div>
				</div>
			</div>

		</div>
		<div class="row">
			<div class="col-xs-12">
				<div class="confirmation-sect-hd">
					<div class="tickets-title-sect">
						<div class="confimation-title-txt">Purchase Confirmation</div>
					</div>
					<div class="confirmation-congrats">Congratulations, your order has been placed</div>
					<div class="purchase-conf-txt">
					What happens next? Suaray will send you an email with a QR code to confirm your purchase.
					<br/><br/>
					At this time, we have charged your card and your sale is final.
					<br/><br/>
					Have a great time at your event and thank you for using Suaray.
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-6 col-xs-12">
				<div class="confirmation-sect-hd">
					<div class="confirmation-widgets-sect">
						<div class="confimation-widets-title-txt">Location</div>
						<div class="confimation-widets-txt">
							{{ $events['address1'] }}<br>
							{{ $events['city'] }}, {{ $events['state'] }} {{ $events['zipcode'] }}
						</div>
					</div>
				</div>
			</div>
			<div class="col-md-6 col-xs-12">
				<div class="confirmation-sect-hd">
					<div class="confirmation-widgets-sect">
						<div class="confimation-widets-title-txt">ticket details</div>
						<div class="confimation-widets-txt">
							{{ $events['title'] }}
							<br/>Quantity: {{ $quantity }} Tickets
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@stop
