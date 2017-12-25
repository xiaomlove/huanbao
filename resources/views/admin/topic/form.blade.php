@extends('layouts.admin')

@section('title', $topic->id ? '编辑帖子' : '新建帖子')

@section('content_header')
    <h2>

    </h2>
@stop

@section('content')
    @include('admin.common.message')
    @if($topic->id)
        <form class="subject-form image-upload-form form-horizontal" method="post"
              action="{{ route('admin.topic.update', $topic->id) }}">
            {{ method_field('PATCH') }}
    @else
        <form class="subject-form image-upload-form form-horizontal" method="post"
              action="{{ route('admin.topic.store') }}">
            @endif
            {{ csrf_field() }}
            <div class="form-group{{$errors->has('name') ? ' has-error' : ''}}">
                <label for="" class="col-sm-2 control-label">标题</label>
                <div class="col-sm-10">
                    <input type="text" name="name" class="form-control" id="" placeholder=""
                           value="{{ old('name', $topic->name) }}">
                    @if($errors->has('name'))
                        <small class="help-block">{{ $errors->first('name') }}</small>
                    @endif
                </div>
            </div>

            <div class="form-group{{$errors->has('fid') ? ' has-error' : ''}}">
                <label for="" class="col-sm-2 control-label">所属版块</label>
                <div class="col-sm-10">
                    <select id="forum" class="form-control" name="fid">
                    @foreach($forums as $forum)
                        <option value="{{ $forum->id }}"{{ $topic->fid == $forum->id ? " selected" : "" }}>{{ $forum->name }}</option>
                    @endforeach
                    </select>
                    @if($errors->has('fid'))
                        <small class="help-block">{{ $errors->first('fid') }}</small>
                    @endif
                </div>
            </div>

            <div class="form-group{{$errors->has('content') ? ' has-error' : ''}}">
                <label for="" class="col-sm-2 control-label">内容</label>
                <div class="col-sm-10">
                    <textarea name="content" class="form-control" id="" placeholder=""
                              rows="4">{{ old('content', $topic->content) }}</textarea>
                    @if($errors->has('slug'))
                        <small class="help-block">{{ $errors->first('content') }}</small>
                    @endif
                </div>
            </div>

            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10 text-center">
                    <button type="submit" class="btn btn-primary">提交</button>
                </div>
            </div>
        </form>
@stop