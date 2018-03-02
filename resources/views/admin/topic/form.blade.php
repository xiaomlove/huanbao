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

        <div class="form-group{{$errors->has('content') ? ' has-error' : ''}}">
            <label for="" class="col-sm-2 control-label">内容</label>
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
        var tid = '{{ $topic->id }}';
        $('#forum').select2();
        var contentEditor = new ContentEditor({
            wrapId: "content",
            uploadUrl: "{{ route('upload.image') }}",
            content: '{!! $topic->main_floor ? $topic->mainFloor->detail->content : "" !!}'
        });

        var $form = $('#form');
        $form.on("click", ".submit", function() {
            var data = $form.serialize();
            data += "&content=" + JSON.stringify(contentEditor.getData());
            $.ajax({
                url: $form.attr("action"),
                type: "post",
                dataType: "json",
                data: data,
            }).done(function(response) {
                console.log(response);
                alert(response.msg);
                if (response.ret == 0 && !tid) {
                    location.href = "{{ route('admin.topic.index') }}";
                }
            }).fail(function(xhr, errstr, errThrown) {
                var response = xhr.responseJSON;
                var msg = "";
                for (var i in response.data.errors) {
                    msg += response.data.errors[i][0] + "\n";
                }
                alert(msg);
            }).always(function() {

            })
        })

    </script>
@stop
