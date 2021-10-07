@extends('admin.layout.master')
@section('title') System Settings @stop

@section('page_name')
    System Settings Management
    <small>System Settings</small>
@stop

@section ('breadcrumbs')
    <li> <a href="{!! route('admin.dashboard') !!}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
    <li class="active"> <a href=""> System Settings </a> </li>
@stop

@section('content')
    <div class="row">
        <div class="col-lg-3">
            <div class="m-portlet m-portlet--full-height ">
                <div class="m-portlet__body">
                    <ul class="nav nav-tabs m-nav m-nav--hover-bg m-portlet-fit--sides" role="tablist">
                        <li class="m-nav__separator m-nav__separator--fit"></li>
                        <li class="m-nav__section m--hide">
                            <span class="m-nav__section-text">
                                Section
                            </span>
                        </li>
                        <li class="m-nav__item">
                            <a href="" class="m-nav__link">
                                <i class="m-nav__link-icon flaticon-time-3"></i>
                                <span class="m-nav__link-text">
                                    Company
                                </span>
                            </a>
                        </li>
                        <li class="m-nav__item">
                            <a class="m-nav__link">
                                <i class="m-nav__link-icon flaticon-lifebuoy"></i>
                                <span class="m-nav__link-text">
                                    Employer
                                </span>
                            </a>
                        </li>
                    </ul>
                    <div class="m-portlet__body-separator"></div>
                </div>
            </div>
        </div>
        <div class="col-lg-9">
            <div class="m-portlet m-portlet--full-height m-portlet--tabs ">
                <div class="m-portlet__head">
                    <div class="m-portlet__head-tools">
                        <ul class="nav nav-tabs m-tabs m-tabs-line   m-tabs-line--left m-tabs-line--primary" role="tablist">
                            <li class="nav-item m-tabs__item">
                                <a class="nav-link m-tabs__link active" data-toggle="tab" href="#m_user_profile_tab_1-" role="tab">
                                    <i class="flaticon-share m--hide"></i>
                                    Settings Main
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="tab-content">
                    <div class="tab-pane active" id="m_user_profile_tab_1">
                        <form class="m-form m-form--fit m-form--label-align-right" method="post" action="{{route('admin.system-settings.update')}}" id="settings-main-form">
                            {{csrf_field()}}
                            <div class="m-portlet__body">
                                <div class="form-group m-form__group m--margin-top-10 m--hide">
                                    <div class="alert m-alert m-alert--default" role="alert">
                                        The example form below demonstrates common HTML form elements that receive updated styles from Bootstrap with additional classes.
                                    </div>
                                </div>
                                <div class="form-group m-form__group row">
                                    <label for="example-text-input" class="col-2 col-form-label">
                                        Company Name
                                    </label>
                                    <div class="col-7 {!! $errors->has('company_id') ? 'has-error' : '' !!}">
                                        <input type="text" class="form-control" name="" id="" readonly="" value="{{!empty($companies) ? $companies->name : ''}}">
                                        <input type="hidden" class="form-control" name="company_id" id="company_id" readonly="" value="{{!empty($companies) ? $companies->id : ''}}">

                                        {!! $errors->first('company_id', '<label class="error_txt_size">:message</label>') !!}
                                    </div>
                                </div>
                                @forelse($getData as $val)
                                <div class="form-group m-form__group row">
                                    <label for="example-text-input" class="col-2 col-form-label">
                                        {{strtoupper(str_replace("_"," ",$val->config_key))}}
                                    </label>
                                    <div class="col-7 {!! $errors->has('config_value') ? 'has-error' : '' !!}">
                                        <input class="form-control m-input" type="text" value="{{$val->config_value}}" name="config_value[]" placeholder="Enter app version">
                                        <input type="hidden" name="config_key[]" value="{{$val->config_key}}">
                                        <input type="hidden" name="row_id[]" value="{{$val->id}}">
                                        {!! $errors->first('config_value', '<label class="error_txt_size">:message</label>') !!}
                                    </div>
                                </div>
                                    @empty
                                    <p>No Data found</p>
                                @endforelse
                            </div>
                            <div class="m-portlet__foot m-portlet__foot--fit">
                                <div class="m-form__actions">
                                    <div class="row">
                                        <div class="col-2"></div>
                                        <div class="col-7">
                                            <button type="submit" class="btn btn-accent m-btn m-btn--air m-btn--custom">
                                                Save changes
                                            </button>
                                            &nbsp;&nbsp;
                                            <a href="{{url('admin/system-settings')}}" class="btn btn-secondary m-btn m-btn--air m-btn--custom">
                                                Cancel
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="tab-pane" id="m_user_profile_tab_4">tab three</div>
                </div>
            </div>
        </div>
    </div>
    <style>
        a.m-nav__link.active {
            background-color: #eeeeee;
            border-color: #eee #eee #ddd;
            border-radius: 0px!important;
        }
    </style>
    <!-- Jquery validator js -->
    <script src="{!! asset('backend/ezzyr_assets/jquery_validator/jquery.validate.js') !!}" type="text/javascript"></script>
    <script src="{!! asset('backend/ezzyr_assets/jquery_validator/additional_methods.js') !!}" type="text/javascript"></script>
    <script>
        {{--$(document).ready(function(){--}}
            {{--$("#settings-main-form").validate({--}}
                {{--rules: {--}}
                    {{--// simple rule, converted to {required:true}--}}
                    {{--company_id: {--}}
                        {{--required: true,--}}
                        {{--remote: {--}}
                            {{--url: "{{route('admin.system-settings.check')}}",--}}
                            {{--type: "post",--}}
                            {{--data: {--}}
                                {{--"_token":"{{csrf_token()}}",--}}
                                {{--"type":"update",--}}
                                {{--company_id: function() {--}}
                                    {{--return $( "#company_id" ).val();--}}
                                {{--}--}}
                            {{--}--}}
                        {{--}--}}
                    {{--},--}}
                {{--},--}}

                {{--messages:{--}}
                  {{--company_id: {--}}
                      {{--remote: "Settings already added for this company.",--}}
                  {{--}--}}
                {{--},--}}
                {{--errorElement : 'label',--}}
                {{--errorClass : 'error_txt_size',--}}
            {{--});--}}
            {{--$(".reset-form").click(function() {--}}
                {{--$(':input','#employee-details').not(':button, :submit, :reset, :hidden').val('').removeAttr('checked').removeAttr('selected');--}}
            {{--})--}}
        {{--});--}}
    </script>
@stop