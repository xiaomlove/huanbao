@extends('layouts.admin')

@section('title', '用户列表')

@inject('userPresenter', 'App\Presenters\UserPresenter')

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
        <a class="btn btn-info pull-right" href="{{ route('admin.user.create') }}">新建</a>
    </form>
@stop

@section('content')
    <table class="table topic-table table-hover">
        <thead>
        <tr>
            <th>ID</th>
            <th>用户名</th>
            <th>邮箱</th>
            <th>注册时间</th>
            <th>操作</th>
        </tr>
        </thead>
        <tbody>
        @foreach($list as $value)
            <tr>
                <th scope="row">{{ $value->id }}</th>
                <td>{{ $value->name }}</td>
                <td>{{ $value->email }}</td>
                <td>{{ $value->created_at }}</td>
                <td><a href="{{ route('admin.user.show', $value->id) }}">详情</a></td>
            </tr>
        @endforeach
        </tbody>
    </table>
    {!! $list->links() !!}
@stop

@section('js')
    <script src="{{ asset('vendor/laydate/layDate-v5.0.85/laydate/laydate.js') }}"></script>
    <script>
        laydate.render({
            elem: '#begin_time'
        })
        laydate.render({
            elem: '#end_time'
        })
    </script>
@stop