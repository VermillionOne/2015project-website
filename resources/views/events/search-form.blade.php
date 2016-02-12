<div class="container">

  {{-- Search form --}}
  {!! ViewHelper::formOpen(['route' => 'events.search', 'method' => 'GET']) !!}

    {{-- Search --}}
    <div id="mid-row">
      <div class="row">
        <div class="col-md-12">
          <div id="browse-title-big">Discover Events</div>
        </div>
      </div>
      <div class="row">
        <div class="col-md-12 input-group-lg">

          {{-- Main filter input --}}
          <input type="text" class="form-control" placeholder="What’re you looking for?" aria-describedby="basic-addon2" name="q" maxlength="74" value="{{ \Input::get('q') }}">

        </div>
      </div>
    </div>

    {{-- Filters: date range --}}
    <div class="row">

      <div class="event-search-field col-md-3 inner-addon right-addon input-group-lg">
        <i class="glyphicon glyphicon-calendar"></i>

        {{-- Start date filter --}}
        <input type="text" class="form-control datepicker" data-role="date-picker" placeholder="Date From" id="datepickerfrom" name="startDate"  value="{{ \Input::get('startDate') }}"/>

      </div>

      <div class="event-search-field col-md-3 inner-addon right-addon input-group-lg">
        <i class="glyphicon glyphicon-calendar"></i>

        {{-- End date filter --}}
        <input type="text" class="form-control datepicker" data-role="date-picker" placeholder="Date To" id="datepickerto" name="endDate"  value="{{ \Input::get('endDate') }}"/>

      </div>

      <div class="event-search-field col-md-3 inner-addon right-addon input-group-lg">
        <i class="glyphicon glyphicon-map-marker"></i>

        {{-- Location filter --}}
        <input type="text" class="form-control" placeholder="City" name="city"  value="{{ \Input::get('city') }}"/>
      </div>

      <div class="event-search-field col-md-3 col-sm-3 col-xs-12 inner-addon right-addon">

        <div class="age-select-dropdown">

          {{-- User clicks checkbox to display age appropriate options --}}
          <input type="checkbox" class="age-select"/>

          <label class="age-select">Age Group
            <span class="caret age-caret"></span>
          </label>

          {{-- Begin dropdown checkbox age options --}}
          <section class="age-select">

            <div class="age-select">
              <input type="checkbox" name="isAge13" value="1" class="age-select-checkbox" />
              <label class="age-select radGroup1">13 +</label>
            </div>

            <div class="age-select">
              <input type="checkbox" name="isAge16" value="1" class="age-select-checkbox" />
              <label class="age-select radGroup1">16 +</label>
            </div>

            <div class="age-select">
              <input type="checkbox" name="isAge18" value="1" class="age-select-checkbox" />
              <label class="age-select radGroup1">18 +</label>
            </div>

            <div class="age-select">
              <input type="checkbox" name="isAdult" value="1" id="checkboxG4" class="age-select-checkbox" />
              <label for="checkboxG4" class="age-select radGroup1">21 +</label>
            </div>

            <div class="age-select">
              <input type="checkbox" name="isAge0" value="1" class="age-select-checkbox" />
              <label class="age-select radGroup1">All Ages</label>
            </div>

          </section>

        </div>

      </div>

    </div>

    {{-- Filters: checkboxes --}}
    <div id="bottom-row">

      <div class="col-md-9 col-sm-9 col-xs-7">

        <div class="search-results-check">
          <input type="checkbox" name="isOutdoor" value="1" id="checkboxG3" class="css-checkbox" />
          <label for="checkboxG3" class="css-label radGroup1">Outdoor</label>
        </div>

        <div class="search-results-check">
          <input type="checkbox" name="isIndoor" value="1" id="checkboxG2" class="css-checkbox" />
          <label for="checkboxG2" class="css-label radGroup1">Indoor</label>
        </div>

      </div>

      <div class="col-md-3 col-sm-3 col-xs-5 search-btn-mobile">
        {!! Form::submit('Search', ['class' => 'btn btn-suaray btn-suaray-positive btn-lg btn-block', 'type' => 'submit']) !!}

      </div>

    </div>

  {!! Form::close() !!}

</div>
