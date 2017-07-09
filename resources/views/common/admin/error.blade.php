@if (count($errors))
<div class="alert alert-danger alert-dismissible fade show" role="alert">
	<button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
    <ul class="list-unstyled">
	@foreach($errors->all() as $error)
	<li>{{ $error }}</li>
	@endforeach
	</ul>
</div>
@endif
