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
            <div class="form-group{{$errors->has('content') ? ' has-error' : ''}}">
                <label for="" class="col-sm-2 control-label">回复内容</label>
                <div class="col-sm-10">
                    <div id="content"></div>
                    @if($errors->has('slug'))
                        <small class="help-block">{{ $errors->first('content') }}</small>
                    @endif
                </div>
            </div>

            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10 text-center">
                    <a class="btn btn-primary submit">提交</a>
                </div>
            </div>
        </form>
@stop

@section('js')
    <script src="{{ asset('js/text_modal.js') }}"></script>
    <script src="{{ asset('js/image_modal.js') }}"></script>
    <script src="{{ asset('js/content_editor.js') }}"></script>

    <script>
        var tid = "{{ request('tid') }}";
        var contentEditor = new ContentEditor({
            formId: "form",
            wrapId: "content",
            uploadUrl: "{{ route('upload.image') }}",
            content: '{!! $comment->detail? $comment->detail->content : "" !!}',
            submitBtnSelector: ".submit",
            createdRedirectUrl: tid && "{{ route('admin.topic.show', ['tid' => request('tid')]) }}",
            updatedRedirectUrl: ""
        });

    </script>
@stop
