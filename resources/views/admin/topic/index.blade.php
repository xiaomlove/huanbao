@extends('layouts.admin')
@section('title', '话题列表')
@section('content')
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
	<table id="topics-table" class="table table-hover topics-table">
      <thead>
        <tr>
          <th>ID</th>
          <th>标题</th>
          <th>所在版块</th>
          <th>作者</th>
          <th>阅读/回复</th>
          <th>发表时间</th>
          <th>最后回复</th>
          <th>操作</th>
        </tr>
      </thead>
      <tbody>
      @foreach($topics as $topic)
        <tr>
          <th scope="row">{{ $topic->id }}</th>
          <td>{{ $topic->title }}</td>
          <td>{{ $topic->fid }}</td>
          <td>{{ $topic->uid }}</td>
          <td>{{ $topic->view_count }}/{{ $topic->comment_count }}</td>
          <td>{{ $topic->created_at }}</td>
          <td>{{ $topic->last_comment_time }}</td>
          <td>
          	<a href="{{ route('topics.edit', $topic->id) }}">编辑</a>
          	<a href="{{ route('topics.show', $topic->id) }}">详情</a>
          	<a href="javascript:;" class="destroy" data-url="{{ route('topics.destroy', $topic->id) }}">删除</a>
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>
    {!! $topics->links() !!}
</section>
<script>
var $table = $("#topics-table");
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