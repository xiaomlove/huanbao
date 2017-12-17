@extends('layouts.admin')
@section('title', '编辑用户组')
@section('content')
<div class="breadcrumb-holder">
    <div class="container-fluid">
      <ul class="breadcrumb">
        <li class="breadcrumb-item"><a href="index.html">Home</a></li>
        <li class="breadcrumb-item active">Forms</li>
      </ul>
    </div>
</div>
<section class="forms">
    <div class="container-fluid">
      <header> 
        <h1 class="h3 display">Forms</h1>
      </header>
      <div class="row">
        <div class="col-lg-6">
          @include('common.admin.message')
          <div class="card">
            <div class="card-header d-flex align-items-center">
              <h2 class="h5 display">编辑用户组</h2>
            </div>
            <div class="card-block">
              <form class="form-horizontal" method="post" action="{{ route('role.update', $info->id) }}" enctype="multipart/form-data">
              	{{ csrf_field() }}
              	{{ method_field('PATCH') }}
                <div class="form-group row{{$errors->has('name') ? ' has-danger' : ''}}">
                  <label class="col-sm-2">名称</label>
                  <div class="col-sm-10">
                    <input id="" type="text" name="name" value="{{ old('name', $info->name) }}" placeholder="name" class="form-control form-control-success">
                    @if($errors->has('name'))
                    <small class="form-text">{{ $errors->first('name') }}</small>
                    @endif
                  </div>
                </div>
                <div class="form-group row{{$errors->has('display_name') ? ' has-danger' : ''}}">
                  <label class="col-sm-2">展示名称</label>
                  <div class="col-sm-10">
                    <input id="" type="text" name="display_name" value="{{ old('display_name', $info->display_name) }}" placeholder="name" class="form-control form-control-success">
                    @if($errors->has('display_name'))
                    <small class="form-text">{{ $errors->first('display_name') }}</small>
                    @endif
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-sm-2">拥有权限</label>
                  <div class="col-sm-10">
                    @include('common.admin.permission')
                  </div>
                </div>
                <div class="form-group row">       
                  <div class="col-sm-10 offset-sm-2">
                    <input type="submit" value="提交" class="btn btn-primary cursor">
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
</section>
@stop