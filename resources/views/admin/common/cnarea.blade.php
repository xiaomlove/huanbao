<div class="form-group {{ $errors->hasAny(['province', 'city', 'district']) ? 'has-error' : '' }}">
    <label class="col-sm-2 control-label">地区</label>
    <div class="col-sm-10 select"  id="location">
        <div style="display: flex;">
            <select name="province" class="form-control" data-uri="{{ route('admin.cnarea.province') }}" data-value="{{ old('province', $cnareaFieldObject->province) }}">
                <option value="">省</option>
            </select>
            <select name="city" class="form-control" data-uri="{{ route('admin.cnarea.city') }}" data-value="{{ old('city', $cnareaFieldObject->city) }}">
                <option value="">市</option>
            </select>
            <select name="district" class="form-control" data-uri="{{ route('admin.cnarea.district') }}" data-value="{{ old('district', $cnareaFieldObject->district) }}">
                <option value="">区</option>
            </select>
        </div>
        @if($errors->has('province'))
            <small class="help-block">{{ $errors->first('province') }}</small>
        @elseif($errors->has('city'))
            <small class="help-block">{{ $errors->first('city') }}</small>
        @elseif($errors->has('district'))
            <small class="help-block">{{ $errors->first('district') }}</small>
        @endif
    </div>
</div>

@push('js')
    <script src="{{ asset('js/cn_area_select.js') }}"></script>
    <script>
        CnSelect.init("location");
    </script>
@endpush

