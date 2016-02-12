<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h3 class="modal-title" id="myModalLabel">{{ $events['title'] }}</h3>
      </div>
      <div class="modal-body">
      <h4>How Many Tickets would you like?</h4>
      @foreach ($events['ticketsInventory'] as $ticket)
    {!! Form::text('id[]', $ticket['id'], ['class' => 'hidden']) !!}
    <div class = "events-tickets-row">
      <div class = "row">

        {{-- Wrapper to hold Ticket quantity dropdown --}}
        <div class = "col-md-2">
          <div class = "ticket-select-sect">
            <div class = "select-style">

              {{-- List available quantities for users to choose from --}}
              <select name="qty[]">
                @for ($n = 0; $n <= $ticket['inventory']; $n++)
                  <option ng-model>{{ $n }}</option>
                @endfor
              </select>

            </div>
          </div>
        </div>

        {{-- Ticket Title and description --}}
        <div class = "col-md-8">

          {{-- Ticket Type --}}
          <div class = "ticket-type-title">
            <h4>{{ $ticket['name'] }}</h4>
          </div>

          {{-- Ticket Title --}}
          <div class = "ticket-type-desc">
            {{ $ticket['description'] }}
          </div>

        </div>

        {{-- Ticket Price and checkbox --}}
        <div class = "col-md-2">

          {{-- Ticket Price --}}
          <div class = "ticket-type-price">
            $ {{ $ticket['amount'] }}
          </div>

        </div>

      </div>
    </div>
  @endforeach
      </div>
      <div class="modal-footer row">
        {{-- Ticket Information buy notice --}}
        <div class = "col-md-9 ticket-type-desc">
          Select the ticket you want to buy and then click 'Buy Tickets'
        </div>
       {{-- Buy button --}}
        <div class = "col-md-3 tickets-buy-btn">
          {{-- <a href = "{{ $events['slug'] }}/tickets"><button class="btn btn-success btn-block">Buy Tickets</button></a> --}}
          {!! Form::submit('Buy Tickets', ['class' => 'btn btn-suaray btn-suaray-positive btn-block']) !!}
        </div>
      </div>
    </div>
  </div>
</div>
