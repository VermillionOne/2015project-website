<div class="security-settings setting-well col-md-12">

  <h3 class="setting-title"> Billing Settings </h3>

  <div class="row">
    <div class="col-sm-12 form-group">

      {!! ViewHelper::formModel($meta, ['route' => ['billing.update', $user['id']], 'class' => 'form-horizontal']) !!}
        <div class="col-sm-12 form-group">
          <label class="control-label">YOUR NAME</label><span>&nbsp;&nbsp;Your first and last name</span>
        </div>

        <div class="col-sm-6 form-group">
          {!! Form::text('cchfirstname', Input::old('cchfirstname'), ['class' => 'form-control', 'placeholder' => 'David']) !!}
        </div>

        <div class="col-sm-6 form-group">
          {!! Form::text('cchlastname', Input::old('cchlastname'), ['class' => 'form-control', 'placeholder' => 'Albright']) !!}
        </div>

        <div class="col-sm-12 form-group">
          <label class="control-label">CARD NUMBER</label>
          <span>&nbsp;&nbsp;The 16 digit number on your card</span>
        </div>

        <div class="col-sm-5 form-group">
          {!! Form::text('ccnumber', Input::old('ccnumber'), ['class' => 'form-control', 'placeholder' => '4929 2229 0803 1230']) !!}
        </div>

        <div class = "setting-cc-icons">
          <img class = "settings-cc-pics" src="{{ ViewHelper::asset('assets/img/visa-logo-alt.png') }}" />
        </div>
        <div class = "setting-cc-icons">
          <img class = "settings-cc-pics"  src="{{ ViewHelper::asset('assets/img/amex-logo-alt.png') }}" />
        </div>
        <div class = "setting-cc-icons">
          <img class = "settings-cc-pics" src="{{ ViewHelper::asset('assets/img/master-logo-alt.png') }}" />
        </div>
        <div class = "setting-cc-icons">
          <img class = "settings-cc-pics"  src="{{ ViewHelper::asset('assets/img/discover-logo-alt.png') }}" />
        </div>

        <div class="col-sm-12 form-group">
          <label class="control-label">CVV</label>
          <span>&nbsp;&nbsp;The 3 digit security code on your card</span>
        </div>

        <div class="col-sm-3 form-group">
          {!! Form::text('ccv', Input::old('ccv'), ['class' => 'form-control', 'placeholder' => '943']) !!}
        </div>

        <div class="col-sm-12 form-group">
          <label class="control-label">EXPIRATION DATE</label>
        </div>

        <div class="col-sm-3 form-group">
          {!! Form::text('ccmonth', Input::old('ccmonth'), ['class' => 'form-control', 'placeholder' => 'Month']) !!}
        </div>

        <div class="col-sm-3 form-group">
          {!! Form::text('ccmonth', Input::old('ccmonth'), ['class' => 'form-control', 'placeholder' => 'YEAR']) !!}
        </div>

        <button class="btn btn-suaray btn-suaray-primary pull-right" name="save-billing">Save Changes</button>

      {!! Form::token() !!}

      {!! Form::close() !!}
    </div>
  </div>
</div>
