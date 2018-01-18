@extends('layouts.admin')

@section('title', '用户主页')

@inject('userPresenter', 'App\Presenters\UserPresenter')

@section('content_header')
    @include('admin.common.message')
    <form class="form-inline">
        <div class="form-group">
            <input type="text" class="form-control" name="tid" placeholder="帖子ID" value="{{ request('tid') }}">
        </div>
        <div class="form-group">
            <input type="text" class="form-control" name="root_id" placeholder="根回复ID" value="{{ request('root_id') }}">
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
            <th colspan="1" class="text-center">头像</th>
            <th colspan="6" class="text-center">基本资料</th>
            <th colspan="1" class="text-center">操作</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td rowspan="4" class="text-center">
                <a href="{{ $user->avatarAttachment->url() }}" target="_blank"><img src="{{ $user->avatarAttachment->url(80, 80) }}" /></a>
                <p>
                    <a class="btn btn-sm" href="{{ route('admin.user.edit', $user->id) }}">修改</a>
                </p>
            </td>
        </tr>
        <tr>
            <td>昵称：</td>
            <td>{{ $user->name }}</td>

            <td>积分：</td>
            <td>{{ $user->point_counts }}</td>

            <td>关注数：</td>
            <td>{{ $user->following_counts }}</td>

            <td>xxx</td>

        </tr>
        <tr>
            <td>UID：</td>
            <td>{{ $user->id }}</td>

            <td>帖子数：</td>
            <td>{{ $user->topic_counts }}</td>

            <td>粉丝数：</td>
            <td>{{ $user->fans_counts }}</td>

            <td>xxx</td>
        </tr>
        <tr>
            <td>邮箱：</td>
            <td>{{ $user->email }}</td>

            <td>回复数：</td>
            <td>{{ $user->comment_counts }}</td>

            <td>注册天数：</td>
            <td>--</td>

            <td>xxx</td>
        </tr>
        </tbody>
    </table>

    <!-- Modal -->
    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">修改基本信息</h4>
                </div>
                <div class="modal-body">
                    <form class="form-horizontal">
                        <div class="form-group">
                            <label for="" class="col-sm-2 control-label">昵称</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="" placeholder="昵称">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="" class="col-sm-2 control-label"></label>
                            <div class="col-sm-10">
                                <input type="password" class="form-control" id="inputPassword3" placeholder="Password">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-10">
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox"> Remember me
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-10">
                                <button type="submit" class="btn btn-default">Sign in</button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
                    <button type="button" class="btn btn-primary">确定</button>
                </div>
            </div>
        </div>
    </div>

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