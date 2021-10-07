@extends('admin.layout.master')
@section('title') Edit Profile @stop


@section('page_name')    &nbsp;
    Admin Profile Management
    <small>Edit Profile</small>
@stop
@section ('breadcrumbs')
    <li> <a href="{!! route('admin.dashboard') !!}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
    <li class="active"> {!! link_to_route('admin.user_profile', 'Profile Management') !!} </li>
    <li class="active"> Edit Profile </li>
@stop

@section('content')
    <!--begin::Portlet-->
    <div class="m-portlet">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <span class="m-portlet__head-icon m--font-brand">
                        <i class="fa fa-edit" aria-hidden="true"></i>
                    </span>
                    <h3 class="m-portlet__head-text">
                       Edit User Profile
                    </h3>
                </div>
            </div>
        </div>
        <!--begin::Form-->
        {!! Form::open(['route' => ['admin.update_profile'], 'role' => 'form', 'method' => 'post', 'id' => "edit-user-profile", 'files'=>true]) !!}
        <input type="hidden" name="_token" value="{{ csrf_token() }}">

        <div class="m-form m-form--fit m-form--label-align-right ">
            <div class="m-portlet__body">
                <div class="row">
                    <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12 col-lg-offset-2 col-md-offset-2 col-sm-offset-2">
                        <div class="form-group m-form__group row">
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 {!! $errors->has('first_name') ? 'has-error' : '' !!}">
                                <label> First name <span class="text-danger">*</span>
                                </label>
                                {!! Form::text('first_name', $user->first_name, ['class' => 'form-control m-input', 'id'=>'first_name','value'=>Input::old('first_name'), 'placeholder' => 'Enter first name',  'tabindex' => '15']) !!}
                                {!! $errors->first('first_name', '<label class="error_txt_size">:message</label>') !!}
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 {!! $errors->has('last_name') ? 'has-error' : '' !!}">
                                <label> Last name <span class="text-danger">*</span></label>
                                {!! Form::text('last_name', $user->last_name, ['class' => 'form-control m-input', 'id'=>'pk_unit_fare','value'=>Input::old('last_name'), 'placeholder' => 'Enter last name',  'tabindex' => '16']) !!}
                                {!! $errors->first('last_name', '<label class="error_txt_size">:message</label>') !!}
                            </div>
                        </div>
                        <div class="form-group m-form__group row">
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12{!! $errors->has('email') ? 'has-error' : '' !!}">
                                <label> Email <span class="text-danger">*</span>
                                </label>
                                {!! Form::text('email', $user->email, ['class' => 'form-control m-input', 'id'=>'email','value'=>Input::old('email'), 'placeholder' => 'Enter email',  'tabindex' => '15']) !!}
                                {!! $errors->first('email', '<label class="error_txt_size">:message</label>') !!}
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 {!! $errors->has('mobile_no') ? 'has-error' : '' !!}">
                                <label> Mobile <span class="text-danger">*</span></label>
                                {!! Form::text('mobile_no', $user->mobile_no, ['class' => 'form-control m-input', 'id'=>'mobile_no','value'=>Input::old('mobile_no'), 'placeholder' => 'Enter mobile_no',  'tabindex' => '16']) !!}
                                {!! $errors->first('mobile_no', '<label class="error_txt_size">:message</label>') !!}
                            </div>

                        </div>

                        <div class="form-group m-form__group row">
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 {!! $errors->has('gender') ? 'has-error' : ''  !!} ">
                                <label>Gender <span class="text-danger">*</span></label>
                                {!! Form::select('gender', ['1' => 'Male', '0' => 'Female'], $user->gender, array('class' => 'form-control m-input m-bootstrap-select m_m_selectpicker', 'value'=>Input::old('gender'), 'tabindex' => '6')) !!}
                                {!! $errors->first('gender', '<label class="error_txt_size">:message</label>') !!}
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 {!! $errors->has('') ? 'has-error' : '' !!}">
                                <label> Designation <span class="text-danger"></span>
                                </label>
                                {!! Form::text('designation', $user->designation, ['class' => 'form-control m-input', 'id'=>'email','value'=>Input::old('designation'), 'placeholder' => 'Enter designation',  'tabindex' => '15']) !!}
                                {!! $errors->first('designation', '<label class="error_txt_size">:message</label>') !!}
                            </div>
                        </div>
                         
                        <div class="form-group m-form__group row">                     
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 {!! $errors->has('profile_pic') ? 'has-error' : ''  !!} ">
                                <label>Picture</label>
                                {!! Form::file('profile_pic', null, ['class' => 'form-control m-input','id'=>'profile_pic', 'tabindex' => '6']) !!}
                                {!! $errors->first('profile_pic', '<label class="error_txt_size">:message</label>') !!}
                            </div>                       
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                @if($user->profile_pic != '')
                                    @if(file_exists(public_path().'/resources/profile_pic/'.$user->profile_pic))
                                        <img src="{{ url('/resources/profile_pic') }}/{{ $user->profile_pic }}" height="70" width="60">
                                    @endif
                                @else
                                    {!! 'No image !' !!}
                                @endif
                            </div>
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
                                <a href="{!! route('admin.user_profile') !!}" class="btn btn-default" tabindex="20">
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



    <style>
        .select2-container--default .select2-selection--single, .select2-selection .select2-selection--single {
            padding: 2px 12px;
            height: 34px;
        }
        .m-bootstrap-select .caret {
            display: none;
        }
    </style>
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
                $('.m_m_selectpicker4').selectpicker();
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
    </script>





    <script type="text/javascript">
        $(document).ready(function(){
            $(".reset-form").click(function() {
                $(':input','#edit-user-profile').not(':button, :submit, :reset, :hidden').val('').removeAttr('checked').removeAttr('selected');
            })
        });
    </script>
@stop