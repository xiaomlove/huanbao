@extends('layouts.admin')
@section('title', '编辑回复')
@section('content')
@inject('commentPresenter', 'App\Presenters\CommentPresenter')
<div class="breadcrumb-holder">
    <div class="container-fluid">
      <ul class="breadcrumb">
        <li class="breadcrumb-item"><a href="index.html">Home</a></li>
        <li class="breadcrumb-item active">Forms</li>
      </ul>
    </div>
</div>
<section id="form-edit-topic" class="forms">
    <div class="container-fluid">
      <header> 
        <h1 class="h3 display">Forms</h1>
      </header>
      <div class="row">
        <div class="col-lg-6">
          @include('common.admin.message')
          <div class="card">
            <div class="card-header d-flex align-items-center">
              <h2 class="h5 display">编辑回复</h2>
            </div>
            <div class="card-block">
              <form class="form-horizontal" method="post" action="{{ route('comment.update', $comment->id) }}" enctype="multipart/form-data">
              	{{ csrf_field() }}
              	{{ method_field('PATCH') }}
              	<input type="hidden" name="tid" value="{{ $comment->tid }}" />
                <div class="form-group row{{$errors->has('title') ? ' has-danger' : ''}}">
                  <label class="col-sm-2">帖子标题</label>
                  <div class="col-sm-10">
                    <input id="" type="text" name="" value="{{ old('title', $comment->topic->title) }}" placeholder="title" class="form-control form-control-success" readOnly>
                    @if($errors->has('title'))
                    <small class="form-text">{{ $errors->first('title') }}</small>
                    @endif
                  </div>
                </div>
                <div class="form-group row{{$errors->has('content') ? ' has-danger' : ''}}">
                  <label class="col-sm-2">内容</label>
                  <div class="col-sm-10">
                    <textarea rows="8" name="content" placeholder="content" class="form-control form-control-success">{{ old('content', $comment->detail->content) }}</textarea>
                    @if($errors->has('content'))
                    <small class="form-text">{{ $errors->first('content') }}</small>
                    @endif
                  </div>
                </div>
                @if(count($comment->attachments))
                <div class="form-group row">
                  <label class="col-sm-2">已有图片</label>
                  <div class="col-sm-10">
                  @foreach($comment->attachments as $attachment)
                    <div class="attachment-image">
                    	<img src="{{ $commentPresenter->getAttachmentImageLink($attachment) }}" />
                    	<input type="hidden" name="attachment_id[]" value="{{ $attachment->id }}" />
                    	<i class="fa fa-times remove" aria-hidden="true" title="删除"></i>
                    </div>
                  @endforeach
                  </div>
                </div>
                @endif
                <div class="form-group row">
                  <label class="col-sm-2">添加图片</label>
                  <div class="col-sm-10">
                    <input type="file" class="form-control-file" id="input-upload-file" name="image[]" multiple accept="image/jpg, image/jpeg, image/png, image/gif">
                  </div>
                </div>
                <div class="form-group row">       
                  <div class="col-sm-10 offset-sm-2">
                    <input type="submit" value="提交" class="btn btn-primary cursor">
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
</section>
<script>
var $section = $('#form-edit-topic');
$section.on("click", ".remove", function(e) {
	$(this).closest(".attachment-image").remove();
})
</script>
@stop