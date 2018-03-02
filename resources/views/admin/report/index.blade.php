@extends('layouts.admin')

@section('title', "报告列表")

@inject('reportPresenter', "App\Presenters\ReportPresenter")

@section('content_header')
    @include('admin.common.message')
    <form class="form-inline">
        <div class="form-group">
            <input type="text" class="form-control" id="" name="jishi_id" placeholder="JS ID" value="{{ request('jishi_id') }}">
        </div>
        <div class="form-group">
            <input type="text" class="form-control" id="" name="jishi_name" placeholder="JS名称" value="{{ request('jishi_name') }}">
        </div>
        <div class="form-group">
            <input type="text" class="form-control" id="" name="huisuo_id" placeholder="HS ID" value="{{ request('huisuo_id') }}">
        </div>
        <div class="form-group">
            <input type="text" class="form-control" id="" name="huisuo_name" placeholder="HS名称" value="{{ request('huisuo_name') }}">
        </div>
        <div class="form-group">
            <input type="text" class="form-control" readonly id="begin_time" name="begin_time" placeholder="开始时间" value="{{ request('begin_time') }}">
        </div>
        <div class="form-group">
            <input type="text" class="form-control" readonly id="end_time" name="end_time" placeholder="结束时间" value="{{ request('end_time') }}">
        </div>
        <button type="submit" class="btn btn-default">搜索</button>
        <a class="btn btn-info pull-right" href="{{ route('admin.report.create') }}">新建</a>
    </form>
@stop

@section('content')
    <table class="table">
        <thead>
        <tr>
            <th>ID</th>
            <th>JS</th>
            <th>HS</th>
            <th>得分</th>
            <th>最后更新</th>
            <th>操作</th>
        </tr>
        </thead>
        <tbody>
        @foreach($list as $value)
            <tr>
                <td>{{ $value->id }}</td>
                <td>{{ $value->jishi_name }}({{ $value->jishi_id }})</td>
                <td>{{ $value->huisuo_name }}({{ $value->huisuo_id }})</td>
                <td>{!! $reportPresenter->getScoreDesc($value) !!}</td>
                <td>{{ $value->updated_at->format('Y-m-d H:i') }}</td>
                <td>
                    <a href="{{ route('admin.report.edit', $value->id) }}">编辑</a>
                    <form method="post" style="display: inline-block;" action="{{ route('admin.report.destroy', $value->id) }}" onsubmit="return window.confirm('确定删除？')">
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