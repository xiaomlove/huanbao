@extends('layouts.admin')

@section('title', '帖子列表')

@inject('topicPresenter', 'App\Presenters\TopicPresenter')

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
        <a class="btn btn-info pull-right" href="{{ route('admin.topic.create') }}">新建</a>
    </form>
@stop

@section('content')
    <table class="table topic-table">
        <thead>
        <tr>
            <th>ID</th>
            <th>标题</th>
            <th>所在版块</th>
            <th>作者</th>
            <th>回复/阅读</th>
            <th>最后回复</th>
            <th>操作</th>
        </tr>
        </thead>
        <tbody>
        @foreach($list as $value)
            <tr>
                <td>{{ $value->id }}</td>
                <td><a href="{{ route('admin.topic.show', $value->id) }}">{{ $value->title }}</a></td>
                <td>{{ $value->forum->name }}</td>
                <td>
                    <small>{{ $value->user->name }}</small>
                    <small>{{ $value->created_at->format('Y-m-d H:i') }}</small>
                </td>
                <td>
                    {{ $value->view_count }}/{{ $value->comment_count }}
                </td>
                <td>{!! $topicPresenter->getLastReply($value) !!}</td>
                <td>
                    <a href="{{ route('admin.topic.edit', $value->id) }}">编辑</a>
                    <form method="post" style="display: inline-block;" action="{{ route('admin.topic.destroy', $value->id) }}" onsubmit="return window.confirm('确定删除？')">
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