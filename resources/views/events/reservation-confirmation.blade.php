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

    </div>

    <div class="row">

      <div class="col-md-9">
        <div class="tickets-sect-hd">
          @include('events.sections.carousel')
        </div>
      </div>

      {{-- Order details left blank to conform to styling of ticket confirmation but holds no value for reservation --}}
      <div class="col-md-3">

        <div class="confirmation-sect-hd">

          <div class="tickets-title-sect">
            <div class="tickets-title-txt">Order Details</div>
          </div>

          <div class="tickets-confirmation-sect">
            <div class="tickets-confirmation-title">Tickets</div>
            <div class="tickets-confirmation-number">$ 0</div>
          </div>

          <div class="tickets-order-sect">
            <div class="tickets-confirmation-title-total">Total</div>
            <div class="tickets-confirmation-number-total">$ 0</div>
          </div>

        </div>

        <div class="confirmation-sect-hd">
          <div class="tickets-title-sect">
            <div class="widgets-title-txt">Delivery Method</div>
            <div class="tickets-confirmation-desc">Instant eDelivery<br/> you will recieve your tickets electornically to your email</div>
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
            <div class="confimation-title-txt">Reservation Confirmation</div>
          </div>

          <div class="confirmation-congrats">Congratulations your reservation request has been sent</div>

          <div class="purchase-conf-txt">

            {{-- For each reservation that has been made, the confirmation message and title of reservation will show --}}
            @foreach ($reservationConfirmation as $reserve)

              <div class="reservation-widgets-sect">
                <div class="confimation-widets-title-txt">{{$reserve['name']}}</div>
                <div class="confimation-widets-txt">
                  {{ $reserve['confirmation'] }}
                </div>
              </div>

            @endforeach

          </div>

        </div>

      </div>
    </div>

  </div>
</div>
@stop
