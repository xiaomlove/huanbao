@foreach(['success', 'info', 'warning', 'danger'] as $type)
@if (session()->has($type))
<div class="alert alert-{{ $type }} alert-dismissible fade show" role="alert">
	<button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
	{{ session()->get($type) }}
</div>
@endif
@endforeach