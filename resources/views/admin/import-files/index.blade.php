@extends('admin.layout.master')
@section('title') CSV Uploader @stop


@section('page_name')
    CSV Management
    <small>Upload csv</small>
@stop

@section ('breadcrumbs')
    <li> <a href="{!! route('admin.dashboard') !!}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
@stop

@section('content')
    <div class="m-portlet">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <h3 class="m-portlet__head-text">
                        <a href="{!! url('resources/sample_csv/provati-data-entry-sample.csv') !!}" class="btn btn-sm m-btn--pill    btn-outline-success">
                            <span class="la la-file-excel-o"></span>&nbsp; Sample CSV download
                        </a>
                    </h3>
                </div>
            </div>
        </div>
        <div class="m-portlet__body">
            <div class="m-section">
                <div class="m-section__content">
                    <form method="post" action="{{route('admin.import.save.files')}}" enctype="multipart/form-data">
                        {{csrf_field()}}
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 pt-0 {!! $errors->has('import_files') ? 'has-error' : '' !!}">
                                <div class="m-radio-inline">
                                    <label class="m-radio">
                                        <input type="radio" name="upload_type" value="1" checked>
                                        Instant Upload (maximum: 100 rows)
                                        <span></span>
                                    </label>
                                    <label class="m-radio">
                                        <input type="radio" name="upload_type" value="2">
                                        Delay Upload (more than 100 rows)
                                        <span></span>
                                    </label>
                                    <p>{!! $errors->first('upload_type', '<label class="error_txt_size">:message</label>') !!}</p>
                                </div>
                                <span class="m-form__help">
                                        Choose wisely to upload your csv
                                </span>
                                <div>
                                    <b>Important Notes *
                                        <ul>
                                            <li>Maximum file size 2 mb</li>
                                            <li>If a file have more than 100 rows it will automatically execute delay upload option</li>
                                        </ul>
                                    </b>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 pt-4 {!! $errors->has('import_files') ? 'has-error' : '' !!}">
                                <label>Upload your CSV file <span class="text-danger">*</span></label>
                                {!! Form::file('import_files', null, ['class' => 'form-control m-input','id'=>'import_files','value'=>Input::old('import_files'), 'placeholder' => '', 'tabindex' => '1']) !!}
                                {!! $errors->first('import_files', '<label class="error_txt_size">:message</label>') !!}
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 pt-4">
                                <button type="submit" class="btn btn-primary">Save</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="m-portlet__foot clearfix">
            <div class="pull-right">
            </div>
        </div>
        <!--end::Section-->
    </div>
@stop