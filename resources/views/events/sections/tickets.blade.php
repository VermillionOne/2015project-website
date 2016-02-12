{{-- Event Ticket section --}}
<div class="section-card">
  <div class = "event-detail-tickets-col">


      @if (empty($times))
        <h2 class = "events-detail-title">No tickets Available for sale.</div>
      @else
        <h2 class = "events-detail-title">Tickets</div>

        {!! ViewHelper::formOpen(array('url' => 'events/' . $events['slug'] . '/tickets', 'name' => 'ticket-purchase', 'novalidate', 'method' => 'post', 'ng-submit' => 'submit()')) !!}

        <p class="ticket-date-select-title">Select date for ticket</p>
        <div class="select-style">

          {!! Form::select('eventTimeId', $times, 0, ['class' => 'ticket-date-select']) !!}
        </div>

        @foreach ($events['ticketsInventory'] as $ticket)

          @if ($ticket['isReservation'] === false)

          @if ($ticket['inventory'] === 0)
            {{-- List available quantities for users to choose from (Max 50 tickets) --}}
            {!! Form::hidden('null', 'Sold Out') !!}
          @else
            {!! Form::hidden('id[]', $ticket['id']) !!}
          @endif

          @if (Auth::check())
            {!! Form::hidden('userId', \Auth::user()->id) !!}
          @endif

          <div class = "events-tickets-row">
            <div class = "row">

              {{-- Wrapper to hold Ticket quantity dropdown --}}
              <div class = "col-md-2 col-xs-12">
                <div class = "ticket-select-sect">
                  <div class = "select-style">

                    @if ($ticket['inventory'] === 0)
                      {{-- List available quantities for users to choose from (Max 50 tickets) --}}
                      {!! Form::text('null', 'Sold Out', ['disabled' => 'disabled']) !!}
                    @else
                      {{-- List available quantities for users to choose from (Max 50 tickets) --}}
                      {!! Form::select('qty[]', range(0, $ticket['inventory'] > 40 ? 40 : $ticket['inventory'])) !!}
                    @endif

                  </div>
                </div>
              </div>
              {{-- Ticket Price and checkbox --}}
              <div class = "col-md-2 col-xs-12 pull-right">

                {{-- Ticket Price --}}
                <div class = "ticket-type-price">
                  $ {{ $ticket['amount'] }}
                </div>

              </div>
              {{-- Ticket Title and description --}}
              <div class = "col-md-8 col-xs-12">

                {{-- Ticket Type --}}
                <div class = "ticket-type-title">
                  <h4>{{ $ticket['name'] }}</h4>
                </div>

                {{-- Ticket Title --}}
                <div class = "ticket-type-desc">
                  {{ $ticket['description'] }}
                </div>

              </div>

            </div>
          </div>
          @endif
        @endforeach

        {{-- Ticket checkout area --}}
        <div class = "events-ticket-type">
          <div class = "row">
            @if ($ticket['inventory'] === 0)
              <div class="alert alert-warning">
                <div class="events-detail-title">
                  SOLD OUT
                </div>
              </div>
            @else
              {{-- Ticket Information buy notice --}}
              <div class = "col-md-9 ticket-type-desc">
                Select the ticket you want to buy and then click 'Buy Tickets'
              </div>

              {{-- Buy button --}}
              <div class = "col-md-3 tickets-buy-btn">
                {!! Form::submit('Buy Tickets', ['class' => 'btn btn-suaray btn-suaray-positive btn-block']) !!}
              </div>
            @endif

          </div>
        </div>

        {!! Form::close() !!}
      @endif
















































































  </div>
