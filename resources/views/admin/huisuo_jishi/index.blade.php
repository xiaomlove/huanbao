@extends('layouts.admin')
@section('title', $pageTitle)
@section('content')
@inject('attachmentPresenter', 'App\Presenters\AttachmentPresenter')
@inject('huisuoJishiPresenter', 'App\Presenters\HuisuoJishiPresenter')
<section class="section-table">
    <div class="table-header">
        <form class="form-inline search-form d-inline-block" method="get">
          <input type="text" name="id" value="{{ request('id') }}" class="form-control mb-2 mr-sm-2 mb-sm-0" id="" placeholder="输入ID">
          <input type="text" name="q"  value="{{ request('q') }}" class="form-control mb-2 mr-sm-2 mb-sm-0" id="" placeholder="输入昵称或邮箱">
          <button type="submit" class="btn btn-primary">筛选</button>
        </form>
        <span class="float-right">
            <a class="btn btn-success btn-action" href="{{ route('admin.' . $typeFlag.'.create') }}"><i class="fa fa-plus"></i>创建</a>
        </span>
    </div>
    <table id="table-attachment-index" class="table table-hover topic-table">
      <thead>
        <tr>
          <th>ID</th>
          <th>封面图</th>
          <th>名称</th>
          <th>地址</th>
          <th>年龄</th>
          <th>均价</th>
          <th>联系方式</th>
          <th>更新于</th>
          <th>操作</th>
        </tr>
      </thead>
      <tbody>
      @foreach($list as $value)
        <tr>
          <th scope="row">{{ $value->id }}</th>
          <td><a target="_blank" href="{{ $attachmentPresenter->getAttachmentImageLink($value->coverImage) }}"><img src="{{ $attachmentPresenter->getAttachmentImageLink($value->coverImage, '40x40') }}"/></a></td>
          <th>{{ $value->name }}</th>
          <td>{{ $huisuoJishiPresenter->getAddress($value) }}</td>
          <td>{{ $value->age }}</td>
          <td>{{ $value->price }}</td>
          <td>{!! $huisuoJishiPresenter->listContacts($value) !!}</td>
          <td>{{ $value->updated_at}}</td>
          <td>
            <a href="{{ route('admin.huisuo.edit', $value->id) }}">编辑</a>
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