@extends('layouts.admin')
@section('title', '新建版块')
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
          <div class="card">
            <div class="card-header d-flex align-items-center">
              <h2 class="h5 display">新建版块</h2>
            </div>
            <div class="card-block">
              <form class="form-horizontal" method="post" action="{{ route('forums.store') }}">
              	{{ csrf_field() }}
                <div class="form-group row{{$errors->has('name') ? ' has-danger' : ''}}">
                  <label class="col-sm-2">名称</label>
                  <div class="col-sm-10">
                    <input id="" type="text" name="name" value="{{ old('name') }}" placeholder="name" class="form-control form-control-success">
                    @if($errors->has('name'))
                    <small class="form-text">{{ $errors->first('name') }}</small>
                    @endif
                  </div>
                </div>
                <div class="form-group row{{$errors->has('description') ? ' has-danger' : ''}}">
                  <label class="col-sm-2">描述</label>
                  <div class="col-sm-10">
                    <textarea rows="4" name="description" placeholder="description" class="form-control form-control-success">{{ old('description') }}</textarea>
                    @if($errors->has('description'))
                    <small class="form-text">{{ $errors->first('description') }}</small>
                    @endif
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-sm-2">父级</label>
                  <div class="col-sm-10 select">
                    <select name="pid" class="form-control">
                      <option value="0">--无--</option>
                      <option value="1">罗湖</option>
                      <option value="2">宝安</option>
                      <option value="3">福田</option>
                      <option value="4">南山</option>
                    </select>
                  </div>
                </div>
                <div class="form-group row{{$errors->has('display_order') ? ' has-danger' : ''}}">
                  <label class="col-sm-2">显示顺序</label>
                  <div class="col-sm-10">
                    <input id="" type="text" name="display_order" value="{{ old('display_order') }}" placeholder="display_order" class="form-control form-control-success">
                    @if($errors->has('display_order'))
                    <small class="form-text">{{ $errors->first('display_order') }}</small>
                    @endif
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