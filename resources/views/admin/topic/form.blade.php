@extends('layouts.admin')

@section('title', $topic->id ? '编辑帖子' : '新建帖子')

@section('content_header')
    <h2>

    </h2>
@stop

@section('content')
    @include('admin.common.message')
    @if($topic->id)
        <form id="form" class="subject-form image-upload-form form-horizontal" method="post"
              action="{{ route('admin.topic.update', $topic->id) }}">
            {{ method_field('PATCH') }}
    @else
        <form id="form" class="subject-form image-upload-form form-horizontal" method="post"
              action="{{ route('admin.topic.store') }}">
    @endif
        {{ csrf_field() }}
        <div class="form-group{{$errors->has('title') ? ' has-error' : ''}}">
            <label for="" class="col-sm-2 control-label">标题</label>
            <div class="col-sm-10">
                <input type="text" name="title" class="form-control" id="" placeholder=""
                       value="{{ old('title', $topic->title) }}">
                @if($errors->has('title'))
                    <small class="help-block">{{ $errors->first('title') }}</small>
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

        @include('admin.common.content_editor', ['contentFieldName' => 'content', 'contentFieldLabel' => '内容', 'contentFieldValue' => $topic->mainFloor ? $topic->mainFloor->detail->content : ""])

        <div class="form-group">
            <div class="col-sm-offset-2 col-sm-10 text-center">
                <input type="submit" value="提交" class="btn btn-primary">
            </div>
        </div>
    </form>

@stop

@section('js')
    <script>
        $('#forum').select2();
    </script>
@stop
