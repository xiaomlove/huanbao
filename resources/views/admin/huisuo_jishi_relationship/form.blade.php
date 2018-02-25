@extends('layouts.admin')

@section('title', ($relationship->id ? '编辑' : '新建') . '关联记录')

@section('content_header')
    <h2>

    </h2>
@stop

@section('content')
    @include('admin.common.message')
    @if($relationship->id)
        <form id="form" class="subject-form image-upload-form form-horizontal" method="post"
              action="{{ route('admin.huisuojishi.update', $relationship->id) }}">
    {{ method_field('PATCH') }}
    @else
        <form id="form" class="subject-form image-upload-form form-horizontal" method="post"
              action="{{ route('admin.huisuojishi.store') }}">
    @endif
            {{ csrf_field() }}
            <div class="form-group{{$errors->has('huisuo_id') ? ' has-error' : ''}}">
                <label for="" class="col-sm-2 control-label">HS ID</label>
                <div class="col-sm-10">
                    <input type="text" readonly name="huisuo_id" class="form-control" id="" placeholder=""
                           value="{{ $huisuoJishi->id }}">
                    @if($errors->has('huisuo_id'))
                        <small class="help-block">{{ $errors->first('huisuo_id') }}</small>
                    @endif
                </div>
            </div>

            <div class="form-group{{$errors->has('huisuo_name') ? ' has-error' : ''}}">
                <label for="" class="col-sm-2 control-label">HS名称</label>
                <div class="col-sm-10">
                    <input type="text" readonly name="huisuo_name" class="form-control" id="" placeholder=""
                           value="{{ old('huisuo_name', $huisuoJishi->name) }}">
                    @if($errors->has('huisuo_name'))
                        <small class="help-block">{{ $errors->first('huisuo_name') }}</small>
                    @endif
                </div>
            </div>

            <div class="form-group{{$errors->has('jishi_id') ? ' has-error' : ''}}">
                <label for="" class="col-sm-2 control-label">JS ID</label>
                <div class="col-sm-10">
                    <input type="text" name="jishi_id" class="form-control" id="" placeholder=""
                           value="{{ old('jishi_id', $relationship->jishi_id) }}">
                    @if($errors->has('jishi_id'))
                        <small class="help-block">{{ $errors->first('jishi_id') }}</small>
                    @endif
                </div>
            </div>

            <div class="form-group{{$errors->has('begin_time') ? ' has-error' : ''}}">
                <label for="" class="col-sm-2 control-label">开始时间</label>
                <div class="col-sm-10">
                    <input type="text" readonly name="begin_time" class="form-control" id="begin_time" placeholder=""
                           value="{{ old('begin_time', $relationship->begin_time) }}">
                    @if($errors->has('begin_time'))
                        <small class="help-block">{{ $errors->first('begin_time') }}</small>
                    @endif
                </div>
            </div>

            <div class="form-group{{$errors->has('end_time') ? ' has-error' : ''}}">
                <label for="" class="col-sm-2 control-label">结束时间</label>
                <div class="col-sm-10">
                    <input type="text" readonly name="end_time" class="form-control" id="end_time" placeholder=""
                           value="{{ old('end_time', $relationship->end_time) }}">
                    @if($errors->has('end_time'))
                        <small class="help-block">{{ $errors->first('end_time') }}</small>
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
    <script src="{{ asset('vendor/laydate/layDate-v5.0.85/laydate/laydate.js') }}"></script>
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
        laydate.render({
            elem: '#begin_time'
        })
        laydate.render({
            elem: '#end_time'
        })
    </script>
@stop
