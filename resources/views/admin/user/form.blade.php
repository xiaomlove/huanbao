@extends('layouts.admin')

@section('title', $user->id ? '编辑用户' : '新建用户')

@section('content_header')
  <h2>

  </h2>
@stop

@section('content')
  @include('admin.common.message')
  @if($user->id)
    <form id="form" class="subject-form image-upload-form form-horizontal" method="post"
          action="{{ route('admin.user.update', $user->id) }}">
    {{ method_field('PATCH') }}
  @else
    <form id="form" class="subject-form image-upload-form form-horizontal" method="post"
          action="{{ route('admin.user.store') }}">
  @endif
      {{ csrf_field() }}
      <div class="form-group{{$errors->has('email') ? ' has-error' : ''}}">
        <label for="" class="col-sm-2 control-label">邮箱</label>
        <div class="col-sm-10">
          <input type="text" name="email" class="form-control" id="" placeholder=""
                 value="{{ old('email', $user->email) }}">
          @if($errors->has('email'))
            <small class="help-block">{{ $errors->first('email') }}</small>
          @endif
        </div>
      </div>

    <div class="form-group{{$errors->has('password') ? ' has-error' : ''}}">
        <label for="" class="col-sm-2 control-label">密码</label>
        <div class="col-sm-10">
            <input type="text" name="password" class="form-control" id="" placeholder=""
                   value="{{ old('password', $user->password) }}">
            @if($errors->has('password'))
                <small class="help-block">{{ $errors->first('password') }}</small>
            @endif
        </div>
    </div>

    <div class="form-group{{$errors->has('password_confirmation') ? ' has-error' : ''}}">
        <label for="" class="col-sm-2 control-label">确认密码</label>
        <div class="col-sm-10">
            <input type="text" name="password_confirmation" class="form-control" id="" placeholder=""
                   value="{{ old('password_confirmation', $user->password_confirmation) }}">
            @if($errors->has('password_confirmation'))
                <small class="help-block">{{ $errors->first('password_confirmation') }}</small>
            @endif
        </div>
    </div>

      <div class="form-group">
        <div class="col-sm-offset-2 col-sm-10 text-center">
          <a class="btn btn-primary submit">提交</a>
        </div>
      </div>
    </form>
@stop

@section('js')
  <script src="{{ asset('js/text_modal.js') }}"></script>
  <script src="{{ asset('js/image_modal.js') }}"></script>
  <script src="{{ asset('js/content_editor.js') }}"></script>

  <script>
      var tid = '{{ $user->id }}';
      $('#forum').select2();
      var contentEditor = new ContentEditor({
          wrapId: "content",
          uploadUrl: "{{ route('upload.image') }}",
          content: '{!! $user->main_floor ? $user->main_floor->detail->content : "" !!}'
      });

      var $form = $('#form');
      $form.on("click", ".submit", function() {
          var data = $form.serialize();
          data += "&content=" + JSON.stringify(contentEditor.getData());
          $.ajax({
              url: $form.attr("action"),
              type: "post",
              dataType: "json",
              data: data,
          }).done(function(response) {
              console.log(response);
              alert(response.msg);
              if (response.ret == 0 && !tid) {
                  location.href = "{{ route('admin.user.index') }}";
              }
          }).fail(function(xhr, errstr, errThrown) {
              var response = xhr.responseJSON;
              var msg = "";
              for (var i in response.data.errors) {
                  msg += response.data.errors[i][0] + "\n";
              }
              alert(msg);
          }).always(function() {

          })
      })

  </script>
@stop
