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
    @if($user->id)
    <div class="form-group{{$errors->has('name') ? ' has-error' : ''}}">
        <label for="" class="col-sm-2 control-label">昵称</label>
        <div class="col-sm-10">
            <input type="text" name="name" class="form-control" id="" placeholder=""
                   value="{{ old('name', $user->name) }}">
            @if($errors->has('name'))
                <small class="help-block">{{ $errors->first('name') }}</small>
            @endif
        </div>
    </div>

    <div class="form-group{{$errors->has('avatar') ? ' has-error' : ''}}">
        <label for="" class="col-sm-2 control-label">头像</label>
        <div class="col-sm-8">
            <input type="text" name="avatar" class="form-control" placeholder="图片地址，确保域名为 {{ config('filesystems.disks.qiniu.domains.default') }} 且能正常打开，或点击右边上传" value="{{ old('avatar', $user->avatarAttachment->url()) }}">
            @if($errors->has('avatar'))
                <small class="help-block">{{ $errors->first('avatar') }}</small>
            @endif
        </div>
        <div class="col-sm-2">
            <input type="file" class="upload">
            <a class="preview" href="{{ old('avatar', $user->avatarAttachment->url()) }}" target="_blank"><img src="{{ old('avatar', $user->avatarAttachment->url()) }}" /></a>
        </div>
    </div>
    @endif
    <div class="form-group{{$errors->has('roles') ? ' has-error' : ''}}">
        <label for="" class="col-sm-2 control-label">角色</label>
        <div class="col-sm-10">
            @foreach($roles as $role)
            <label class="checkbox-inline">
                <input type="checkbox" value="{{ $role->name }}" name="roles[]"{{ $user->hasRole($role->name) ? " checked" : "" }}> {{ $role->display_name }}
            </label>
            @endforeach
            @if($errors->has('roles'))
                <small class="help-block">{{ $errors->first('roles') }}</small>
            @endif
        </div>
    </div>

    <div class="form-group{{$errors->has('password') ? ' has-error' : ''}}">
        <label for="" class="col-sm-2 control-label">密码</label>
        <div class="col-sm-10">
            <input type="text" name="password" class="form-control" id="" placeholder=""
                   value="{{ old('password') }}">
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
          <input type="submit" value="提交" class="btn btn-primary">
        </div>
      </div>
    </form>
@stop

@section('js')
    <script src="{{ asset('vendor/fileupload/jQuery-File-Upload-9.19.1/js/vendor/jquery.ui.widget.js') }}"></script>
    <script src="{{ asset('vendor/fileupload/jQuery-File-Upload-9.19.1/js/jquery.fileupload.js') }}"></script>
    <script>
        $('.upload').fileupload({
            dataType: 'json',
            paramName: 'image',
            url: "{{ route('upload.image') }}",
            done: function (e, data) {
                var $this = $(this);
                var url = data.result.data.url;
                $this.closest('div').prev().find("input").val(url);
                $this.closest('div').find(".preview")
                    .attr('href', url)
                    .find("img").attr("src", url);
            },
            fail: function (e, data, jqXHR) {
                console.log(jqXHR);
            }
        });
    </script>

@stop
