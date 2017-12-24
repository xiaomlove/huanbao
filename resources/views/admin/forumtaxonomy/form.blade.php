@extends('layouts.admin')

@section('title', $taxonomy->id ? '编辑版块分类' : '新建版块分类')

@section('content_header')
    <h2>

    </h2>
@stop

@section('content')
    @include('admin.common.message')
    @if($taxonomy->id)
        <form class="subject-form image-upload-form form-horizontal" method="post"
              action="{{ route('admin.forumtaxonomy.update', $taxonomy->id) }}">
            {{ method_field('PATCH') }}
    @else
        <form class="subject-form image-upload-form form-horizontal" method="post"
              action="{{ route('admin.forumtaxonomy.store') }}">
    @endif
            {{ csrf_field() }}
            <div class="form-group{{$errors->has('name') ? ' has-error' : ''}}">
                <label for="" class="col-sm-2 control-label">标题</label>
                <div class="col-sm-10">
                    <input type="text" name="name" class="form-control" id="" placeholder=""
                           value="{{ old('name', $taxonomy->name) }}">
                    @if($errors->has('name'))
                        <small class="help-block">{{ $errors->first('name') }}</small>
                    @endif
                </div>
            </div>

            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10 text-center">
                    <button type="submit" class="btn btn-primary">提交</button>
                </div>
            </div>
        </form>
@stop