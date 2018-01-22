@extends('layouts.admin')

@section('title', '编辑用户个人权限')

@section('content_header')
  <h2>
    【{{ $user->name }}】的个人权限
  </h2>
@stop

@section('content')
  @include('admin.common.message')
    <form id="form" class="subject-form image-upload-form form-horizontal" method="post"
          action="{{ route('admin.user.permission', $user->id) }}">
    {{ method_field('PATCH') }}

  {{ csrf_field() }}

    @include('admin.common.user_permissions')

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
