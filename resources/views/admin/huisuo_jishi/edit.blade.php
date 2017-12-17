@extends('layouts.admin')
@section('title', $pageTitle)
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
              <h2 class="h5 display">{{ $pageTitle }}</h2>
            </div>
            <div class="card-block">
              <form id="form" class="form-horizontal form-huisuo-jishi" method="post" action="{{ route('huisuo.update', $info->id) }}">
              	{{ csrf_field() }}
              	{{ method_field('PATCH') }}
                <div class="form-group row{{$errors->has('name') ? ' has-danger' : ''}}">
                  <label class="col-sm-2">名称</label>
                  <div class="col-sm-10">
                    <input id="" type="text" name="name" value="{{ $info->name }}" placeholder="name" class="form-control form-control-success">
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-sm-2">封面</label>
                  <div class="col-sm-8">
                  	<input type="text" class="form-control" value="{{ $attachmentPresenter->getAttachmentImageLink($info->coverImage) }}" id="" placeholder="点右边上传" readOnly>
                  </div>
                  <div class="col-sm-2">
                  	<input type="file" style="display: none" class="form-control-file"  accept="image/jpg, image/jpeg, image/png, image/gif">
                  	<input type="button" class="btn btn-info select-image pointer" value="上传">
                  	<input type="hidden" class="" id="" name="cover" value="{{ $info->cover }}">
                  </div>
                </div>
                <div class="form-group row{{$errors->has('fid') ? ' has-danger' : ''}}">
                  <label class="col-sm-2">地址</label>
                  <div class="col-sm-10 select" style="display: flex">
                    @include('common.admin.cnarea_select')
                    @if($errors->has('fid'))
                    <small class="form-text">{{ $errors->first('fid') }}</small>
                    @endif
                  </div>
                </div>
                <div class="form-group row{{$errors->has('address') ? ' has-danger' : ''}}">
                  <label class="col-sm-2">详细地址</label>
                  <div class="col-sm-10">
                    <textarea rows="4" name="address" placeholder="address" class="form-control form-control-success">{{ $info->address }}</textarea>
                  </div>
                </div>
                <div class="form-group row{{$errors->has('description') ? ' has-danger' : ''}}">
                  <label class="col-sm-2">图文描述</label>
                  <div class="col-sm-10">
                    <div id="description"></div>
                  </div>
                </div>
                <div class="form-group row{{$errors->has('age') ? ' has-danger' : ''}}">
                  <label class="col-sm-2">年龄</label>
                  <div class="col-sm-10">
                    <input id="" type="number" min="16" max="60" name="age" value="{{ $info->age }}" placeholder="age" class="form-control form-control-success">
                  </div>
                </div>
                <div class="form-group row{{$errors->has('price') ? ' has-danger' : ''}}">
                  <label class="col-sm-2">价格</label>
                  <div class="col-sm-10">
                    <input id="" type="number" min="0" max="10000" name="price" value="{{ $info->price }}" placeholder="price" class="form-control form-control-success">
                  </div>
                </div>
                @foreach($info->contacts as $contact)
                <input type="hidden" name="contacts[id][]" value="{{ $contact->id }}">
                <div class="form-group row contacts">
                  <label class="col-sm-2">联系方式</label>
                  <div class="col-sm-2 contact-part" style="padding-left: 15px">
                    <select class="form-control" name="contacts[type][]">
                    	<option value="">选择类型</option>
                    	@foreach($contactTypes as $key => $value)
                    	<option value="{{ $key }}" {{ $contact->type == $key ? "selected" : "" }}>{{ $value }}</option>
                    	@endforeach
                    </select>
                  </div>
                  <div class="col-sm-3 contact-part">
                  	<input type="text" class="form-control" id="" name="contacts[account][]" value="{{ $contact['account'] }}" placeholder="填账号，右边二维码图片">
                  </div>
                  <div class="col-sm-3 contact-part">
                  	<input type="text" class="form-control" id="" value="{{ $contact->image ? $attachmentPresenter->getAttachmentImageLink($contact->image) : '' }}" placeholder="点右边上传" readOnly>
                  </div>
                  <div class="col-sm-1 contact-part">
                  	<input type="file" style="display: none" class="form-control-file"  accept="image/jpg, image/jpeg, image/png, image/gif">
                  	<input type="button" class="btn btn-info select-image pointer" value="上传">
                  	<input type="hidden" class="" id="" name="contacts[image][]" value="{{ $contact->image_id }}">
                  </div>
                  <div class="col-sm-1 contact-part">
                  	<i class="fa fa-times fa-2x" aria-hidden="true" style="color: red;cursor: pointer;margin-left: 10px" title="删除该联系方式"></i>
                  </div>
                </div>
                @endforeach
                <div class="form-group row">     
                  <div class="col-sm-10 offset-sm-2">
                    <input type="button" id="add-contacts" value="增加联系方式" class="btn btn-info cursor btn-sm">
                  </div>
                </div>
                <div class="form-group row"> 
                  <div class="col-sm-10 offset-sm-2">
                    <input type="button" value="提交" class="btn btn-primary pointer submit">
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
</section>
<script src="{{ asset('js/text_modal.js') }}"></script>
<script src="{{ asset('js/image_modal.js') }}"></script>
<script src="{{ asset('js/content_editor.js') }}"></script>
<script>
var contentEditor = new ContentEditor({
	wrapId: "description",
	uploadUrl: "{{ route('upload.image') }}",
	content: '{!! $info->description !!}'
});


//添加联系人
var $addContactsBtn = $('#add-contacts');
$addContactsBtn.click(function(){
	var $thisRow = $(this).closest(".form-group");
	$clone = $thisRow.prev().clone();
	$clone.find("input[type!=button]").val("");
	$clone.find("select").val("");
	$thisRow.before($clone);
})

//上传图片
var $form = $('#form');
$form.on("click", ".select-image", function(e) {
	$(this).prev().trigger("click");
})
$form.on("change", ".form-control-file", function(e) {
	if (!e.target.files || !e.target.files[0]) {
		return;
	}
	var file = e.target.files[0];
	var formData = new FormData();
	formData.append("image", file);
	var $parent = $(this).parent();
	$.post({
		url: "{{ route('upload.image') }}",
		type: "post",
		dataType: "json",
		contentType: false,
		processData: false,
		data: formData,
		beforeSend: function() {
			$parent.find(".select-image").attr("value", "上传中...").attr("disabled", true)
		}
	}).done(function(response) {
		console.log(response);
		if (response.ret == 0) {
			$parent.find("input[type=hidden]").val(response.data.id);
			$parent.prev().find("input").val(response.data.uri);
		} else {
			alert(response.msg);
		}
	}).error(function(xhr, errstr) {
		alert(errstr);
	}).always(function() {
		$parent.find(".select-image").attr("value", "上传").removeAttr("disabled");
	})
})

$form.on("click", ".submit", function() {
	var data = $form.serialize();
	data += "&description=" + JSON.stringify(contentEditor.getData());
	$.ajax({
		url: $form.attr("action"),
		type: "post",
		dataType: "json",
		data: data,
	}).done(function(response) {
		console.log(response);
		if (response.ret == 0) {
			alert("OK!");
		} else {
			alert(response.msg);
		}
	}).fail(function(xhr, errstr, errThrown) {
		var response = xhr.responseJSON;
		var msg = "";
		for (var i in response.data.errors) {
			msg += response.data.errors[i][0] + "\n";
		}
		alert(msg);
	}).always(function() {
		
	})
})

</script>
@stop