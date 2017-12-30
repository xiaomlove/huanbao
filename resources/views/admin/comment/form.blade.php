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
        var tid = '{{ $comment->id }}';
        var contentEditor = new ContentEditor({
            wrapId: "content",
            uploadUrl: "{{ route('upload.image') }}",
            content: '{!! $comment->detail? $comment->detail->content : "" !!}'
        });

        var $form = $('#form');
        $form.on("click", ".submit", function () {
            var data = $form.serialize();
            data += "&content=" + JSON.stringify(contentEditor.getData());
            $.ajax({
                url: $form.attr("action"),
                type: "post",
                dataType: "json",
                data: data,
            }).done(function (response) {
                console.log(response);
                alert(response.msg);
                if (response.ret == 0 && !tid) {
                    location.href = "{{ route('admin.comment.index') }}";
                }
            }).fail(function (xhr, errstr, errThrown) {
                var response = xhr.responseJSON;
                var msg = "";
                for (var i in response.data.errors) {
                    msg += response.data.errors[i][0] + "\n";
                }
                alert(msg);
            }).always(function () {

            })
        })

    </script>
@stop
