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
        	<a class="btn btn-success btn-action" href="{{ route('forums.create') }}"><i class="fa fa-plus"></i>创建</a>
        </span>
    </div>
	<table class="table table-hover">
      <thead>
        <tr>
          <th>ID</th>
          <th>名称</th>
          <th>描述</th>
          <th>创建时间</th>
          <th>操作</th>
        </tr>
      </thead>
      <tbody>
      @foreach($forums as $forum)
        <tr>
          <th scope="row">{{ $forum->id }}</th>
          <td>{{ $forum->name }}</td>
          <td>{{ $forum->description }}</td>
          <td>{{ $forum->created_at }}</td>
          <td>
          	<a href="{{ route('forums.edit', $forum->id) }}">编辑</a>
          	<a href="{{ route('forums.destroy', $forum->id) }}">删除</a>
          </td>
        </tr>
      @endforeach
      </tbody>
    </table>
    {!! $forums->links() !!}
</section>
@stop