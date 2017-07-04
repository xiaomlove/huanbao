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
	<table id="forums-table" class="table table-hover forums-table">
      <thead>
        <tr>
          <th>ID</th>
          <th>名称</th>
          <th>别名</th>
          <th>描述</th>
          <th>创建时间</th>
          <th>操作</th>
        </tr>
      </thead>
      <tbody>
      @php
      $traverse = function($forums) use (&$traverse) {
      	foreach($forums as $key => $forum):
      @endphp
        <tr>
          <th scope="row">{{ $forum->id }}</th>
          <td>
          {{ str_repeat('——', $forum->depth) . $forum->name }}
          @if (!$forum->is_leaf)<i class="fa fa-caret-down" aria-hidden="true" data-expand="1"></i>@endif
          </td>
          <td>{{ $forum->slug }}</td>
          <td>{{ $forum->description }}</td>
          <td>{{ $forum->created_at }}</td>
          <td>
          	<a href="{{ route('forums.edit', $forum->id) }}">编辑</a>
          	<a href="javascript:;" class="destroy" data-url="{{ route('forums.destroy', $forum->id) }}">删除</a>
          </td>
        </tr>
        @php
        	if ($forum->children)
        	{
        		$traverse($forum->children);
        	}
        	endforeach;
        };
        $traverse($forums);
        @endphp
      </tbody>
    </table>
</section>
<script>
$("#forums-table").on("click", ".fa", function(e) {
	var $fa = $(this);
	var $tbody = $fa.closest("tbody");
	if ($fa.attr("data-expand") == "1") {
		$tbody.next().hide();
		$fa.attr("data-expand", 0).removeClass("fa-caret-down").addClass("fa-caret-right");
	} else {
		$tbody.next().show();
		$fa.attr("data-expand", 1).removeClass("fa-caret-right").addClass("fa-caret-down");
	}
	
})


</script>
@stop