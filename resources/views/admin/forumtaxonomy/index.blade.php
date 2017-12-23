@extends('adminlte::page')

@section('title', '版块分类列表')

@section('content_header')
    @include('admin.common.message')
    <form class="form-inline">
        <div class="form-group">
            <input type="text" class="form-control" id="" name="name" placeholder="名称" value="{{ request('name') }}">
        </div>
        <div class="form-group">
            <input type="text" class="form-control" id="" name="party_origination" placeholder="发起方" value="{{ request('party_origination') }}">
        </div>
        <div class="form-group">
            <input type="text" class="form-control" readonly id="begin_time" name="begin_time" placeholder="开始时间" value="{{ request('begin_time') }}">
        </div>
        <div class="form-group">
            <input type="text" class="form-control" readonly id="end_time" name="end_time" placeholder="结束时间" value="{{ request('end_time') }}">
        </div>
        <button type="submit" class="btn btn-default">搜索</button>
    </form>
@stop

@section('content')
    <table class="table">
        <thead>
        <tr>
            <th>ID</th>
            <th>名称</th>
            <th>最后更新</th>
            <th>操作</th>
        </tr>
        </thead>
        <tbody>
        @foreach($list as $value)
            <tr>
                <td>{{ $value->id }}</td>
                <td>{{ $value->name }}</td>
                <td>{{ $value->updated_at->format('Y-m-d H:i') }}</td>
                <td>
                    <a href="{{ route('admin.forum.edit', $value->id) }}">编辑</a>
                    <a target="_blank" href="{{ route('admin.forum.index')}}?project_id={{ $value->id }}">题目</a>
                    <form method="post" style="display: inline-block;" action="{{ route('admin.forum.destroy', $value->id) }}" onsubmit="return window.confirm('确定删除？')">
                        {{ csrf_field() }}
                        {{ method_field('DELETE') }}
                        <button type="submit" class="btn btn-xs btn-danger">删除</button>
                    </form>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    {!! $list->links() !!}
@stop
