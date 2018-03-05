@extends('adminlte::page')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/main.css') }}">
    <script>
        var BASE_URI = "{{ url('/') }}/";
        var ATTACHMENT_BASE_URI = "//{{ trim(config('filesystems.disks.qiniu.domains.default'), '/') }}/";
    </script>
@stop

