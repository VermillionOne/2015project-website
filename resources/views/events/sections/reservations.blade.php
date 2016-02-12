{{-- Event Ticket section --}}
<div class="section-card">
  <div class = "event-detail-tickets-col">
    <h2 class = "events-detail-title">Reservations</div>

    {!! ViewHelper::formOpen(['route' => ['events.create.reservation', 'id' => $events['id']], 'name' => 'ticket-purchase', 'method' => 'post']) !!}

      <p class="ticket-date-select-title">Select date for reservation</p>

      <div class="select-style">

        {!! Form::select('eventTimeId', $times, 0, ['class' => 'ticket-date-select']) !!}

      </div>

    @foreach ($events['ticketsInventory'] as $index => $ticket)

      {{-- Check to see if there are any reservation style tickets --}}
      @if ($ticket['isReservation'] === true)

        {!! Form::hidden('reservation_id[]', $ticket['id']) !!}

        @if (Auth::check())
          {!! Form::hidden('userId', \Auth::user()->id) !!}
        @endif

        @if (!Auth::check() && $index === 0)
          <br>

            <p class="ticket-date-select-title">Enter your email address</p>
            <input type="email" class="form-control" style="width: 33%;" required name="email">

        @endif

        <div class = "events-tickets-row">
          <div class = "row">

            {{-- Reservation Title and description --}}
            <div class = "col-md-12">

              {{-- Reservation Type --}}
              <div class = "ticket-type-title">
                <h4>{{ $ticket['name'] }}</h4>
              </div>

              {{-- Reservation Description --}}
              <div class = "ticket-type-desc">
                {{ $ticket['description'] }}

                {{-- Textarea to enter user information for reservation --}}
                <div class="reserve-input">

                  {{-- Start Date Picker --}}
                  <div class="inner-addon right-addon">
                    {!! Form::textarea('reservation_request[]', null , ['class' => 'form-control', 'size' => '35x2']) !!}
                  </div>

                </div>

              </div>

            </div>

          </div>
        </div>

    @endif

    @endforeach

    {{-- Ticket checkout area --}}
    <div class = "events-ticket-type">
      <div class = "row">

        {{-- Ticket Information buy notice --}}
        <div class = "col-md-9 ticket-type-desc">
          Select the reservation you want and then click 'Reserve'
        </div>

        {{-- Buy button --}}
        <div class = "col-md-3 tickets-buy-btn">
          {!! Form::submit('Reserve', ['class' => 'btn btn-suaray btn-suaray-positive btn-block', 'name' => 'reserve']) !!}
        </div>

      </div>
    </div>

    {!! Form::close() !!}
  </div>
