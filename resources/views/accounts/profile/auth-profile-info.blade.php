        <div class = "profile-info">

          {{-- Users name name and profile image location --}}
          <div class="profile-title-name " >{{ $user['firstName'] }} {{ $user['lastName'] }}</div>

          {{-- Begin a form to upload a profile image --}}
          {!! ViewHelper::formModel($user, ['url' => action('AccountsController@updateProfilePhoto'), 'file' => true]) !!}

          <div class="" >

            {{-- Users profile image --}}
            <div class="profile-round">

              @if (empty($user['avatar']))
                <img src="{{ ViewHelper::asset('assets/img/missing-profile-pic.jpg') }}">
              @else
                <img src="{{ $user['avatar'] }}">
              @endif

              <div class="profile-image-upload" >
                <span class="fa fa-camera" ng-click="profileFileUpload()"><br><br>Edit Profile Image</span>

                @if (\Auth::check())
                  <input data-user-id="{{ \Auth::user()->id }}" data-role="files" files-bind type="file">
                @endif
              </div>

              <span class="fa fa-refresh fa-spin avatar-updating" ng-show="avatarPrep === true"></span>

            </div>

            <button type="submit" data-role="submit-avatar-image" ng-click="isUpdated({{ \Auth::user()->id }})" class="hidden"></button>

          </div>
          {!! Form::token() !!}
          {!! Form::close() !!}

          {!! ViewHelper::formModel($user, ['url' => action('AccountsController@doAccountSettings'), 'class' => 'form-horizontal']) !!}

          {{-- name sure a token is always passed with a form submit --}}

          {{-- Begin a form to update the users general information --}}

            {{-- Input for First Name --}}
            <div class="col-xs-12">

              <div class="form-group @if ($errors->has('firstName')) has-error @endif">
                {!! Form::text('firstName', null, ['class' => 'form-control profile-name', 'maxlength' => '30', 'placeholder' => 'First Name', 'data-toggle' => "tooltip", 'data-placement' => "left", 'title' => 'First Name']) !!}

                {{-- Hidden input for the signed in users user ID. Will be used to pass to the apihelper for update --}}
                {!! Form::hidden('id', $user['id']) !!}

                @if(Session::has('first_name'))
                  <p class="edit-tickets-warn-text">{{ Session::get('first_name') }}</p>
                @endif

              </div>

              {{-- Input for Last Name --}}
              <div class="form-group @if ($errors->has('lastName')) has-error @endif">
                {!! Form::text('lastName', null, ['class' => 'form-control profile-name', 'maxlength' => '50', 'placeholder' => 'Last Name', 'placeholder' => 'Last Name', 'data-toggle' => 'tooltip', 'title' => 'Last Name', 'data-placement' => 'left']) !!}
                @if(Session::has('last_name'))
                  <p class="edit-tickets-warn-text">{{ Session::get('last_name') }}</p>
                @endif
              </div>

              {{-- Input for Email --}}
              <div class="form-group @if ($errors->has('email')) has-error @endif">
                {!! Form::text('email', null, [ 'class' => 'form-control ', 'maxlength' => '100', 'placeholder' => 'example@email.com', 'data-toggle' => 'tooltip', 'title' => 'Email', 'data-placement' => 'left']) !!}
                @if(Session::has('first_name'))
                  <p class="edit-tickets-warn-text">{{ Session::get('first_name') }}</p>
                @endif
              </div>

              <div class="form-group @if ($errors->has('username')) has-error @endif">
                {!! Form::text('username', null, [ 'class' => 'form-control ', 'maxlength' => '50', 'placeholder' => 'username', 'placeholder' => 'Username', 'data-toggle' => 'tooltip', 'title' => 'Username', 'data-placement' => 'left']) !!}
                @if(Session::has('username'))
                  <p class="edit-tickets-warn-text">{{ Session::get('username') }}</p>
                @endif
              </div>

            </div>

            <p>Account Privacy</p>

            <div class="btn-group btn-group-justified" data-toggle="buttons">

              {{-- Option Off Button --}}
              <label class="btn btn-suaray btn-suaray-discreet create-event-option-on">
                {!! Form::radio('isPrivate', true, false, ['autocomplete' => 'off']) !!} Private
              </label>


              {{-- Option On Button --}}
              <label class="btn btn-suaray btn-suaray-discreet create-event-option-on">
                {!! Form::radio('isPrivate', 0, false, ['autocomplete' => 'off']) !!} Public
              </label>

            </div>
            <hr>

            <button class="btn btn-suaray btn-suaray-primary btn-block" name="save" type="submit">&nbsp;<span class="glyphicon glyphicon-floppy-disk"></span>&nbsp;Save</button>

          {{-- token is always passed with a form --}}
          {!! Form::token() !!}

          {{-- close that form --}}
          {!! Form::close() !!}

      </div>
