@foreach($permissions as $p)
<div class="custom-controls-stacked">
  <label class="custom-control custom-checkbox">
    <input name="permissions[]" value="{{ $p->name }}" type="checkbox" class="custom-control-input" {{ $info->hasPermissionTo($p) ? "checked" : ""}}>
    <span class="custom-control-indicator"></span>
    <span class="custom-control-description">{{ $p->display_name }}</span>
  </label>
</div>
@endforeach