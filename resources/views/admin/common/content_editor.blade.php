<div class="form-group{{$errors->has($contentFieldName) ? ' has-error' : ''}}">
    <label for="" class="col-sm-2 control-label">{{ $contentFieldLabel }}</label>
    <div class="col-sm-10">
        <div id="{{ $contentFieldName }}"></div>
        @if($errors->has($contentFieldName))
            <small class="help-block">{{ $errors->first($contentFieldName) }}</small>
        @endif
    </div>
</div>

@push('js')
    <script src="https://unpkg.com/qiniu-js@2.2.1/dist/qiniu.min.js"></script>
    <script src="{{ asset('js/text_modal.js') }}"></script>
    <script src="{{ asset('js/image_modal.js') }}"></script>
    <script src="{{ asset('js/content_editor.js') }}"></script>
    <script>
        var {{ $contentFieldName }}ContentEditor = new ContentEditor({
            wrapId: "{{ $contentFieldName }}",
            uploadUrl: "{{ route('admin.upload.image') }}",
            tokenUrl: "{{ route('admin.upload.token') }}",
            content: '{!! $contentFieldValue !!}'
        });
        var $form = $('#{{ $contentFieldName }}').closest("form");
        $form.on("submit", function (e) {
            let $input = $('<input>');
            let value = JSON.stringify({{ $contentFieldName }}ContentEditor.getData());
            $input.attr("name", "{{ $contentFieldName }}").val(value).appendTo($form);
        })
    </script>
@endpush