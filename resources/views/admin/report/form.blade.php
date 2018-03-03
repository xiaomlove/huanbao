@extends('layouts.admin')

@push('css')
    <link rel="stylesheet" href="{{ asset('vendor/bootstrap-slider/css/bootstrap-slider.min.css') }}">
@endpush

@section('title', ($report->id ? '编辑报告' : '新建报告'))

@section('content_header')
    <h2>

    </h2>
@stop

@section('content')
    @include('admin.common.message')
    @if($report->id)
        <form id="form" class="subject-form image-upload-form form-horizontal" method="post"
              action="{{ route('admin.report.update', $report->id) }}">
    {{ method_field('PATCH') }}
    @else
        <form id="form" class="subject-form image-upload-form form-horizontal" method="post"
              action="{{ route('admin.report.store') }}">
    @endif
            {{ csrf_field() }}
            <div class="form-group{{$errors->has('jishi_id') ? ' has-error' : ''}}">
                <label for="" class="col-sm-2 control-label">JS</label>
                <div class="col-sm-10">
                    <select id="forum" class="form-control" name="jishi_id">

                    </select>
                    @if($errors->has('jishi_id'))
                        <small class="help-block">{{ $errors->first('jishi_id') }}</small>
                    @endif
                </div>
            </div>
            <div class="form-group{{$errors->has('huisuo_id') ? ' has-error' : ''}}">
                <label for="" class="col-sm-2 control-label">HS</label>
                <div class="col-sm-10">
                    <select id="forum" class="form-control" name="huisuo_id">

                    </select>
                    @if($errors->has('huisuo_id'))
                        <small class="help-block">{{ $errors->first('huisuo_id') }}</small>
                    @endif
                </div>
            </div>
            <div class="form-group{{$errors->has('content') ? ' has-error' : ''}}">
                <label for="" class="col-sm-2 control-label">情况说明</label>
                <div class="col-sm-10">
                    <div id="content"></div>
                    @if($errors->has('content'))
                        <small class="help-block">{{ $errors->first('content') }}</small>
                    @endif
                </div>
            </div>
            <div class="form-group{{$errors->has('jishi_top_value') ? ' has-error' : ''}}">
                <label for="" class="col-sm-2 control-label">上</label>
                <div class="col-sm-10">
                    <input type="text" name="jishi_top_value" class="form-control" id="jishi_top_value" placeholder=""
                           value="{{ old('jishi_top_value', $report->jishi_top_value) }}">
                    @if($errors->has('jishi_top_value'))
                        <small class="help-block">{{ $errors->first('jishi_top_value') }}</small>
                    @endif
                </div>
            </div>
            <div class="form-group{{$errors->has('jishi_top_description') ? ' has-danger' : ''}}">
                <label class="col-sm-2 control-label">描述</label>
                <div class="col-sm-10">
                    <textarea rows="4" name="jishi_top_description" placeholder="" class="form-control form-control-success">{{ old("jishi_top_description", $report->jishi_top_description) }}</textarea>
                </div>
            </div>

            <div class="form-group{{$errors->has('jishi_middle_value') ? ' has-error' : ''}}">
                <label for="" class="col-sm-2 control-label">中</label>
                <div class="col-sm-10">
                    <input type="text" name="jishi_middle_value" class="form-control" id="jishi_middle_value" placeholder=""
                           value="{{ old('jishi_middle_value', $report->jishi_middle_value) }}">
                    @if($errors->has('jishi_middle_value'))
                        <small class="help-block">{{ $errors->first('jishi_middle_value') }}</small>
                    @endif
                </div>
            </div>
            <div class="form-group{{$errors->has('jishi_middle_description') ? ' has-danger' : ''}}">
                <label class="col-sm-2 control-label">描述</label>
                <div class="col-sm-10">
                    <textarea rows="4" name="jishi_middle_description" placeholder="" class="form-control form-control-success">{{ old("jishi_middle_description", $report->jishi_middle_description) }}</textarea>
                </div>
            </div>

            <div class="form-group slider-long{{$errors->has('jishi_bottom_value') ? ' has-error' : ''}}">
                <label for="" class="col-sm-2 control-label">下</label>
                <div class="col-sm-10">
                    <input type="text" name="jishi_bottom_value" class="form-control" id="jishi_bottom_value" placeholder=""
                           value="{{ old('jishi_middle_value', $report->jishi_middle_value) }}">
                    @if($errors->has('jishi_bottom_value'))
                        <small class="help-block">{{ $errors->first('jishi_bottom_value') }}</small>
                    @endif
                </div>
            </div>
            <div class="form-group{{$errors->has('jishi_bottom_description') ? ' has-danger' : ''}}">
                <label class="col-sm-2 control-label">描述</label>
                <div class="col-sm-10">
                    <textarea rows="4" name="jishi_bottom_description" placeholder="" class="form-control form-control-success">{{ old("jishi_bottom_description", $report->jishi_bottom_description) }}</textarea>
                </div>
            </div>

            <div class="form-group slider-long{{$errors->has('jishi_attitude_value') ? ' has-error' : ''}}">
                <label for="" class="col-sm-2 control-label">态度</label>
                <div class="col-sm-10">
                    <input type="text" name="jishi_attitude_value" class="form-control" id="jishi_attitude_value" placeholder=""
                           value="{{ old('jishi_attitude_value', $report->jishi_attitude_value) }}">
                    @if($errors->has('jishi_attitude_value'))
                        <small class="help-block">{{ $errors->first('jishi_attitude_value') }}</small>
                    @endif
                </div>
            </div>
            <div class="form-group{{$errors->has('jishi_attitude_description') ? ' has-danger' : ''}}">
                <label class="col-sm-2 control-label">描述</label>
                <div class="col-sm-10">
                    <textarea rows="4" name="jishi_attitude_description" placeholder="" class="form-control form-control-success">{{ old("jishi_attitude_description", $report->jishi_attitude_description) }}</textarea>
                </div>
            </div>

            <div class="form-group slider-long{{$errors->has('jishi_technique_value') ? ' has-error' : ''}}">
                <label for="" class="col-sm-2 control-label">技术</label>
                <div class="col-sm-10">
                    <input type="text" name="jishi_technique_value" class="form-control" id="jishi_technique_value" placeholder=""
                           value="{{ old('jishi_technique_value', $report->jishi_technique_value) }}">
                    @if($errors->has('jishi_technique_value'))
                        <small class="help-block">{{ $errors->first('jishi_technique_value') }}</small>
                    @endif
                </div>
            </div>
            <div class="form-group{{$errors->has('jishi_technique_description') ? ' has-danger' : ''}}">
                <label class="col-sm-2 control-label">描述</label>
                <div class="col-sm-10">
                    <textarea rows="4" name="jishi_technique_description" placeholder="" class="form-control form-control-success">{{ old("jishi_technique_description", $report->jishi_technique_description) }}</textarea>
                </div>
            </div>

            <div class="form-group slider-long{{$errors->has('jishi_figure_value') ? ' has-error' : ''}}">
                <label for="" class="col-sm-2 control-label">ShenCai</label>
                <div class="col-sm-10">
                    <input type="text" name="jishi_figure_value" class="form-control" id="jishi_figure_value" placeholder=""
                           value="{{ old('jishi_figure_value', $report->jishi_figure_value) }}">
                    @if($errors->has('jishi_figure_value'))
                        <small class="help-block">{{ $errors->first('jishi_figure_value') }}</small>
                    @endif
                </div>
            </div>
            <div class="form-group{{$errors->has('jishi_figure_description') ? ' has-danger' : ''}}">
                <label class="col-sm-2 control-label">描述</label>
                <div class="col-sm-10">
                    <textarea rows="4" name="jishi_figure_description" placeholder="" class="form-control form-control-success">{{ old("jishi_figure_description", $report->jishi_figure_description) }}</textarea>
                </div>
            </div>

            <div class="form-group slider-long{{$errors->has('jishi_appearance_value') ? ' has-error' : ''}}">
                <label for="" class="col-sm-2 control-label">YanZhi</label>
                <div class="col-sm-10">
                    <input type="text" name="jishi_appearance_value" class="form-control" id="jishi_appearance_value" placeholder=""
                           value="{{ old('jishi_appearance_value', $report->jishi_appearance_value) }}">
                    @if($errors->has('jishi_appearance_value'))
                        <small class="help-block">{{ $errors->first('jishi_appearance_value') }}</small>
                    @endif
                </div>
            </div>
            <div class="form-group{{$errors->has('jishi_appearance_description') ? ' has-danger' : ''}}">
                <label class="col-sm-2 control-label">描述</label>
                <div class="col-sm-10">
                    <textarea rows="4" name="jishi_appearance_description" placeholder="" class="form-control form-control-success">{{ old("jishi_appearance_description", $report->jishi_appearance_description) }}</textarea>
                </div>
            </div>

            <div class="form-group slider-long{{$errors->has('huisuo_environment_facility_value') ? ' has-error' : ''}}">
                <label for="" class="col-sm-2 control-label">环境设施</label>
                <div class="col-sm-10">
                    <input type="text" name="huisuo_environment_facility_value" class="form-control" id="huisuo_environment_facility_value" placeholder=""
                           value="{{ old('huisuo_environment_facility_value', $report->huisuo_environment_facility_value) }}">
                    @if($errors->has('jishi_appearance_value'))
                        <small class="help-block">{{ $errors->first('huisuo_environment_facility_value') }}</small>
                    @endif
                </div>
            </div>
            <div class="form-group{{$errors->has('huisuo_environment_facility_description') ? ' has-danger' : ''}}">
                <label class="col-sm-2 control-label">描述</label>
                <div class="col-sm-10">
                    <textarea rows="4" name="huisuo_environment_facility_description" placeholder="" class="form-control form-control-success">{{ old("huisuo_environment_facility_description", $report->huisuo_environment_facility_description) }}</textarea>
                </div>
            </div>

            <div class="form-group slider-long{{$errors->has('huisuo_service_attitude_value') ? ' has-error' : ''}}">
                <label for="" class="col-sm-2 control-label">服务态度</label>
                <div class="col-sm-10">
                    <input type="text" name="huisuo_service_attitude_value" class="form-control" id="huisuo_service_attitude_value" placeholder=""
                           value="{{ old('huisuo_service_attitude_value', $report->huisuo_service_attitude_value) }}">
                    @if($errors->has('huisuo_service_attitude_value'))
                        <small class="help-block">{{ $errors->first('huisuo_service_attitude_value') }}</small>
                    @endif
                </div>
            </div>
            <div class="form-group{{$errors->has('huisuo_service_attitude_description') ? ' has-danger' : ''}}">
                <label class="col-sm-2 control-label">描述</label>
                <div class="col-sm-10">
                    <textarea rows="4" name="huisuo_service_attitude_description" placeholder="" class="form-control form-control-success">{{ old("huisuo_service_attitude_description", $report->huisuo_service_attitude_description) }}</textarea>
                </div>
            </div>



            {{ var_dump($errors) }}

            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10 text-center">
                    <input type="submit" value="提交" class="btn btn-primary">
                </div>
            </div>
        </form>
@stop

@section('js')
    <script src="{{ asset('vendor/fileupload/jQuery-File-Upload-9.19.1/js/vendor/jquery.ui.widget.js') }}"></script>
    <script src="{{ asset('vendor/fileupload/jQuery-File-Upload-9.19.1/js/jquery.fileupload.js') }}"></script>
    <script src="{{ asset('js/text_modal.js') }}"></script>
    <script src="{{ asset('js/image_modal.js') }}"></script>
    <script src="{{ asset('js/content_editor.js') }}"></script>
    <script src="{{ asset('vendor/bootstrap-slider/bootstrap-slider.min.js') }}"></script>

    <script>
        var tid = '{{ $report->tid }}';
        $('#forum').select2();
        var contentEditor = new ContentEditor({
            wrapId: "content",
            uploadUrl: "{{ route('admin.upload.image') }}",
            content: '{!! $report->topic ? $report->topic->mainFloor->detail->content : "" !!}'
        });
        var commonTicks = [0, 2, 4, 6, 8, 10];
        var commonTicksLabels = ["零分", "极差", "较差", "一般", "优秀", "完美"];
        var commonTicksPositions = [0, 20, 40, 60, 80, 100];

        $('#jishi_top_value').slider({
            ticks: [0, 3, 5, 6],
            ticks_positions: [0, 50, 66.67, 100],
            ticks_labels: ["None", "PZ", "JW", "SW"],
            value: 3
        })
        $('#jishi_middle_value').slider({
            ticks: [0, 3, 5, 6],
            ticks_positions: [0, 50, 66.67, 100],
            ticks_labels: ["None", "M", "T+M", "T+M+T"],
            value: 3
        })
        $('#jishi_bottom_value').slider({
            ticks: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10],
            ticks_positions: [0, 10, 20, 30, 40, 50, 60, 70, 80, 90, 100],
            ticks_labels: ["None", "M", "M+P", "S+M", "S+M+P", "T", "T+T", "ZC", "MB", "PP", "WTNS"],
            value: 3
        })
        let sliderFields = ["jishi_attitude_value", "jishi_technique_value", "jishi_figure_value", "jishi_appearance_value", "huisuo_environment_facility_value", "huisuo_service_attitude_value"];
        sliderFields.map(id => {
            $('#' + id).slider({
                step: 0.5,
                ticks: commonTicks,
                ticks_positions: commonTicksPositions,
                ticks_labels: commonTicksLabels,
                value: 5
            })
        })

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
@stop
