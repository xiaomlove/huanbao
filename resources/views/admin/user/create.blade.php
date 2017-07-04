@extends('layouts.admin')
@section('title', '新建用户')
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
              <h2 class="h5 display">新建用户</h2>
            </div>
            <div class="card-block">
              <form class="form-horizontal" method="post" action="{{ route('user.store') }}">
              	{{ csrf_field() }}
                <div class="form-group row{{$errors->has('email') ? ' has-danger' : ''}}">
                  <label class="col-sm-2">Email</label>
                  <div class="col-sm-10">
                    <input id="" type="email" name="email" value="{{ old('email') }}" placeholder="邮箱地址" class="form-control form-control-success">
                    @if($errors->has('email'))
                    <small class="form-text">{{ $errors->first('email') }}</small>
                    @endif
                  </div>
                </div>
                <div class="form-group row{{$errors->has('password') ? ' has-danger' : ''}}">
                  <label class="col-sm-2">Password</label>
                  <div class="col-sm-10">
                    <input id="" type="password" name="password" value="{{ old('password') }}" placeholder="密码" class="form-control form-control-warning">
                    @if($errors->has('password'))
                    <small class="form-text">{{ $errors->first('password') }}</small>
                    @endif
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-sm-2">Password Confirm</label>
                  <div class="col-sm-10">
                    <input id="" type="password" name="password_confirmation" value="{{ old('password_confirmation') }}" placeholder="确认密码" class="form-control form-control-warning">
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