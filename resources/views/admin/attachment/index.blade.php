@extends('layouts.admin')

@section('title', '附件列表')

@inject('attachmentPresenter', 'App\Presenters\AttachmentPresenter')

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
    <table class="table attachment-table">
        <thead>
        <tr>
            <th>ID</th>
            <th>缩略图</th>
            <th>类型</th>
            <th>key</th>
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
                <td>{!! $attachmentPresenter->getThumbnail($value) !!}</td>
                <td>{{ $value->mime_type }}</td>
                <td>{{ $value->key }}</td>
                <td>{{ $value->user->name }}</td>
                <td>{{ $value->humanSize() }}</td>
                <td>{!! $attachmentPresenter->listAttaches($value) !!}</td>
                <td>
                    <form method="post" style="display: inline-block;" action="{{ route('admin.attachment.destroy', $value->id) }}" onsubmit="return window.confirm('确定删除？')">
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