<div class="form-group{{$errors->has($imageFieldName) ? ' has-error' : ''}}">
    <label for="" class="col-sm-2 control-label">{{ $imageFieldLabel }}</label>
    <div class="col-sm-8">
        <input type="text" name="{{ $imageFieldName}}" class="form-control" placeholder="图片地址，确保域名为 {{ config('filesystems.disks.qiniu.domains.default') }} 且能正常打开，或点击右边上传" value="{{ old($imageFieldName, $imageFieldObject->$imageFieldName) }}">
        @if($errors->has($imageFieldName))
            <small class="help-block">{{ $errors->first($imageFieldName) }}</small>
        @endif
    </div>
    <div class="col-sm-2">
        <input type="file" class="upload">
        <a class="preview" href="{{ old($imageFieldName, $imageFieldObject->$imageFieldName) }}" target="_blank"><img src="{{ old($imageFieldName, $imageFieldObject->$imageFieldName) }}" /></a>
    </div>
</div>

@push('js')
    <script src="{{ asset('vendor/fileupload/jQuery-File-Upload-9.19.1/js/vendor/jquery.ui.widget.js') }}"></script>
    <script src="{{ asset('vendor/fileupload/jQuery-File-Upload-9.19.1/js/jquery.fileupload.js') }}"></script>
    <script>
        $('.upload').fileupload({
            dataType: 'json',
            paramName: 'image',
            url: "{{ route('admin.upload.image') }}",
            done: function (e, data) {
                var $this = $(this);
                var url = data.result.data.url;
                $this.closest('div').prev().find("input").val(url);
                $this.closest('div').find(".preview")
                    .attr('href', url)
                    .find("img").attr("src", url);
            },
            fail: function (e, data, jqXHR) {
                console.log(jqXHR);
            }
        });
    </script>
@endpush