<?php if (!isset($media)) $media = 'create_event_media'; ?>
<?php if (!isset($mediaLocked)) $mediaLocked = true; ?>

<div class="create-event-form-box">

  <div class="row">

    @if (isset($event) && Request::url('/events/' . $event['slug'] . '/updatimages'))

      {{-- BEGIN upload media section --}}
      <h3 class="col-sm-12 create-event-form-main-title">Upload Media for {{$event['title']}}</h3>

    @else

    {{-- BEGIN upload media section --}}
    <h3 class="col-sm-12 create-event-form-main-title">Media for @{{event.title}}</h3>
    <h4 class="col-sm-12 create-event-form-sub-title">Upload Media for Featured Photo and Gallery Images.</h4>

    @endif

    <div class="input-group col-sm-10 col-sm-offset-1 media-upload">
      <i class="fa fa-question-circle fa-lg suaray-popover" tabindex="0" role="button" data-toggle="suaray-popover" data-trigger="focus" title="Event Title" data-content="These images will be in your event gallery, and can be used for your events featured photo which will be displayed at the top of your event page, and elsewhere in the site such as search and your 'My Events' page."></i>
      {!! Form::label('eventPhoto', 'Choose Media For Your Event', ['class' => 'clearfix control-label']) !!}<br>

      <span class="btn btn-suaray btn-suaray-primary btn-lg">

        @if (\Auth::check())
          <input type="file" data-role="files" data-slug="@{{event.title}}" data-user-id="{{ \Auth::user()->id }}" files-bind name="files" multiple required/>
        @endif

      </span>
    </div>

    {{-- Holder for the Panel --}}
    <div class="col-sm-10 col-sm-offset-1">

      <div class="panel create-event-panel" data-role="show-info-container">

        {{-- Panel Heading Container --}}
        <div class="panel-heading flat">

          {{-- Panel Header --}}
          <p class="panel-title upload-complete" >Files</p>

        </div>

        {{-- Upload Media Form Content --}}
        <div >

          {{-- Div Row for containing Panel Content - media thumbnails--}}
          <div class="row panel-content" >

            {{-- This Div to repeat as images are selected --}}
            <div class="row upload-file-container" ng-repeat="(index, file) in files">

              {{-- The Filename and icon representing filetype --}}
              <div class="col-sm-6 col-sm-offset-1" >
                <p class="create-event-summary-element" ng-model="files"><span class="fa fa-picture-o fa-lg"></span>&nbsp;@{{file.name}}</p>
              </div>

              <div class="col-sm-4">

                {{-- The progress bar will disappear after the file is done uploading --}}
                <div class="progress" ng-data-role="progress-@{{index}}" style="">
                  <div class="progress-bar progress-bar-striped active" ng-data-role="progress-bar-@{{index}}" role="progressbar" ></div>
                </div>

                {{-- This line will appear after file upload is complete --}}
                <span class="fa fa-check fa-lg complete-file-upload" ng-data-role="upload-sucessful-@{{index}}"></span>

              </div>

            </div>

          </div>

        </div>

      </div>

    </div>

    {{-- Video Upload for premium events --}}
    <div class="input-group col-sm-10 col-sm-offset-1 media-upload">
      <i class="fa fa-question-circle fa-lg suaray-popover" tabindex="0" role="button" data-toggle="suaray-popover" data-trigger="focus" title="Event Title" data-content="These videos will be viewable on your event's page just below the description. This is a great to share more about your event in an exciting way!"></i>

      {!! Form::label('eventPhoto', ' Insert video codes', ['class' => 'clearfix control-label']) !!}<br>

      @if (\Auth::check())
        <div class="create-event-youtube-video-code">
          <img class="" src="{{ ViewHelper::asset('assets/img/YouTube-logo-full_color.png') }}"  alt="YouTube Video Code Input" />
          {!! Form::text('youtubeIds', Input::old('youtubeVideoCode'), ['class' => 'form-control ng-dirty ', 'data-role' => 'youtube-video-code']) !!}
          <button type="button" name="button" class="btn btn-suaray btn-suaray-positive add-video-button" ng-click="addVideo('youtube')" ng-hide="youtubeCodes !== ''"><span class="fa fa-plus"></span>Add</button>
          <button type="button" name="button" class="btn btn-suaray btn-suaray-positive add-video-button" ng-click="addVideo('youtube')" ng-show="youtubeCodes !== ''"><span class="fa fa-refresh"></span>Refresh</button>
        </div>
      @endif

      @if (\Auth::check())
        <div class="create-event-vimeo-video-code">
          <img class="" src="{{ ViewHelper::asset('assets/img/vimeo_logo_blue.png') }}" alt="Vimeo Video Code Input" />
          {!! Form::text('vimeoIds', Input::old('vimeoVideoCode'), ['class' => 'form-control ng-dirty col-md-6', 'data-role' => 'vimeo-video-code']) !!}
          <button type="button" name="button" class="btn btn-suaray btn-suaray-positive add-video-button" ng-click="addVideo('vimeo')" ng-hide="vimeoCodes !== ''"><span class="fa fa-plus"></span>Add</button>
          <button type="button" name="button" class="btn btn-suaray btn-suaray-positive add-video-button" ng-click="addVideo('vimeo')" ng-show="vimeoCodes !== ''"><span class="fa fa-refresh"></span>Refresh</button>
        </div>
      @endif

    </div>

    {{-- Holder for the Panel --}}
    <div class="col-sm-10 col-sm-offset-1">

      <div class="panel create-event-panel" data-role="show-info-container">

        {{-- Panel Heading Container --}}
        <div class="panel-heading flat">

          {{-- Panel Header --}}
          <p class="panel-title upload-complete">Videos</p>

        </div>

        {{-- Upload Media Form Content --}}
        <div>

          {{-- Div Row for containing Panel Content - media thumbnails--}}
          <div class="row panel-content" >

            <div class="col-sm-6">
              <p class="video-holder youtube" data-role="youtube-embed-video-preview">

              </p>
            </div>

            <div class="col-sm-6">
              <p class="video-holder vimeo" data-role="vimeo-embed-video-preview">

              </p>
            </div>

          </div>

        </div>

      </div>

    </div>

    {{-- 'NEXT >' Button to continue along form --}}
    <div class="form-group text-right">
      <div class="col-sm-10 col-sm-offset-1">

        @if (isset($event) && Request::url('/events/' . $event['slug'] . '/updatimages'))
          {!! ViewHelper::formOpen(['route' => ['events.updates', $event['id']]]) !!}
            {!! Form::hidden('newImage', 'yes') !!}
            <button class="btn btn-suaray btn-suaray-primary btn-lg pull-right">Save Images</button>
          {!! Form::close() !!}
        @else
          <button class="btn btn-suaray btn-suaray-primary btn-lg pull-right timezone-val" ng-click="openSummary()">Next <span class="glyphicon glyphicon-chevron-right"></span></button>
        @endif

      </div>

    </div>

  </div>

</div>
