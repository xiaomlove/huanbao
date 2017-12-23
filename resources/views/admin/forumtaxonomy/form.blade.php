@extends('layouts.admin')

@section('title', $taxonomy->id ? '编辑版块分类' : '新建版块分类')

@section('content_header')
    <a class="btn btn-primary pull-right" href="{{ route('admin.forumtaxonomy.index') }}">返回</a>
@stop

@section('content')
    @include('admin.common.message')
    @if($taxonomy->id)
        <form class="subject-form image-upload-form form-horizontal" method="post"
              action="{{ route('admin.forumtaxonomy.update', $taxonomy->id) }}">
            {{ method_field('PATCH') }}
    @else
        <form class="subject-form image-upload-form form-horizontal" method="post"
              action="{{ route('admin.forumtaxonomy.store') }}">
    @endif
            {{ csrf_field() }}
            <input type="hidden" name="project_id" value="{{ $taxonomy->id }}"/>
            <div class="form-group{{$errors->has('title') ? ' has-error' : ''}}">
                <label for="" class="col-sm-2 control-label">标题</label>
                <div class="col-sm-10">
                    <input type="text" name="title" class="form-control" id="" placeholder=""
                           value="{{ old('title', $taxonomy->title) }}">
                    @if($errors->has('title'))
                        <small class="help-block">{{ $errors->first('title') }}</small>
                    @endif
                </div>
            </div>
            <div class="form-group{{$errors->has('image') ? ' has-error' : ''}}">
                <label for="" class="col-sm-2 control-label">配图</label>
                <div class="col-sm-8">
                    <input type="text" name="image" class="form-control" id="" placeholder="图片地址，或点击右边上传"
                           value="{{ old('image', $taxonomy->image) }}">
                    @if($errors->has('image'))
                        <small class="help-block">{{ $errors->first('image') }}</small>
                    @endif
                </div>
                <div class="col-sm-2">
                    <input type="file" class="upload">
                    <a class="preview" href="{{ old('image', $taxonomy->image) }}" target="_blank"><img
                                src="{{ old('image', $taxonomy->image) }}"/></a>
                </div>
            </div>
            <div class="form-group{{$errors->has('priority') ? ' has-error' : ''}}">
                <label for="" class="col-sm-2 control-label">优先级</label>
                <div class="col-sm-10">
                    <input type="number" name="priority" class="form-control" id="" placeholder="值越大越靠前，默认0"
                           value="{{ old('priority', $taxonomy->priority) }}">
                    @if($errors->has('priority'))
                        <small class="help-block">{{ $errors->first('priority') }}</small>
                    @endif
                </div>
            </div>

            <div class="form-group" data-type="1">
                <label for="" class="col-sm-2 control-label"></label>
                <div class="col-sm-10">
                    <a class="btn btn-info" id="add-option">添加选项</a>
                </div>
            </div>

            <div class="form-group{{$errors->has('min_answer_count') ? ' has-error' : ''}}" data-type="1">
                <label for="" class="col-sm-2 control-label">最少选择项数</label>
                <div class="col-sm-8">
                    <input type="number" name="min_answer_count" min="1" class="form-control answer-count" id=""
                           placeholder="默认1(单选)"
                           value="{{ old('min_answer_count', $taxonomy->min_answer_count) }}">
                    @if($errors->has('min_answer_count'))
                        <small class="help-block">{{ $errors->first('min_answer_count') }}</small>
                    @endif
                </div>
                <div class="col-sm-2">
                    <span class="help-block">最少和最多同为1表示单选</span>
                </div>
            </div>

            <div class="form-group{{$errors->has('max_answer_count') ? ' has-error' : ''}}" data-type="1">
                <label for="" class="col-sm-2 control-label">最多选择项数</label>
                <div class="col-sm-8">
                    <input type="number" name="max_answer_count" min="1" class="form-control answer-count" id=""
                           placeholder="默认1(单选)"
                           value="{{ old('max_answer_count', $taxonomy->max_answer_count) }}">
                    @if($errors->has('max_answer_count'))
                        <small class="help-block">{{ $errors->first('max_answer_count') }}</small>
                    @endif
                </div>
                <div class="col-sm-2">
                    <span class="help-block">最少和最多同为1表示单选</span>
                </div>
            </div>

            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10 text-center">
                    <button type="submit" class="btn btn-primary">提交</button>
                </div>
            </div>
        </form>
@stop