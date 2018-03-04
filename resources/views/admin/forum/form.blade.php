@extends('layouts.admin')

@section('title', $forum->id ? '编辑版块' : '新建版块')

@section('content_header')
    <h2>

    </h2>
@stop

@section('content')
    @include('admin.common.message')
    @if($forum->id)
        <form class="subject-form image-upload-form form-horizontal" method="post"
              action="{{ route('admin.forum.update', $forum->id) }}">
            {{ method_field('PATCH') }}
    @else
        <form class="subject-form image-upload-form form-horizontal" method="post"
              action="{{ route('admin.forum.store') }}">
            @endif
            {{ csrf_field() }}
            <div class="form-group{{$errors->has('name') ? ' has-error' : ''}}">
                <label for="" class="col-sm-2 control-label">标题</label>
                <div class="col-sm-10">
                    <input type="text" name="name" class="form-control" id="" placeholder=""
                           value="{{ old('name', $forum->name) }}">
                    @if($errors->has('name'))
                        <small class="help-block">{{ $errors->first('name') }}</small>
                    @endif
                </div>
            </div>

            <div class="form-group{{$errors->has('icon') ? ' has-error' : ''}}">
                <label for="" class="col-sm-2 control-label">图标</label>
                <div class="col-sm-10">
                    <input type="text" name="icon" class="form-control" id="" placeholder=""
                           value="{{ old('icon', $forum->icon) }}">
                    @if($errors->has('icon'))
                        <small class="help-block">{{ $errors->first('icon') }}</small>
                    @endif
                </div>
            </div>

            <div class="form-group{{$errors->has('description') ? ' has-error' : ''}}">
                <label for="" class="col-sm-2 control-label">描述</label>
                <div class="col-sm-10">
                    <textarea  name="description" class="form-control" id="" placeholder="" rows="4">{{ old('description', $forum->description) }}</textarea>
                    @if($errors->has('icon'))
                        <small class="help-block">{{ $errors->first('description') }}</small>
                    @endif
                </div>
            </div>

            <div class="form-group{{$errors->has('taxonomy.*') ? ' has-error' : ''}}">
                <label for="" class="col-sm-2 control-label">所属分类</label>
                <div class="col-sm-10">
                    @foreach($taxonomies as $taxonomy)
                    <label class="checkbox-inline">
                        <input type="checkbox" value="{{ $taxonomy->id }}" name="taxonomies[]"{{ $forum->taxonomies->contains('id', $taxonomy->id) ? " checked" : "" }}>{{$taxonomy->name}}
                    </label>
                    @endforeach
                    @if($errors->has('taxonomy.*'))
                        <small class="help-block">{{ $errors->first('taxonomy') }}</small>
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