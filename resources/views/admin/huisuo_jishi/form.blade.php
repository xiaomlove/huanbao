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

            @include('admin.common.image_upload', ['imageFieldLabel' => '背景图', 'imageFieldName' => 'background_image', 'imageFieldObject' => $huisuoJishi])

            @include('admin.common.cnarea', ['cnareaFieldObject' => $huisuoJishi])

            <div class="form-group{{$errors->has('address') ? ' has-danger' : ''}}">
                <label class="col-sm-2 control-label">详细地址</label>
                <div class="col-sm-10">
                    <textarea rows="4" name="address" placeholder="" class="form-control form-control-success">{{ $huisuoJishi->address }}</textarea>
                </div>
            </div>

            @include('admin.common.content_editor', ['contentFieldName' => 'content', 'contentFieldLabel' => '详细描述', 'contentFieldValue' => $huisuoJishi->topic ? $huisuoJishi->topic->mainFloor->detail->content : ""])

            {{ var_dump($errors) }}

            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10 text-center">
                    <input type="submit" value="提交" class="btn btn-primary">
                </div>
            </div>
        </form>
@stop



