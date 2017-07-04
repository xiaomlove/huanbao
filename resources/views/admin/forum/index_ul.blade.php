@extends('layouts.admin')
@section('title', '版块列表')
@section('content')
<section class="section-table">
	<div class="table-header">
    	<form class="form-inline search-form d-inline-block" method="get">
          <input type="text" name="id" value="{{ request('id') }}" class="form-control mb-2 mr-sm-2 mb-sm-0" id="" placeholder="输入ID">
          <input type="text" name="q"  value="{{ request('q') }}" class="form-control mb-2 mr-sm-2 mb-sm-0" id="" placeholder="输入昵称或邮箱">
          <button type="submit" class="btn btn-primary">筛选</button>
        </form>
        <span class="float-right">
        	<a class="btn btn-success btn-action" href="{{ route('forum.create') }}"><i class="fa fa-plus"></i>创建</a>
        </span>
    </div>
	<ul class="forums-ul thead">
      <li>
          <span>ID</span>
          <span>名称</span>
          <span>别名</span>
          <span>描述</span>
          <span>创建时间</span>
          <span>操作</span>
      </li>
    </ul>
      <ul id="forums-table" class="forums-ul tbody">
      @php
      $traverse = function($forums) use (&$traverse) {
      	foreach($forums as $key => $forum):
      @endphp
        <li>
          <span>{{ $forum->id }}</span>
          <span>
          	@if (!$forum->is_leaf)<i class="fa fa-caret-down arrow" aria-hidden="true" data-expand="1"></i>@endif
          	<span>{{ str_repeat('——', $forum->depth) }}</span>
          	<span>{{ $forum->name }}</span>
          </span>
          <span>{{ $forum->slug }}</span>
          <span>{{ $forum->description }}</span>
          <span>{{ $forum->created_at }}</span>
          <span>
          	<a href="{{ route('forum.edit', $forum->id) }}">编辑</a>
          	<a href="javascript:;" class="destroy" data-url="{{ route('forum.destroy', $forum->id) }}" data-token="{{ csrf_token() }}">删除</a>
          </span>
       @php
    	if ($forum->children)
    	{
        @endphp
        <ul class="forums-ul">
        @php
        		$traverse($forum->children);
        @endphp
        </ul>
        @php
        	}
        @endphp
        </li>
       @php
        endforeach;
        };
        $traverse($forums);
       @endphp
      </ul>
</section>
<script>
var $table = $("#forums-table");
$table.on("click", ".arrow", function(e) {
	var $fa = $(this);
	var $tbody = $fa.closest("li").children(".forums-ul");
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