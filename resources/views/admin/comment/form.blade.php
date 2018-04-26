@extends('layouts.admin')

@section('title', $comment->id ? '编辑回复' : '新建回复')

@section('content_header')
    <h2 class="text-center">
        {{ $topic->title }}
    </h2>
@stop

@section('content')
    @include('admin.common.message')
    @if($comment->id)
        <form id="form" class="subject-form image-upload-form form-horizontal" method="post"
              action="{{ route('admin.comment.update', $comment->id) }}">
    {{ method_field('PATCH') }}
    @else
        <form id="form" class="subject-form image-upload-form form-horizontal" method="post"
              action="{{ route('admin.comment.store') }}">
    @endif
            {{ csrf_field() }}
            <input type="hidden" name="tid" value="{{ request('tid', $comment->tid) }}">

            @include('admin.common.content_editor', ['contentFieldName' => 'content', 'contentFieldLabel' => '内容', 'contentFieldValue' => $comment->detail ? $comment->detail->content : ""])

            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10 text-center">
                    <input type="submit" value="提交" class="btn btn-primary">
                </div>
            </div>
        </form>
@stop
