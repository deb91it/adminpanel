@extends('admin.layout.master')
@section('title') Create New Company @stop


@section('page_name')
    Company Management
    <small>New Company</small>
@stop

@section ('breadcrumbs')
    <li> <a href="{!! route('admin.dashboard') !!}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
    <li class="active"> {!! link_to_route('admin.agents', 'Company Management') !!} </li>
    <li class="active"> {!! link_to_route('admin.company.new', 'New Company') !!} </li>
@stop

@section('content')
    

    <!--begin::Portlet-->
    <div class="m-portlet">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <span class="m-portlet__head-icon m--font-brand">
                        <i class="fa fa-plus"></i>
                    </span>
                    <h3 class="m-portlet__head-text">
                        Add Company
                    </h3>
                </div>
            </div>
        </div>
        <!--begin::Form-->
        {!! Form::open(['route' => 'admin.company.store', 'role' => 'form', 'method' => 'post', 'id' => "add-company", 'files'=>true]) !!}
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <div class="m-form m-form--fit m-form--label-align-right ">
            <div class="m-portlet__body">
                <div class="row">
                    <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12 col-lg-offset-2 col-md-offset-2 col-sm-offset-2">
                        <div class="form-group m-form__group row">
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 {!! $errors->has('name') ? 'has-error' : '' !!}">
                                <label>Company Name <span class="text-danger">*</span></label>
                                {!! Form::text('name', null, ['class' => 'form-control m-input','id'=>'name','value'=>Input::old('name'), 'placeholder' => 'Enter Company Name', 'tabindex' => '1']) !!}
                                {!! $errors->first('name', '<label class="error_txt_size">:message</label>') !!}
                            </div>

                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 {!! $errors->has('moto') ? 'has-error' : '' !!}">
                                <label>Company moto {{--<span class="text-danger">*</span>--}}</label>
                                {!! Form::text('moto', null, ['class' => 'form-control m-input', 'id'=>'moto','value'=>Input::old('moto'), 'placeholder' => 'Enter Company Moto', 'tabindex' => '2']) !!}
                                {!! $errors->first('moto', '<label class="error_txt_size">:message</label>') !!}
                            </div>
                        </div>

                        <div class="form-group m-form__group row">
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 {!! $errors->has('group_name') ? 'has-error' : '' !!}">
                                <label>Group name (if has) {{--<span class="text-danger">*</span>--}}</label>
                                {!! Form::text('group_name', null, ['class' => 'form-control m-input', 'id'=>'group_name','value'=>Input::old('group_name'), 'placeholder' => 'Enter Group Name', 'tabindex' => '3']) !!}
                                {!! $errors->first('group_name', '<label class="error_txt_size">:message</label>') !!}
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 {!! $errors->has('agent') ? 'has-error' : '' !!}">
                                <label>Agent <span class="text-danger">*</span></label>
                                {{--{!! Form::select('agent[]', $agents, null, array('multiple' => true, 'class' => 'form-control m-input m-bootstrap-select m_m_selectpicker3', 'value'=>Input::old('agent'), 'tabindex' => '4')) !!}--}}
                                <select class="form-control m-input m-bootstrap-select m_m_selectpicker3" name="agent[]" multiple>
                                    <option value=""> Select agent </option>>
                                    @foreach($agents as $agent)
                                        <option value="{{ $agent->id }}" @if(Input::old('agent') && in_array($agent->id, Input::old('agent'))) {{ 'selected' }} @endif>{{ $agent->name }}</option>
                                    @endforeach
                                </select>
                                {!! $errors->first('agent', '<label class="error_txt_size">:message</label>') !!}
                            </div>
                        </div>

                        <div class="form-group m-form__group row">
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 {!! $errors->has('logo') ? 'has-error' : '' !!}">
                                <label>Logo <span class="text-danger">*</span></label>
                                {!! Form::file('logo', null, ['class' => 'form-control','id'=>'logo', 'tabindex' => '6']) !!}
                                {!! $errors->first('logo', '<label class="error_txt_size">:message</label>') !!}
                            </div>

                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 {!! $errors->has('address') ? 'has-error' : ''  !!} ">
                                <label>Address <span class="text-danger">*</span></label>
                                {!! Form::textarea('address',null,['class'=>'form-control', 'rows' => 2, 'cols' => 40, 'value'=>Input::old('address'), 'placeholder' => 'Enter Company address']) !!}
                                {!! $errors->first('address', '<label class="error_txt_size">:message</label>') !!}
                            </div>
                        </div>

                        <div class="form-group m-form__group row">
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 {!! $errors->has('country') ? 'has-error' : '' !!}">
                                <label>Country <span class="text-danger">*</span></label>
                                {!! Form::select('country', $countries, null, array('class' => 'form-control m-input m-bootstrap-select m_m_selectpicker3 selectCountry', 'value'=>Input::old('country'), 'tabindex' => '11')) !!}
                                {!! $errors->first('country', '<label class="error_txt_size">:message</label>') !!}
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 {!! $errors->has('city') ? 'has-error' : '' !!}">
                                <label>City <span class="text-danger">*</span></label>
                                {!! Form::select('city', ['First select country'], null, array('class' => 'form-control m-input', 'id' => 'city_select', 'value'=>Input::old('city'), 'tabindex' => '12')) !!}
                                {!! $errors->first('city', '<label class="error_txt_size">:message</label>') !!}
                            </div>
                        </div>
                        <div class="form-group m-form__group row">

                        </div>
                    </div><!--END LEFT COL -->
                </div>
            </div>
            <div class="m-portlet__foot m-portlet__no-border m-portlet__foot--fit">
                <div class="m-form__actions m-form__actions--solid">
                    <div class="row">
                        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12 col-lg-offser-2 col-md-offset-2 col-sm-offset-2">
                            <div class="pull-left">
                                <button class="btn btn-default" tabindex="20">
                                    <i class="fa fa-check-circle text-success fa-lg" aria-hidden="true"></i>
                                    <span class="text-success"> Save </span>
                                </button>
                                &nbsp;
                                <span class="btn btn-default reset-form" tabindex="20">
                                    <i class="fa fa-history text-info fa-lg" aria-hidden="true"></i>
                                    <span class="text-success"> Reset </span>
                                </span>
                                &nbsp;
                                <a href="{!! route('admin.company') !!}" class="btn btn-default" tabindex="20">
                                    {{-- <span class="glyphicon glyphicon-remove text-danger"></span>&nbsp; <span class="text-danger"> Cancel </span>--}}
                                    <i class="fa fa-remove text-danger fa-lg" aria-hidden="true"></i>&nbsp; <span class="text-danger"> Cancel </span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {!! Form::close() !!}
        <!--end::Form-->
    </div>
    <!--end::Portlet-->
    <script>
        //== Class definition
        var BootstrapSelect = function () {
            //== Private functions
            var demos = function () {
                // minimum setup
                $('.m_m_selectpicker').selectpicker();
                $('.m_m_selectpicker1').selectpicker();
                $('.m_m_selectpicker2').selectpicker();
                $('.m_m_selectpicker3').selectpicker();
                $('.m_m_selectpicker5').selectpicker();
            }
            return {
                // public functions
                init: function() {
                    demos(); 
                }
            };
        }();

        jQuery(document).ready(function() {    
            BootstrapSelect.init();
        });
         //== Bootstrap select 2
        var Select2 = function() {
            //== Private functions
            var demos = function() {
                // basic
                $('#city_select').select2();
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

    <script type="text/javascript">
        $(document).ready(function(){
            $(".reset-form").click(function() {
                $(':input','#add-company').not(':button, :submit, :reset, :hidden').val('').removeAttr('checked').removeAttr('selected');
            })
        });
        $(".selectCountry").change(function()
        {
            var country_id = $(this).val();
            if (country_id == '')
                return false;
            $.ajax
            ({
                type: "POST",
                url: "{!! route('admin.passenger.zone') !!}",
                data: {"_token": "{{ csrf_token() }}", "country_id": country_id},
                cache: false,
                beforeSend: function(){
                    $('#loader1').show();
                },
                complete: function(){
                    $('#loader1').hide();
                },
                success: function(html)
                {
                    $("#city_select").html(html);
                }
            });
        });
    </script>
@stop