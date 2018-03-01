@extends('layouts.admin')

@section('title', "{$huisuoJishi->typeName}列表")

@inject('huisuoJishiPresenter', "App\Presenters\HuisuoJishiPresenter")

@section('content_header')
    @include('admin.common.message')
    <form class="form-inline">
        <div class="form-group">
            <input type="text" class="form-control" id="" name="name" placeholder="名称" value="{{ request('name') }}">
        </div>
        <div class="form-group">
            <input type="text" class="form-control" readonly id="begin_time" name="begin_time" placeholder="开始时间" value="{{ request('begin_time') }}">
        </div>
        <div class="form-group">
            <input type="text" class="form-control" readonly id="end_time" name="end_time" placeholder="结束时间" value="{{ request('end_time') }}">
        </div>
        <button type="submit" class="btn btn-default">搜索</button>
        <a class="btn btn-info pull-right" href="{{ route('admin.' . $huisuoJishi->type . '.create') }}">新建</a>
    </form>
@stop

@section('content')
    <table class="table">
        <thead>
        <tr>
            <th>ID</th>
            <th>名称</th>
            <th>地址</th>
            <th>最后更新</th>
            <th>操作</th>
        </tr>
        </thead>
        <tbody>
        @foreach($list as $value)
            <tr>
                <td>{{ $value->id }}</td>
                <td>{{ $value->name }}({{ $value->short_name }})</td>
                <td>{!! $huisuoJishiPresenter->getAddress($value) !!}</td>
                <td>{{ $value->updated_at->format('Y-m-d H:i') }}</td>
                <td>
                    <a href="{{ route('admin.' . $huisuoJishi->type . '.edit', $value->id) }}">编辑</a>
                    @if($huisuoJishi->isHuisuo())
                    <a href="{{ route('admin.huisuojishi.index', ['huisuo_id' => $value->id]) }}" target="_blank">{{ $value->typeNameOpposite }}列表</a>
                    <a href="{{ route('admin.huisuojishi.create', ['huisuo_id' => $value->id]) }}">添加{{ $value->typeNameOpposite }}</a>
                    @endif
                    <form method="post" style="display: inline-block;" action="{{ route('admin.' . $huisuoJishi->type . '.destroy', $value->id) }}" onsubmit="return window.confirm('确定删除？')">
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