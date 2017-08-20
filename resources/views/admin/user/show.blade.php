@extends('layouts.admin')
@section('title', '用户主页')
@section('content')
@inject('attachmentPresenter', 'App\Presenters\AttachmentPresenter')
<div class="breadcrumb-holder">
    <div class="container-fluid">
      <ul class="breadcrumb">
        <li class="breadcrumb-item"><a href="index.html">Home</a></li>
        <li class="breadcrumb-item active">Forms</li>
      </ul>
    </div>
</div>
<section id="section-user-main-profile" class="forms">
    <div class="container-fluid">
      @include('common.admin.message')
      <header> 
        <h1 class="h3 display">用户主页</h1>
      </header>
      <form class="form-horizontal form-user-main-profile row" method="post" action="{{ route('user.update', $user->id) }}" enctype="multipart/form-data">
        {{ csrf_field() }}
        {{ method_field('PATCH') }}
        <div class="col-lg-4">
          <div class="card">
            <div class="card-header d-flex align-items-center">
              <h2 class="h5 display">头像</h2>
            </div>
            <div class="card-block avatar-box">
            	@if(count($user->avatars))
            	<p>
            		<img src="{{ $attachmentPresenter->getAttachmentImageLink($user->avatars->first(), '200x200') }}" class="img-thumbnail" />
            		<input type="hidden" name="attachment_id" value="{{$user->avatars->first()->id}}" />
            	</p>
            	@endif
            	<p><input type="file" name="image" /></p> 
            </div>
          </div>
        </div>
        <div class="col-lg-8">
          <div class="card">
            <div class="card-header d-flex align-items-center">
              <h2 class="h5 display">基本信息</h2>
            </div>
            <div class="card-block">
            	<div class="form-group row{{$errors->has('name') ? ' has-danger' : ''}}">
                  <label class="col-sm-2">Name</label>
                  <div class="col-sm-10">
                    <input id="" type="text" name="name" value="{{ old('name', $user->name) }}" placeholder="用户名" class="form-control form-control-success">
                    @if($errors->has('name'))
                    <small class="form-text">{{ $errors->first('name') }}</small>
                    @endif
                  </div>
                </div>
                <div class="form-group row{{$errors->has('email') ? ' has-danger' : ''}}">
                  <label class="col-sm-2">Email</label>
                  <div class="col-sm-10">
                    <input id="" type="email" name="email" value="{{ old('email', $user->email) }}" placeholder="邮箱地址" class="form-control form-control-success">
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
            </div>
          </div>
        </div>
      </form>
    </div>
</section>
<script>

</script>
@stop