@extends('layouts.admin')

@section('title', '回复列表')

@inject('commentPresenter', 'App\Presenters\CommentPresenter')

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
        <!--
        <a class="btn btn-info pull-right" href="{{ route('admin.topic.create') }}">新建</a>
        -->
    </form>
@stop

@section('content')
    <table class="table topic-table comment-table">
        <thead>
        <tr>
            <th>ID</th>
            <th>内容</th>
            <th>位置</th>
            <th>作者</th>
            <th>点赞/回复</th>
            <th>操作</th>
        </tr>
        </thead>
        <tbody>
        @foreach($list as $value)
            <tr>
                <th scope="row">{{ $value->id }}</th>
                <td>{{ str_limit($commentPresenter->renderDetail($value, ['only_text' => true]), 40, '...') }}</td>
                <td>{!! $commentPresenter->getPosition($value) !!}</td>
                <td>
                    <small>{{ $value->user->name }}</small>
                    <small>{{ $value->created_at->format('Y-m-d H:i') }}</small>
                </td>
                <td>
                    {{ $value->like_count }}/{{ $value->comment_count }}
                </td>
                <td>
                    <a href="{!! $commentPresenter->getEditLink($value) !!}">编辑</a>
                    <a href="{{ route('admin.topic.show', $value->tid) }}">详情</a>
                    <form method="post" style="display: inline-block;" action="{{ route('admin.comment.destroy', $value->id) }}" onsubmit="return window.confirm('确定删除？')">
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