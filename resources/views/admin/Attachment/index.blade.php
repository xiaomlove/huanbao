@extends('layouts.admin')
@section('title', '附件列表')
@section('content')
@inject('attachmentPresenter', 'App\Presenters\AttachmentPresenter')
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
	<table id="table-attachment-index" class="table table-hover topic-table">
      <thead>
        <tr>
          <th>ID</th>
          <th>缩略图</th>
          <th>类型</th>
          <th>路径</th>
          <th>上传者</th>
          <th>大小</th>
          <th>依附于</th>
          <th>操作</th>
        </tr>
      </thead>
      <tbody>
      @foreach($list as $value)
        <tr>
          <th scope="row">{{ $value->id }}</th>
          <td><a target="_blank" href="{{ $attachmentPresenter->getAttachmentImageLink($value, false) }}"><img src="{{ $attachmentPresenter->getAttachmentImageLink($value, true) }}"/></a></td>
          <td>{{ $value->mime_type }}</td>
          <td>{{ $value->dirname . '/' . $value->basename}}</td>
          <td>
          	<small>{{ $value->uid }}</small>
          	<small>{{ $value->created_at->format('Y-m-d H:i')}}</small>
          </td>
          <td>{{ round($value->size / 1024) . " KB"}}</td>
          <td>{!! $attachmentPresenter->getAttached($value) !!}</td>
          <td>
          	<a href="javascript:;">删除</a>
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>
    {!! $list->links() !!}
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