@extends('layouts.admin')

@section('title', $permission->id ? '编辑权限' : '新建权限')

@section('content_header')
  <h2>

  </h2>
@stop

@section('content')
  @include('admin.common.message')
  @if($permission->id)
    <form id="form" class="subject-form image-upload-form form-horizontal" method="post"
          action="{{ route('admin.permission.update', $permission->id) }}">
  {{ method_field('PATCH') }}
  @else
    <form id="form" class="subject-form image-upload-form form-horizontal" method="post"
          action="{{ route('admin.permission.store') }}">
      @endif
      {{ csrf_field() }}
      <div class="form-group{{$errors->has('name') ? ' has-error' : ''}}">
        <label for="" class="col-sm-2 control-label">名称</label>
        <div class="col-sm-10">
          <input type="text" name="name" class="form-control" id="" placeholder=""
                 value="{{ old('name', $permission->name) }}" readonly>
          @if($errors->has('name'))
            <small class="help-block">{{ $errors->first('name') }}</small>
          @endif
        </div>
      </div>

      <div class="form-group{{$errors->has('display_name') ? ' has-error' : ''}}">
        <label for="" class="col-sm-2 control-label">显示名称</label>
        <div class="col-sm-10">
          <input type="text" name="display_name" class="form-control" id="" placeholder=""
                 value="{{ old('display_name', $permission->display_name) }}">
          @if($errors->has('display_name'))
            <small class="help-block">{{ $errors->first('display_name') }}</small>
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
            url: "{{ route('admin.upload.image') }}",
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
