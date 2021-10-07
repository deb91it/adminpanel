@extends('agent.layout.master')
@section('title') Employee List @stop


@section('page_name')
    Employee  Management
    <small>All Employees</small>
@stop

@section ('breadcrumbs')
    <li> <a href="{!! route('agent.dashboard') !!}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
    <li class="active"> {!! link_to_route('agents', 'Employee  Management') !!} </li>
@stop

@section('content')
    <div class="m-portlet">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <h3 class="m-portlet__head-text">
                        <a href="{!! route('agent.new') !!}" class="btn btn-sm btn-brand m-btn--pill">
                            <span class="la la-plus"></span>&nbsp; New Employee
                        </a>
                    </h3>                 
                </div> 
            </div>
            <div class="m-portlet__head-tools" style="display: none;">
                <div class="m-btn-group m-btn-group--pill btn-group-sm btn-group date-show-container export-date-show-container" role="group" aria-label="First group" style="display:none; margin-left: 5px; margin-right: 5px">
                    <span class="m--font-brand"><b>Export from :</b></span> <span class="start-select-date" name=""> </span> <span class="m--font-brand"><b>To</b></span> <span class="end-select-date" name=""> </span>
                </div>

                <div class="m-btn-group m-btn-group--pill btn-group-sm btn-group" role="group" aria-label="First group">
                    <span class="m-btn btn btn-light m-loader m-loader--brand m-loader--right m-loader--lg" id="loading-indicator" style="display: none" ></span>
                </div>

                <div class="m-btn-group m-btn-group--pill btn-group" role="group" aria-label="First group">
                    <button type="button" class="m-btn btn btn-primary export-file" title="Print" name="xlsx">
                        <i class="fa fa-file-excel-o"></i>
                    </button>
                    <button type="button" class="m-btn btn btn-success export-file" name="csv" title="Csv">
                        <i class="fa fa-file-o"></i>
                    </button>
                    <button type="button" class="m-btn btn btn-brand export-file" title="Pdf" name="pdf">
                        <i class="fa fa-file-pdf-o"></i>
                    </button>
                </div>
            </div>
        </div>
        <div class="m-portlet__body">
            <div class="m-section">
                <div class="m-section__content">
                    <table class="table table-sm m-table m-table--head-bg-brand table-responsive table-striped table-hover custom_table_responsive">
                        <thead class="">
                            <tr>
                                <th>Sl.</th>
                                <th>Name</th>
                                <th>Contact</th>
                                <th>Parent</th>
                                <th>Is active</th>
                                {{--<th>Can login</th>--}}
                                <th>Created at</th>
                                <th><center> Action </center></th>
                            </tr>
                        </thead>
                        <tbody>
                        @if(count($agents))
                            <?php $i = $agents->firstItem(); ?>
                            @foreach($agents as $agent)
                                <tr>
                                    <td>{{ $i }}</td>
                                    <td>
                                        {{ $agent->full_name }} <br/>
                                        <span class="m--font-brand" style="font-size: 11px;"> {{ $agent->designation }}</span>
                                    </td>
                                    <td>{{ $agent->email }} <br/> {{ $agent->mobile_no }} </td>
                                    <td class="agent-table-parent">@php echo $agent->parent; @endphp</td>
                                    <td class="">
                                        @if($agent->status == 1)
                                            <i class="fa fa-check m--font-success"></i>
                                        @else
                                            <i class="fa fa-remove m--font-danger"></i>
                                        @endif
                                    </td>
                                    {{--<td class="text-center">
                                        @if($agent->m_canlogin == 1)
                                            <i class="fa fa-check m--font-success"></i>
                                        @else
                                            <i class="fa fa-remove m--font-danger"></i>
                                        @endif
                                    </td>--}}
                                    <td>{{ $agent->created_at }}</td>
                                    <td>
                                        <a href="{!! route('agent.edit',array($agent->id)) !!}" class="btn btn-sm m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill" title="Edit"><i class="fa fa-edit"></i></a>
                                        <a onclick="employeeDetails({{$agent->id}})" class="btn btn-sm m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill" title="Employee Details"><i class="fa fa-user-circle-o"></i></a>
                                    </td>
                                </tr>
                                <?php $i++ ?>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="10" class="text-center text-danger">{{ "No data found" }}</td>
                            </tr>
                        @endif
                        </tbody>
                    </table>
                </div> 
            </div>
        </div>
        <div class="m-portlet__foot clearfix">
            <div class="pull-right">
                {!! $agents->render() !!}
            </div>
        </div>
        <!--end::Section-->
    </div>

<!--end::Portlet-->


    <!-- Metronic modal -->
    <!--begin::Modal-->
    <div class="modal fade" id="employeeModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <form method="post" action="{{route('agent.employee.details.update')}}" id="employee-details">
                {{csrf_field()}}
                <input type="hidden" name="row_id" id="row_id">
                <input type="hidden" name="member_id" id="member_id">
            <div class="modal-content">
                <div class="m-portlet">
                    <div class="m-portlet__head">
                        <div class="m-portlet__head-caption">
                            <div class="m-portlet__head-title">
                        <span class="m-portlet__head-icon m--font-brand">
                            <i class="fa fa-plus"></i>
                        </span>
                                <h3 class="m-portlet__head-text">
                                    Edit Employee Details
                                </h3>
                            </div>
                        </div>
                    </div>
                    <span class="description">
                        <div class="msqSuccess" style="color: green"></div>
                        <div class="msqError" style="color: red"></div>
                    </span>
                    <div class="m-form m-form--fit m-form--label-align-right ">
                        <div class="modal-body driver_modal">
                            <div class="m-portlet__body">
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <div class="form-group m-form__group row">
                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 {!! $errors->has('father_name') ? 'has-error' : '' !!}">
                                                <label>Father Name <span class="text-danger">*</span></label>
                                                {!! Form::text('father_name', null, ['class' => 'form-control m-input','id'=>'father_name','value'=>Input::old('father_name'), 'placeholder' => 'Enter Category Name', 'tabindex' => '1']) !!}
                                                {!! $errors->first('father_name', '<label class="error_txt_size">:message</label>') !!}
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 {!! $errors->has('mother_name') ? 'has-error' : '' !!}">
                                                <label>Mother Name <span class="text-danger">*</span></label>
                                                {!! Form::text('mother_name', null, ['class' => 'form-control m-input','id'=>'mother_name','value'=>Input::old('mother_name'), 'placeholder' => 'Enter Category Name', 'tabindex' => '1']) !!}
                                                {!! $errors->first('mother_name', '<label class="error_txt_size">:message</label>') !!}
                                            </div>
                                        </div>

                                        <div class="form-group m-form__group row">
                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 {!! $errors->has('nid') ? 'has-error' : '' !!}">
                                                <label>National ID <span class="text-danger">*</span></label>
                                                {!! Form::text('nid', null, ['class' => 'form-control m-input','id'=>'nid','value'=>Input::old('nid'), 'placeholder' => 'Enter Category Name', 'tabindex' => '1']) !!}
                                                {!! $errors->first('nid', '<label class="error_txt_size">:message</label>') !!}
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 {!! $errors->has('birth_certificate_no') ? 'has-error' : '' !!}">
                                                <label>Birth Certificate No </label>
                                                {!! Form::text('birth_certificate_no', null, ['class' => 'form-control m-input','id'=>'birth_certificate_no','value'=>Input::old('birth_certificate_no'), 'placeholder' => 'Enter Category Name', 'tabindex' => '1']) !!}
                                                {!! $errors->first('birth_certificate_no', '<label class="error_txt_size">:message</label>') !!}
                                            </div>
                                        </div>
                                        <div class="form-group m-form__group row">
                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 {!! $errors->has('date_of_join') ? 'has-error' : '' !!}">
                                                <label>Date of Join <span class="text-danger">*</span></label>
                                                {!! Form::date('date_of_join', null, ['class' => 'form-control m-input','id'=>'date_of_join','value'=>Input::old('date_of_join'), 'placeholder' => 'Enter Category Name', 'tabindex' => '1']) !!}
                                                {!! $errors->first('date_of_join', '<label class="error_txt_size">:message</label>') !!}
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 {!! $errors->has('resign_date') ? 'has-error' : '' !!}">
                                                <label>Date of Separation </label>
                                                {!! Form::date('resign_date', null, ['class' => 'form-control m-input','id'=>'resign_date','value'=>Input::old('resign_date'), 'placeholder' => 'Enter Category Name', 'tabindex' => '1']) !!}
                                                {!! $errors->first('resign_date', '<label class="error_txt_size">:message</label>') !!}
                                            </div>
                                        </div>
                                        <div class="form-group m-form__group row">
                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 {!! $errors->has('address') ? 'has-error' : '' !!}">
                                                <label>Address <span class="text-danger">*</span></label>
                                                {!! Form::textArea('address', null, ['class' => 'form-control m-input','id'=>'address','value'=>Input::old('address'), 'rows'=>'3', 'cols'=>'50', 'placeholder' => 'Enter your address', 'tabindex' => '1']) !!}
                                                {!! $errors->first('address', '<label class="error_txt_size">:message</label>') !!}
                                            </div>
                                        </div>
                                    </div><!--END LEFT COL -->
                                </div>
                            </div>
                            <div class="m-portlet__foot m-portlet__no-border m-portlet__foot--fit">
                                <div class="m-form__actions m-form__actions--solid">
                                    <div class="row">
                                        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
                                            <div class="pull-left">
                                                <button class="btn btn-default" type="submit" tabindex="20">
                                                    <i class="fa fa-check-circle text-success fa-lg" aria-hidden="true"></i>
                                                    <span class="text-success"> Save </span>
                                                </button>
                                                &nbsp;
                                                <span class="btn btn-default reset-form" tabindex="20">
                                            <i class="fa fa-history text-info fa-lg" aria-hidden="true"></i>
                                            <span class="text-success"> Reset </span>
                                        </span>
                                                &nbsp;
                                                <a href="" data-dismiss="modal"  class="btn btn-default" tabindex="20">
                                                    {{-- <span class="glyphicon glyphicon-remove text-danger"></span>&nbsp; <span class="text-danger"> Cancel </span>--}}
                                                    <i class="fa fa-remove text-danger fa-lg" aria-hidden="true"></i>&nbsp; <span class="text-danger"> Cancel </span>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            </form>
        </div>
    </div>
    <!--end::Modal-->
    <!--/ Metronic modal -->
    
    <style>
        .dataTables_wrapper .dataTables_paginate .paginate_button{
            border:0px!important;
            padding:0px!important;
        }
        .dataTables_length{
            margin-bottom:10px;
        }
        .dataTables_length .form-control {
            margin-left: 5px;
            margin-right: 5px;
        }
        table.dataTable.no-footer {
            border-bottom:1px solid #f4f4f4!important;
        }
        .agent-table-parent span{
            color: #716aca;
            font-size: 11px;
        }
    </style>

    <!-- Jquery validator js -->
    <script src="{!! asset('backend/ezzyr_assets/jquery_validator/jquery.validate.js') !!}" type="text/javascript"></script>
    <script src="{!! asset('backend/ezzyr_assets/jquery_validator/additional_methods.js') !!}" type="text/javascript"></script>
    <script type="text/javascript">

        $(document).ready(function(){
            $("#employee-details").validate({
                rules: {
                    // simple rule, converted to {required:true}
                    father_name: {
                        required: true,
                    },
                    // compound rule
                    mother_name: {
                        required: true,
                    },
                    address: {
                        required: true,
                    },
                    // compound rule
                    nid: {
                        required: true,
                        number: true,
                    },
                    // compound rule
                    date_of_join: {
                        required: true,
                    },
                },
                errorElement : 'label',
                errorClass : 'error_txt_size',
            });
            $(".reset-form").click(function() {
                $(':input','#employee-details').not(':button, :submit, :reset, :hidden').val('').removeAttr('checked').removeAttr('selected');
            })
        });
        // For export data
        $(document).on('click','.export-file',function(e)
        {
            e.preventDefault();
            var base_url = "{{ url('/') }}";
            var exportType = $(this).attr('name');
            var startDate = $('.start-select-date').attr('name');
            var endDate = $('.end-select-date').attr('name');

            $.ajax({
                type: "POST",
                url: "{!! route('agents.export') !!}",
                data: {"_token": "{{ csrf_token() }}", "export_type": exportType, "start_date": startDate, "end_date": endDate},
                cache: false,
                beforeSend: function(){
                    $('#loading-indicator').show();
                },
                complete: function(){
                    $('#loading-indicator').hide();
                },
                success: function (res) {
                    if (res.success === false) {
                        alert(res.msg);
                    } else {
                        location.href = base_url +'/'+ res.full;
                    }
                }
            });

        });
        function employeeDetails(member_id) {
            $.ajax({
                type: "POST",
                url: "{!! route('agent.employee.details') !!}",
                data: {"_token": "{{ csrf_token() }}", "member_id": member_id},
                cache: false,
                beforeSend: function(){
                    $('#loading-indicator').show();
                },
                complete: function(){
                    $('#loading-indicator').hide();
                },
                success: function (res) {
                    if (res.success === false) {
                        $("#employee-details")[0].reset();
                    } else {
                        $("#father_name").val(res.response.father_name);
                        $("#mother_name").val(res.response.mother_name);
                        $("#address").val(res.response.address);
                        $("#nid").val(res.response.nid);
                        $("#birth_certificate_no").val(res.response.birth_certificate_no);
                        $("#date_of_join").val(res.response.date_of_join);
                        $("#employee_id").val(res.response.employee_id);
                        $("#resign_date").val(res.response.resign_date);
                        $("#row_id").val(res.response.id);
                    }
                    $("#member_id").val(res.response.member_id);
                    $('#employeeModal').modal('show');
                }
            });
        }


    </script>
@stop