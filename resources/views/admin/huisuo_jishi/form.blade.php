@extends('layouts.admin')

@section('title', ($huisuoJishi->id ? '编辑' : '新建') . $huisuoJishi->typeName)

@section('content_header')
    <h2>

    </h2>
@stop

@section('content')
    @include('admin.common.message')
    @if($huisuoJishi->id)
        <form id="form" class="subject-form image-upload-form form-horizontal" method="post"
              action="{{ route('admin.' . $huisuoJishi->type . '.update', $huisuoJishi->id) }}">
    {{ method_field('PATCH') }}
    @else
        <form id="form" class="subject-form image-upload-form form-horizontal" method="post"
              action="{{ route('admin.' . $huisuoJishi->type . '.store') }}">
    @endif
            {{ csrf_field() }}
            <div class="form-group{{$errors->has('name') ? ' has-error' : ''}}">
                <label for="" class="col-sm-2 control-label">名称</label>
                <div class="col-sm-10">
                    <input type="text" name="name" class="form-control" id="" placeholder=""
                           value="{{ old('name', $huisuoJishi->name) }}">
                    @if($errors->has('name'))
                        <small class="help-block">{{ $errors->first('name') }}</small>
                    @endif
                </div>
            </div>
            <div class="form-group{{$errors->has('short_name') ? ' has-error' : ''}}">
                <label for="" class="col-sm-2 control-label">
                    @if($huisuoJishi->isJishi())
                        工号
                    @elseif($huisuoJishi->isHuisuo())
                        简称
                    @endif
                </label>
                <div class="col-sm-10">
                    <input type="text" name="short_name" class="form-control" id="" placeholder=""
                           value="{{ old('short_name', $huisuoJishi->short_name) }}">
                    @if($errors->has('short_name'))
                        <small class="help-block">{{ $errors->first('short_name') }}</small>
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
