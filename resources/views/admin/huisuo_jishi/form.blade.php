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
                    {{ $huisuoJishi->short_name_label }}
                </label>
                <div class="col-sm-10">
                    <input type="text" name="short_name" class="form-control" id="" placeholder=""
                           value="{{ old('short_name', $huisuoJishi->short_name) }}">
                    @if($errors->has('short_name'))
                        <small class="help-block">{{ $errors->first('short_name') }}</small>
                    @endif
                </div>
            </div>
            <div class="form-group{{$errors->has('tid') ? ' has-error' : ''}}">
                <label for="" class="col-sm-2 control-label">关联帖子</label>
                <div class="col-sm-10">
                    <input type="text" name="tid" class="form-control" id="" placeholder=""
                           value="{{ old('tid', $huisuoJishi->tid) }}">
                    @if($errors->has('tid'))
                        <small class="help-block">{{ $errors->first('tid') }}</small>
                    @endif
                </div>
            </div>

            {!! imageFormGroup('背景图', 'background_image', old('background_image', $huisuoJishi->background_image), $errors) !!}

            <div class="form-group {{ $errors->hasAny(['province', 'city', 'district']) ? 'has-error' : '' }}">
                <label class="col-sm-2 control-label">地址</label>
                <div class="col-sm-10 select"  id="location">
                    <div style="display: flex;">
                        <select name="province" class="form-control" data-uri="{{ route('cnarea.province') }}" data-value="{{ old('province', $huisuoJishi->province) }}">
                            <option value="">省</option>
                        </select>
                        <select name="city" class="form-control" data-uri="{{ route('cnarea.city') }}" data-value="{{ old('city', $huisuoJishi->city) }}">
                            <option value="">市</option>
                        </select>
                        <select name="district" class="form-control" data-uri="{{ route('cnarea.district') }}" data-value="{{ old('district', $huisuoJishi->district) }}">
                            <option value="">区</option>
                        </select>
                    </div>
                    @if($errors->has('province'))
                        <small class="help-block">{{ $errors->first('province') }}</small>
                    @elseif($errors->has('city'))
                        <small class="help-block">{{ $errors->first('city') }}</small>
                    @elseif($errors->has('district'))
                        <small class="help-block">{{ $errors->first('district') }}</small>
                    @endif
                </div>
            </div>
            <div class="form-group{{$errors->has('address') ? ' has-danger' : ''}}">
                <label class="col-sm-2 control-label">详细地址</label>
                <div class="col-sm-10">
                    <textarea rows="4" name="address" placeholder="可留空，在关联的帖子中写" class="form-control form-control-success">{{ $huisuoJishi->address }}</textarea>
                </div>
            </div>

            {{ var_dump($errors) }}

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
    <script src="{{ asset('js/cn_area_select.js') }}"></script>
    <script>
        CnSelect.init("location");
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
