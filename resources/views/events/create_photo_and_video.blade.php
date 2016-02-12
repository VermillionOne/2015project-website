@extends('layouts.master')

@section('content')
<div class="container pb30 view-event-create_photo" data-ng-controller="EventsCreatePhotoController" data-id="{{ $event['id'] }}" data-photo-submit="{{ action("EventsPhotosController@store", ['id' => $event['id']]) }}">
  @include('events.create.step')
  <hr class="mt0">
  <div>
    <div>
      <span>Pending uploads: @{{ uploader.queue.length }}</span>
      <div class="pull-right">
        <div class="btn btn-success btn-file">
          <input type="file" multiple nv-file-select uploader="uploader"/>
          <small class="glyphicon glyphicon-plus"></small> Add Photos
        </div>
        <button type="button" class="btn btn-success btn-s" ng-click="uploader.uploadAll()" ng-disabled="!uploader.getNotUploadedItems().length">
          <span class="glyphicon glyphicon-upload"></span> Upload all
        </button>
        <button type="button" class="btn btn-warning btn-s" ng-click="uploader.cancelAll()" ng-disabled="!uploader.isUploading">
          <span class="glyphicon glyphicon-ban-circle"></span> Cancel all
        </button>
        <!--button type="button" class="btn btn-danger btn-s" ng-click="uploader.clearQueue()" ng-disabled="!uploader.queue.length">
          <span class="glyphicon glyphicon-trash"></span> Remove all
        </button-->
      </div>
      <div class="mt20">
        <small>Queue progress:</small>
        <div class="progress" style="">
          <div class="progress-bar" role="progressbar" ng-style="{ 'width': uploader.progress + '%' }"></div>
        </div>
      </div>
    </div>
    <div class="mb40">
      <div class="well well-sm photo-container" nv-file-over="" nv-file-drop="" uploader="uploader">
      <table class="table">
          <tbody>
            <tr ng-repeat="photo in photos">
              <td class="text-center">
                <img class="thumbnail" height="100" ng-src="@{{photo.md}}" />
              </td>
              <td class="text-right" nowrap>
                <button type="button" class="btn btn-danger btn-xs" ng-click="item.remove()">
                  <span class="glyphicon glyphicon-trash"></span> Remove
                </button>
              </td>
            </tr>
            <tr ng-repeat="item in uploader.queue">
              <td>
                <div ng-show="uploader.isHTML5" ng-thumb="{ file: item._file, height: 100 }"></div>
                <div class="text-center">
                  <small>
                    <span ng-show="item.isSuccess"><i class="glyphicon glyphicon-ok"></i></span>
                    <span ng-show="item.isCancel"><i class="glyphicon glyphicon-ban-circle"></i></span>
                    <span ng-show="item.isError"><i class="glyphicon glyphicon-remove"></i></span>
                    @{{ item.file.size/1024/1024|number:2 }} MB
                  </small>
                  <div class="progress mb0">
                    <div class="progress-bar" role="progressbar" ng-style="{ 'width': item.progress + '%' }"></div>
                  </div>
                </div>
              </td>
              <td class="text-right" nowrap>
                <button type="button" class="btn btn-success btn-xs" ng-click="item.upload()" ng-disabled="item.isReady || item.isUploading || item.isSuccess">
                  <span class="glyphicon glyphicon-upload"></span> Upload
                </button>
                <button type="button" class="btn btn-warning btn-xs" ng-click="item.cancel()" ng-disabled="!item.isUploading">
                  <span class="glyphicon glyphicon-ban-circle"></span> Cancel
                </button>
                <button type="button" class="btn btn-danger btn-xs" ng-click="item.remove()">
                  <span class="glyphicon glyphicon-trash"></span> Remove
                </button>
              </td>
            </tr>
          </tbody>
        </table>
        @if(empty($photos))
          <h3 class="text-center text-muted" ng-show="!photos.length && !uploader.queue.length">No photos yet</h3>
        @else
          @foreach( $photos as $photo)
            <div class="col-md-4">
              <img src="{{ $photo['url']['318x190'] }}" width="100%"/>
            </div>
          @endforeach
        @endif

      </div>
    </div>
  </div>
  <hr>
  <div class="text-right">
    <a href="{{ action('EventsController@showCreateFinal', ['id' => $event['id']]) }}" class="btn btn-primary">Next <span class="glyphicon glyphicon-chevron-right"></span></a>
  </div>
</div>
@stop
