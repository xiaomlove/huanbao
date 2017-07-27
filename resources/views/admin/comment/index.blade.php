@extends('layouts.admin')
@section('title', '回复列表')
@section('content')
@inject('commentPresenter', 'App\Presenters\CommentPresenter')
<section class="section-table">
	<div class="table-header">
    	<form class="form-inline search-form d-inline-block" method="get">
          <input type="text" name="id" value="{{ request('id') }}" class="form-control mb-2 mr-sm-2 mb-sm-0" id="" placeholder="输入ID">
          <input type="text" name="q"  value="{{ request('q') }}" class="form-control mb-2 mr-sm-2 mb-sm-0" id="" placeholder="输入昵称或邮箱">
          <button type="submit" class="btn btn-primary">筛选</button>
        </form>
        <span class="float-right">
        	<a class="btn btn-success btn-action" href="{{ route('topic.create') }}"><i class="fa fa-plus"></i>创建</a>
        </span>
    </div>
	<table id="topic-table" class="table table-hover topic-table">
      <thead>
        <tr>
          <th>ID</th>
          <th>内容</th>
          <th>位置</th>
          <th>作者</th>
          <th>点赞/回复</th>
          <th>操作</th>
        </tr>
      </thead>
      <tbody>
      @foreach($comments as $comment)
        <tr>
          <th scope="row">{{ $comment->id }}</th>
          <td>{{ str_limit($comment->detail->content, 40, '...') }}</td>
          <td>{{ $commentPresenter->getPosition($comment) }}</td>
          <td>
          	<small>{{ $comment->user->name }}</small>
          	<small>{{ $comment->created_at->format('Y-m-d H:i') }}</small>
          </td>
          <td>
          	<small>{{ $comment->like_count }}</small>
          	<small>{{ $comment->comment_count }}</small>
          </td>
          <td>
          	<a href="{{ route('comment.edit', $comment->id) }}">编辑</a>
          	<a href="{{ route('topic.show', $comment->tid) }}">详情</a>
          	<a href="javascript:;" class="destroy" data-url="{{ route('topic.destroy', $comment->id) }}">删除</a>
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>
    {!! $comments->links() !!}
</section>
<script>
var $table = $("#topic-table");
$table.on("click", ".arrow", function(e) {
	var $fa = $(this);
	var $tbody = $fa.closest("li").children(".topics-ul");
	if ($fa.attr("data-expand") == "1") {
		$tbody.slideUp();
		$fa.attr("data-expand", 0).removeClass("fa-caret-down").addClass("fa-caret-right");
	} else {
		$tbody.slideDown();
		$fa.attr("data-expand", 1).removeClass("fa-caret-right").addClass("fa-caret-down");
	}
	
});

$table.on("click", ".destroy", function(e) {
	var $this = $(this);
	var url = $this.attr("data-url");
	var token = $this.attr("data-token");
	$.post(url, {_method: "DELETE", _token: token}, function(response) {
		console.log(response);
	}, "json");
});
</script>
@stop