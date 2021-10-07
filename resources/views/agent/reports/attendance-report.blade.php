@extends('agent.layout.master')
@section('title') Attendance Report @stop


@section('page_name')
    Attendance Report
@stop

@section ('breadcrumbs')
    <li> <a href="{!! route('agent.dashboard') !!}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
    <li class="active"> {!! link_to_route('agent.report.attendance-report', 'Attendance Report') !!} </li>
@stop

@section('content')
    <div class="m-portlet">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <h3 class="m-portlet__head-text">
                        Monthly Attendance Report
                    </h3>
                </div>
            </div>
        </div>
        <div class="m-portlet__body">
            <div class="m-section">
                <div class="m-section__content">
                    <!--begin::Form-->
                    {!! Form::open(['route' => 'agent.report.attendance-report-export', 'role' => 'form', 'method' => 'post', 'id' => "create-passenger"]) !!}
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <div class="m-form m-form--fit m-form--label-align-right ">
                        <div class="m-portlet__body">
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="form-group m-form__group row">
                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 {!! $errors->has('employee_type') ? 'has-error' : '' !!}">
                                            <label>Employee Type <span class="text-danger">*</span></label>
                                            {!! Form::select('employee_type', $roles,  null, array('class' => 'form-control m-input m-bootstrap-select m_selectpicker', 'id' => 'select_employee_type', 'value'=>Input::old('employee_type'), 'tabindex' => '1')) !!}
                                            {!! $errors->first('employee_type', '<label class="error_txt_size">:message</label>') !!}
                                        </div>
                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 {!! $errors->has('gender') ? 'has-error' : ''  !!} ">
                                            <label> Select Month </label>
                                            <select class="form-control m-select2" id="month_select" name="month">
                                                <option value="1"> January </option>
                                                <option value="2"> February </option>
                                                <option value="3"> March </option>
                                                <option value="4"> April </option>
                                                <option value="5"> May </option>
                                                <option value="6"> June </option>
                                                <option value="7"> July </option>
                                                <option value="8"> August </option>
                                                <option value="9"> September </option>
                                                <option value="10"> October </option>
                                                <option value="11"> November </option>
                                                <option value="12"> December </option>
                                            </select>
                                        </div>
                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 {!! $errors->has('year') ? 'has-error' : ''  !!} ">
                                            <label>Year<span class="text-danger">*</span></label>
                                            {!! Form::select('year', array('2' => '2019', '1' => '2018', '0' => '2017'),null,array('class' => 'form-control m-input m-bootstrap-select m_selectpicker', 'value'=>Input::old('year'), 'tabindex' => '6')) !!}
                                            {!! $errors->first('year', '<label class="error_txt_size">:message</label>') !!}
                                        </div>
                                    </div>
                                    <div class="form-group m-form__group row">
                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                            <button class="btn btn-default show-report-btn" tabindex="20">
                                                <i class="fa fa-search text-success fa-lg" aria-hidden="true"></i>
                                                <span class="text-success"> Show Report </span>
                                            </button>
                                        </div>
                                    </div>
                                </div><!--END LEFT COL -->
                            </div>
                        </div>
                    </div>
                {!! Form::close() !!}
                <!--end::Form-->
                </div>
            </div>
        </div>
        <!--end::Section-->
    </div>
    <!--end::Portlet-->

    <script>
        //== Class definition
        var Select2 = function() {
            var demos = function() {
                // basic
                $('#month_select').select2();
                /*$('#merchant_select').select2({
                    placeholder: "Select a Merchant"
                });*/
            }
            //== Public functions
            return {
                init: function() {
                    demos();
                }
            };
        }();

        //== Initialization
        jQuery(document).ready(function() {
            Select2.init();
        });
    </script>
    <script>
        /*$(document).ready(function () {
            $('.show-report-btn').on('click', function () {
                alert('oK');
            })
        })*/
    </script>
@stop