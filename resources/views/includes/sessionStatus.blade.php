@if(Session::has('success_message'))
  <div class="alert alert-success" align="center">
    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
    {{ Session::get('success_message') }}
  </div>
@endif

@if(Session::has('fail_message'))
  <div class="alert alert-danger" align="center">
    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
    {{ Session::get('fail_message') }}
  </div>
@endif

@if(Session::has('warning_message'))
  <div class="alert alert-warning" align="center">
    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
    {{ Session::get('warning_message') }}
  </div>
@endif
