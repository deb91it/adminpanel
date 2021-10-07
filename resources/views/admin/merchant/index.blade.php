@extends('admin.layout.master')
@section('title') Merchant List @stop


@section('page_name')
    Merchant Management
    <small>All Merchants</small>
@stop

@section ('breadcrumbs')
    <li> <a href="{!! route('admin.dashboard') !!}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
    <li class="active"> {!! link_to_route('admin.merchants', 'Merchant Management') !!} </li>
@stop

@section('content')

    <div class="m-portlet">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <h3 class="m-portlet__head-text">
                        <a href="{!! route('admin.merchant.new') !!}" class="btn btn-sm btn-brand m-btn--pill">
                            <span class="la la-plus"></span>&nbsp; New Merchant
                        </a>
                    </h3>
                </div>
            </div>
            <div class="m-portlet__head-tools">
                <div class="m-btn-group m-btn-group--pill btn-group-sm btn-group date-show-container export-date-show-container" role="group" aria-label="First group" style="display:none; margin-left: 5px; margin-right: 5px">
                    <span class="m--font-brand"><b>Export from :</b></span> <span class="start-select-date" name=""> </span> <span class="m--font-brand"><b>To</b></span> <span class="end-select-date" name=""> </span>
                </div>

                <div class="m-btn-group m-btn-group--pill btn-group-sm btn-group" role="group" aria-label="First group">
                    <span class="m-btn btn btn-light m-loader m-loader--brand m-loader--right m-loader--lg" id="loading-indicator" style="display: none" ></span>
                </div>

                <div class="m-btn-group  btn-group" role="group" aria-label="First group">
{{--                    <button type="button" class="m-btn btn btn-primary export-file" title="Excel" name="xlsx">--}}
{{--                        <i class="fa fa-file-excel-o"></i>--}}
{{--                    </button>--}}
                    <button type="button" class="btn btn-success export-file" name="csv" title="Csv">
                        <i class="fa fa-file-o"></i>&nbsp;Export
                    </button>
                    {{--                    <button type="button" class="m-btn btn btn-brand export-file" title="Pdf" name="pdf">--}}
                    {{--                        <i class="fa fa-file-pdf-o"></i>--}}
                    {{--                    </button>--}}
                </div>
            </div>
        </div>
        <div class="m-portlet__body">
            <div class="m-section">
                <div class="m-section__content">
                    <table id="merchant-datatable" class="table table-sm m-table m-table--head-bg-brand table-responsive table-striped table-hover custom_table_responsive">
                        <thead class="">
                        <tr>
                            <th>Sl.</th>
                            <th>Merchant Code</th>
                            <th>Owner Name</th>
                            <th>Business Name</th>
                            <th>Address</th>
                            <th>Email</th>
                            <th>Mobile</th>
                            <th>Hub name</th>
                            <th>Is active</th>
                            <th>Can login</th>
                            <th>Has plan</th>
                            <th>Role</th>
                            <th>Joining Date</th>
                            <th><center> Action </center></th>
                        </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
        <div class="m-portlet__foot clearfix">
            <div class="pull-right">
            </div>
        </div>
        <!--end::Section-->
    </div>

    <!--end::Portlet-->

    <!-- Metronic modal -->
    <!--begin::Modal-->
    <div class="modal fade" id="plan-assign-Modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Plan Details</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="m-portlet__head">
                        <div class="m-portlet__head-caption">
                            <div class="m-portlet__head-title">
                                <div class="m-section">

                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="m-portlet__body">
                        <div class="m-section">
                            <div class="m-section__content">
                                <table id="plan-details" class="table table-sm m-table m-table--head-bg-brand table-responsive table-striped table-hover custom_table_responsive">
                                    <thead class="">
                                    <tr>
                                        <th>Sl</th>
                                        <th>Plan Name</th>
                                        <th>Status</th>
                                        <th>Created At</th>
                                    </tr>
                                    </thead>
                                    <tbody id="plan-details-body"></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="m-portlet__foot clearfix">
                    <div class="pull-right">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--end::Modal-->
    <!--/ Metronic modal -->

    <!--begin::Modal-->
    <div class="modal fade" id="approve-merchant-Modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Merchant Details</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="m-portlet__head">
                        <div class="m-portlet__head-caption">
                            <div class="m-portlet__head-title">
                                <div class="m-section">

                                </div>
                            </div>
                        </div>
                    </div>
                    <form method="post" action="{{route('admin.merchant.approve.store')}}" id="approval-form">
                        {{csrf_field()}}
                        <input type="hidden" name="merchant_id" id="merchant_id">
                        <input type="hidden" name="member_id" id="member_id">
                        <div class="m-portlet__body">
                            <div class="m-section">
                                <div class="m-section__content">
                                    <div class="form-group m-form__group row justify-content-center">
                                        <div class="col-lg-5 col-md-5 col-sm-5 col-xs-12 {!! $errors->has('merchant_id') ? 'has-error' : '' !!}">
                                            <div class="card" style="width: 100%;">
                                                <img class="card-img-top" style="padding: 20px;" id="merchant-modal-img"></img>
                                                <div class="card-body">

                                                </div>
                                                <ul class="list-group list-group-flush">
                                                    <li class="list-group-item"><b>Name :</b> <span id="merchant-modal-name"></span></li>
                                                    <li class="list-group-item"><b>Email :</b> <span id="merchant-modal-email"></span></li>
                                                    <li class="list-group-item"><b>Mobile : <span id="merchant-modal-mobile"></span></b></li>
                                                </ul>
                                            </div>
                                        </div>

                                        <div class="col-lg-5 col-md-5 col-sm-5 col-xs-12">
                                            <!--                                        --><?php //if (get_admin_hub_id() > 0) { }else{
                                            //                                            ?>
                                            <div class="row {!! $errors->has('hub_id') ? 'has-error' : '' !!}">
                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                    <label>Hub List <span class="text-danger">*</span></label>
                                                    <select name="hub_id" id="hub_id" class="form-control">
                                                    </select>
                                                    {!! $errors->first('hub_id', '<label class="error_txt_size">:message</label>') !!}
                                                </div>
                                            </div>

                                            <div class="row {!! $errors->has('cod_percentage') ? 'has-error' : '' !!}">
                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                    <label>COD Percentage <span class="text-danger">*</span></label>
                                                    <input type="text" name="cod_percentage" id="cod_percentage" class="form-control" placeholder="percentage" value="">
                                                    {!! $errors->first('cod_percentage', '<label class="error_txt_size">:message</label>') !!}
                                                </div>
                                            </div>
                                            <div class="row {!! $errors->has('cod_limit_amount') ? 'has-error' : '' !!}">
                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                    <label>COD Limit Amount <span class="text-danger">*</span></label>
                                                    <input type="text" name="cod_limit_amount" id="cod_limit_amount" class="form-control" placeholder="Amount" value="">
                                                    {!! $errors->first('cod_limit_amount', '<label class="error_txt_size">:message</label>') !!}
                                                </div>
                                            </div>
                                            <!--                                            --><?php //} ?>
                                            <div class="row {!! $errors->has('approval_val') ? 'has-error' : '' !!}" style="padding-top:25px; ">
                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                    <label>Approve <span class="text-danger">*</span></label>
                                                    <label class="m-checkbox m-checkbox--bold m-checkbox--state-brand">
                                                        <input type="checkbox" name="approval_val" id="approval_val" value="1">
                                                        <span></span>
                                                    </label>
                                                    {!! $errors->first('approval_val', '<label class="error_txt_size">:message</label>') !!}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="m-portlet__foot m-portlet__no-border m-portlet__foot--fit">
                            <div class="m-form__actions m-form__actions--solid">
                                <div class="row justify-content-center">
                                    <div class="col-lg-10 col-md-10 col-sm-10 col-xs-12">
                                        <div class="pull-left">
                                            <button type="submit" class="btn btn-default" tabindex="20">
                                                <i class="fa fa-check-circle text-success fa-lg" aria-hidden="true"></i>
                                                <span class="text-success" id="approve-btn"> Save </span>
                                            </button>

                                            <a data-dismiss="modal" class="btn btn-default" tabindex="20">
                                                {{-- <span class="glyphicon glyphicon-remove text-danger"></span>&nbsp; <span class="text-danger"> Cancel </span>--}}
                                                <i class="fa fa-remove text-danger fa-lg" aria-hidden="true"></i>&nbsp; <span class="text-danger"> Cancel </span>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="m-portlet__foot clearfix">
                    <div class="pull-right">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--end::Modal-->
    <script src="{!! asset('backend/ezzyr_assets/jquery_validator/jquery.validate.js') !!}" type="text/javascript"></script>
    <script type="text/javascript">
        $(document).ready(function(){
            var table = $('#merchant-datatable').DataTable({
                processing: true,
                serverSide: true,
                paging: true,
                pageLength: 25,
                lengthChange: true,
                searching: true,
                ordering: true,
                info: true,
                autoWidth: false,
                dom : 'l<"#date-filter">frtip',
                ajax: {
                    url: '{!! route('admin.datatable.merchant') !!}',
                    type: 'GET',
                    data: function( d ) {
                        d._token = "{{ csrf_token() }}";
                    }
                },
                columns: [
                    {
                        data: 'id',
                        name: 'id',
                        searchable: false,
                        render: function (data, type, row, meta) {
                            return meta.row + meta.settings._iDisplayStart + 1;
                        }

                    },
                    {
                        data: 'merchant_code',
                        name: 'merchant_code',
                        searchable: true,
                        defaultContent:"-",
                        render: function (data, type, row, meta) {
                            var item = row.merchant_code;
                            if (row.merchant_code == 0)
                            {
                                item = '-';
                            }
                            return item;
                        }
                    },
                    {
                        data: 'full_name',
                        name: 'full_name',
                        searchable: true,
                        defaultContent:"-",
                        render: function (data, type, row, meta) {
                            var item = '';
                            if (row.new_time == 1)
                            {
                                item = '&nbsp;&nbsp;<span class="m-badge m-badge--brand m-badge--wide">new</span>';
                            }
                            return '<a href="#" style="cursor: pointer" title="Plan Details" onclick="viewPlans('+row.merchant_id+')">'+row.full_name + item+'</a>';
                        }
                    },
                    {
                        data: 'business_name',
                        name: 'business_name',
                        searchable: true
                    },
                    {
                        data: 'address',
                        name: 'address',
                        searchable: false
                    },
                    {
                        data: 'm_email',
                        name: 'm_email',
                        searchable: true
                    },
                    {
                        data: 'm_mobile',
                        name: 'm_mobile',
                        searchable: true
                    },
                    {
                        data: 'hub_name',
                        name: 'hub_name',
                        searchable: false
                    },
                    {
                        data: 'merch_status',
                        name: 'merch_status',
                        searchable: false,
                        orderable: false,
                        render: function ( data, type, row ) {
                            if(row.merch_status==1){
                                return '<i class="fa fa-check m--font-success"></i>';
                            }else{
                                return '<i class="fa fa-remove m--font-danger"></i>';
                            }
                        }
                    },
                    {
                        data: 'm_canlogin',
                        name: 'm_canlogin',
                        searchable: false,
                        orderable: false,
                        render: function ( data, type, row ) {
                            if(row.m_canlogin==1){
                                return '<i class="fa fa-check m--font-success"></i>';
                            }else{
                                return '<i class="fa fa-remove m--font-danger"></i>';
                            }
                        }
                    },
                    {
                        data: 'has_plan',
                        name: 'has_plan',
                        searchable: false,
                        orderable: false,
                        render: function ( data, type, row ) {
                            if(row.has_plan==1){
                                return '<i class="fa fa-check m--font-success"></i>';
                            }else{
                                return '<i class="fa fa-remove m--font-danger"></i>';
                            }
                        }
                    },
                    {
                        data: 'r_rolename',
                        name: 'r_rolename',
                        searchable: false
                    },
                    {
                        data: 'joining_date',
                        name: 'joining_date',
                        searchable: false
                    },
                    {
                        data: 'action_col',
                        name: 'action_col',
                        searchable: false,
                        orderable: false
                    },
                ],
            });

            $('.dataTables_wrapper').removeClass('form-inline');
            $('.form-control').removeClass('input-sm');
            $('.dataTables_processing').addClass('m-loader m-loader--brand');

            $('#merchant-datatable_length').addClass('col-lg-6 col-md-6 col-sm-6');
            $('#merchant-datatable_length .form-control').addClass('m-bootstrap-select m-bootstrap-select--air filter-select');
            $('#date-filter').addClass('col-lg-3 col-md-3 col-sm-3');
            $('#merchant-datatable_filter').addClass('col-lg-3 col-md-3 col-sm-3');


            var date_picker_html =
                '<div class="form-group m-form__group clearfix">' +
                '<div class="input-group pull-right" id="m_daterangepicker">' +
                '<input type="text" class="form-control m-input" readonly  placeholder="Select date range" value=""/>' +
                '<span class="input-group-addon">' +
                '<i class="fa fa-calendar"></i>' +
                '</span>' +
                '</div>' +
                '</div>';

            $('#date-filter').append(date_picker_html);
            $('#merchant-datatable_filter label:first-child input').attr('placeholder', 'code, name, mobile, email,business name');


            $('#m_daterangepicker').daterangepicker({
                buttonClasses: 'm-btn btn',
                applyClass: 'btn-primary',
                cancelClass: 'btn-secondary',
                ranges: {
                    'Today': [moment(), moment()],
                    'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                    'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                    'This Month': [moment().startOf('month'), moment().endOf('month')],
                    'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                },
                startDate: moment(),
                endDate: moment(),
                format: 'YYYY-MM-DD'

            }, function(start, end, label) {
                $('#m_daterangepicker .form-control').html(start.format('YYYY-MM-DD') + ' - ' + end.format('YYYY-MM-DD'));
                var start_date = start.format('YYYY-MM-DD');
                var end_date = end.format('YYYY-MM-DD');
                table.columns(9).search(start_date + ' ~ ' + end_date).draw();


                // To view beside export buttons
                $('.date-show-container').show();
                $('.start-select-date').html(start.format('DD-MM-YYYY'));
                $('.start-select-date').attr('name', start.format('YYYY-MM-DD'));
                $('.end-select-date').html(end.format('DD-MM-YYYY'));
                $('.end-select-date').attr('name', end.format('YYYY-MM-DD'));
            });

            //For clear the input field
            function tog(v){
                return v?'addClass':'removeClass';
            }
            $(document).on('input', '.clearable', function(){
                $(this)[tog(this.value)]('x');
            }).on('change', '.clearable', function( e ){
                $(this)[tog(this.value)]('x');
            }).on('mousemove', '.x', function( e ){
                $(this)[tog(this.offsetWidth-18 < e.clientX-this.getBoundingClientRect().left)]('onX');
            }).on('touchstart click', '.onX', function( ev ){
                ev.preventDefault();
                $(this).removeClass('x onX').val('').change();
                table.columns(9).search('').draw();
            });

        });

    </script>


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
    </style>


    <script type="text/javascript">
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
                url: "{!! route('admin.merchants.export') !!}",
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

        function viewPlans(id) {
            $.ajax({
                type: "POST",
                url: "{!! route('admin.plan-assign.view') !!}",
                data: {"_token": "{{ csrf_token() }}", "plan_assign_id": id},
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
                        $("#plan-details-body tr").remove();
                        var sl =1;
                        var content ='';
                        var status ='';
                        $.each(res.result, function (key, val) {
                            console.log(val)
                            if(val['out_time']==null){outTime='-'}else{outTime=val['out_time']}
                            if(val['status']==1){
                                status = '<i class="fa fa-check m--font-success"></i>';
                            }else{
                                status = '<i class="fa fa-remove m--font-danger"></i>';
                            }
                            content +='<tr>';
                            content += '<td>' + sl++ + '</td>';
                            content += '<td>' + val['plan_name'] + '</td>';
                            content += '<td>' + status + '</td>';
                            content += '<td>' + val['createTime'] + '</td>';
                            content +='</tr>';
                        });
                        content +='';
                        $('#plan-details-body').append(content);
                        $("#plan-assign-Modal").modal('show');
                    }
                }
            });
        }

        function approveMerchant(id) {
            $.ajax({
                type: "POST",
                url: "{!! route('admin.merchant.approve') !!}",
                data: {"_token": "{{ csrf_token() }}", "merchant_id": id},
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
                        $("#hub_id option").remove();
                        var content ='';
                        var picture ='';
                        var selected = '';
                        if (res.merchant.profile_pic !='')
                        {
                            picture = res.path+"/resources/profile_pic/"+res.merchant.profile_pic;
                        }else{
                            picture = res.path+"/backend/dist/img/avatar.png";
                        }
                        $("#merchant-modal-img").attr('src',picture);
                        $("#merchant-modal-name").text(res.merchant.first_name+" "+res.merchant.last_name);
                        $("#merchant-modal-email").text(res.member.email);
                        $("#merchant-modal-mobile").text(res.member.mobile_no);
                        $("#merchant_id").val(res.merchant.id);
                        $("#cod_percentage").val(res.merchant.cod_percentage);
                        $("#cod_limit_amount").val(res.merchant.cod_limit_amount);
                        $("#member_id").val(res.member.id);
                        content +='<option value="">select a hub</option>';
                        $.each(res.hubs, function (key, val) {
                            if(res.merchant.hub_id == val.id)
                            {
                                selected = 'selected';
                            }else{
                                selected = ' ';
                            }
                            content +='<option  value="'+val.id+'" '+selected+'>'+val.hub_name+'</option >';
                        });
                        content +='';
                        if (res.member.can_login == 1 && res.merchant.status == 1)
                        {
                            console.log(1);
                            $("#approval_val").prop( "checked", true );
                        }else{
                            $("#approval_val").prop('checked', false);
                        }
                        $('#hub_id').append(content);
                        $("#approve-merchant-Modal").modal('show');
                    }
                }
            });
        }
        function deleteMerchant(id) {
            if (confirm("Are you sure?")) {
                let location = window.location.replace("/admin/merchant/"+id+"/delete");
            }
            return false;
        }
        <?php if (get_admin_hub_id() > 0) {}else{
        ?>
        $(document).ready(function () {
            $("#approval-form").validate({
                rules: {
                    // simple rule, converted to {required:true}
                    hub_id: {
                        required: true,
                    },
                },
                errorElement : 'label',
                errorClass : 'error_txt_size',
            });
        })
        <?php } ?>
    </script>
@stop