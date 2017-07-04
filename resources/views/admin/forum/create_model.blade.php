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
            {!! Form::model($forum, ['route' => $route, 'class' => 'form-horizontal']) !!}
            	<div class="form-group row">
            		{!! Form::label('name', '名称', ['class' => 'col-sm-2']) !!}
            		<div class="col-sm-10">
            		{!! Form::text('name', ['class' => 'form-control form-control-success']) !!}
            		</div>
            	</div>
            {!! Form::close() !!}
            </div>
          </div>
        </div>
      </div>
    </div>
</section>
@stop