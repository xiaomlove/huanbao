@extends('layouts.admin')

@section('title', '角色列表')

@section('content_header')
    @include('admin.common.message')
    <form class="form-inline">
        <div class="form-group">
            <input type="text" class="form-control" id="" name="tid" placeholder="帖子ID" value="{{ request('tid') }}">
        </div>
        <div class="form-group">
            <input type="text" class="form-control" id="" name="root_id" placeholder="根回复ID" value="{{ request('root_id') }}">
        </div>
        <div class="form-group">
            <input type="text" class="form-control" readonly id="begin_time" name="begin_time" placeholder="开始时间" value="{{ request('begin_time') }}">
        </div>
        <div class="form-group">
            <input type="text" class="form-control" readonly id="end_time" name="end_time" placeholder="结束时间" value="{{ request('end_time') }}">
        </div>
        <button type="submit" class="btn btn-default">搜索</button>
        <a class="btn btn-info pull-right" href="{{ route('admin.role.create') }}">新建</a>
    </form>
@stop

@section('content')
    <table class="table topic-table comment-table">
        <thead>
        <tr>
            <th>ID</th>
            <th>名称</th>
            <th>显示名称</th>
            <th>操作</th>
        </tr>
        </thead>
        <tbody>
        @foreach($list as $value)
            <tr>
                <th scope="row">{{ $value->id }}</th>
                <td>{{ $value->name }}</td>
                <td>{{ $value->display_name  }}</td>
                <td>
                    <a href="{{ route('admin.role.edit', $value->id) }}">编辑</a>
                    <form method="post" style="display: inline-block;" action="{{ route('admin.role.destroy', $value->id) }}" onsubmit="return window.confirm('确定删除？')">
                        {{ csrf_field() }}
                        {{ method_field('DELETE') }}
                        <button type="submit" class="btn btn-xs btn-danger">删除</button>
                    </form>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    {!! $list->appends(request()->except('page'))->links() !!}
@stop
