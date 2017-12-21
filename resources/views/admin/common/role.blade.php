@foreach($roles as $r)
<label class="custom-control custom-checkbox">
  <input type="checkbox" name="roles[]" class="custom-control-input" value="{{ $r->name }}" {{ $user->hasRole($r) ? "checked" : ""}}>
  <span class="custom-control-indicator"></span>
  <span class="custom-control-description">{{ $r->display_name }}</span>
</label>
@endforeach