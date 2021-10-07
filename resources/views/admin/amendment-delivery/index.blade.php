@extends('admin.layout.master')
@section('title') Delivery List @stop


@section('page_name')
     Delivery  Management
    <small>All Delivery </small>
@stop

@section ('breadcrumbs')
    <li> <a href="{!! route('admin.dashboard') !!}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
    <li class="active"> {!! link_to_route('admin.deliverys', ' Delivery Management') !!} </li>
@stop

@section('content')

    <div class="m-portlet">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <h3 class="m-portlet__head-text">
                        <a href="{!! route('admin.deliveries.create') !!}" class="btn btn-sm btn-brand m-btn--pill">
                            <span class="la la-plus"></span>&nbsp; New delivery
                        </a>
                    </h3>
                </div>
            </div>
                        <div class="m-portlet__head-tools">
                            <div class="m-btn-group m-btn-group--pill btn-group-sm btn-group date-show-container export-date-show-container" role="group" aria-label="First group" style="display:none; margin-left: 5px; margin-right: 5px">
                                <span class="m--font-brand"><b>Date from :</b></span> <span class="start-select-date" name=""> </span> <span class="m--font-brand"><b>To</b></span> <span class="end-select-date" name=""> </span>
                            </div>

                            <div class="m-btn-group m-btn-group--pill btn-group-sm btn-group" role="group" aria-label="First group">
                                <span class="m-btn btn btn-light m-loader m-loader--brand m-loader--right m-loader--lg" id="loading-indicator" style="display: none" ></span>
                            </div>

{{--                            <div class="m-btn-group m-btn-group--pill btn-group" role="group" aria-label="First group">--}}
{{--                                <button type="button" class="m-btn btn btn-primary export-file" title="Print" name="xlsx">--}}
{{--                                    <i class="fa fa-file-excel-o"></i>--}}
{{--                                </button>--}}
{{--                                <button type="button" class="m-btn btn btn-success export-file" name="csv" title="Csv">--}}
{{--                                    <i class="fa fa-file-o"></i>--}}
{{--                                </button>--}}
{{--                                <button type="button" class="m-btn btn btn-brand export-file" title="Pdf" name="pdf">--}}
{{--                                    <i class="fa fa-file-pdf-o"></i>--}}
{{--                                </button>--}}
{{--                            </div>--}}
                        </div>
        </div>
        <?php if (Request::is('admin/amendment-delivery')) {$sortingUrl = route('admin.delivery.amendment-hub.store');}else{$sortingUrl = route('admin.delivery.hub.store');} ?>
        <div class="m-portlet__body">
            <div class="m-section">
                <div class="m-section__content table-responsive">
                    <div class="col-md-12" style="padding-bottom: 15px;">
                        <label><i class="fa fa-barcode"></i> Barcode Reader</label>
                        <input type="text" name="barcode_scanner" class="form-control" id="barcode_scanner" placeholder="Please click on this field to scan the barcode">
                    </div>
                    <form method="post" action="{{$sortingUrl}}">
                        {{csrf_field()}}
                        <div class="col-md-12" style="padding-bottom: 15px;">
                            <div class="col-md-1 {!! $errors->has('flag_status_id') ? 'has-error' : ''  !!}">
                                <label>Select All</label>
                                <label class="m-checkbox m-checkbox--bold m-checkbox--state-brand">
                                    <input type="checkbox" id="data-table-checkAll">
                                    <span></span>
                                </label>
                            </div>
                            <div class="col-md-3 {!! $errors->has('flag_status_id') ? 'has-error' : ''  !!}">
                                <label>Sorting <span class="text-danger">*</span></label>
                                <select class="form-control m-input" name="flag_status_id" id="flag_status_id">
                                    <option value="">select an option</option>
                                    @forelse($getStatus as $status)
                                        <option value="{{$status->id}}">{{$status->flag_text}}</option>
                                    @empty
                                        <option></option>
                                    @endforelse
                                </select>
                                {!! $errors->first('flag_status_id', '<label class="error_txt_size">:message</label>') !!}
                            </div>
                            <div class="col-md-2 {!! $errors->has('is_hub') ? 'has-error' : ''  !!}" id="">
                                <label>Note</label>
                                <input type="text" name="notes" class="form-control" placeholder="your note .. ">
                                {!! $errors->first('is_hub', '<label class="error_txt_size">:message</label>') !!}
                            </div>
                            <?php if (get_admin_hub_id() > 0){ ?>
                            <div class="col-md-4 {!! $errors->has('assign_to') ? 'has-error' : ''  !!}" id="div_assign">
                                <label>Assign To <span class="text-danger">*</span></label>
                                <select class="form-control m-input" name="assign_to" id="assign_to">
                                </select>
                                {!! $errors->first('assign_to', '<label class="error_txt_size">:message</label>') !!}
                            </div>
                            <?php }else{ ?>
                            <div class="col-md-2 {!! $errors->has('is_hub') ? 'has-error' : ''  !!}" id="div_hub">
                                <label>Is Hub <span class="text-danger">*</span></label>
                                <select class="form-control m-input" name="is_hub" id="is_hub">
                                    <option value="">select an option</option>
                                    <option value="1">Yes</option>
                                    <option value="2">No</option>
                                </select>
                                {!! $errors->first('is_hub', '<label class="error_txt_size">:message</label>') !!}
                            </div>
                            <div class="col-md-2 {!! $errors->has('assign_to') ? 'has-error' : ''  !!}" id="div_assign">
                                <label>Assign To <span class="text-danger">*</span></label>
                                <select class="form-control m-input" name="assign_to" id="assign_to">
                                </select>
                                {!! $errors->first('assign_to', '<label class="error_txt_size">:message</label>') !!}
                            </div>
                            <?php } ?>

                            <div class="col-md-2">
                                <label> &nbsp;&nbsp;&nbsp;</label>
                                <button style="margin-top: 0px;" type="submit" class="form-control btn btn-brand m--font-light" href="#" onclick="sortingDeliveris()">submit</button>
                            </div>
                        </div>
                        <table id="Deliverys-datatable" class="table table-sm m-table m-table--head-bg-brand table-striped table-hover">
                            <thead class="">
                            <tr>
                                <th>Sl.</th>
                                <th>Consignment Id</th>
                                <th>Merchant Order Id</th>
                                <th>Delivery Date</th>
                                <th>Merchant Name</th>
                                <th>Recipient Info</th>
                                <th>Recipient Address</th>
                                <th>Zone Name</th>
                                <th>Store</th>
                                <th>Status</th>
                                <th>Package Description</th>
                                <th>Amount to be collected</th>
                                <th>Received Amount</th>
                                <th>Payment Status</th>
                                <th>Plan Name</th>
                                <th>Collected note</th>
                                <th>Special Instruction</th>
                                <th>Delivery Created At</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                        </table>
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

    <!--end::Portlet-->

    <!-- Metronic modal -->
    <!--begin::Modal-->
    <div class="modal fade" id="product-assign-Modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Product Details</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                    <div class="modal-body">
                        <div style="padding-bottom: 15px;">
                            <label class="m-checkbox m-checkbox--bold m-checkbox--state-brand">
                                <input type="checkbox" id="checkAll">
                                Select ALL
                                <span></span>
                            </label>
                            <button class="btn btn-brand pull-right" onclick="setApproval()">Approve</button>
                        </div>
                        <form id="product_details_form">
                            <input type="hidden" name="delivery_id" id="delivery_id">
                            {{csrf_field()}}
                            <div class="m-portlet__body">
                                <div class="m-section">
                                    <div class="m-section__content">
                                        <table id="product-details" class="table table-sm m-table m-table--head-bg-brand table-responsive table-striped table-hover custom_table_responsive">
                                            <thead class="">
                                            <tr>
                                                <th>Sl</th>
                                                <th>Product Name</th>
                                                <th>Subtitle</th>
                                                <th>SKU</th>
                                                <th>Category Name</th>
                                                <th>Store Name</th>
                                                <th>Description</th>
                                                <th>Price (TK)</th>
                                                <th>Weight (kg)</th>
                                                <th>Dimension (cm)</th>
                                            </tr>
                                            </thead>
                                            <tbody id="product-details-body"></tbody>
                                        </table>
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
    <!--/ Metronic modal -->

    <!-- Metronic modal -->
    <!--begin::Modal-->
    <div class="modal fade" id="product-payment-received-Modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Product Details</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                <form id="" method="post" action="{{route('admin.delivery.amendment-hub.store.payment.received')}}" class="product_details_form">
                        {{csrf_field()}}
                    <input type="hidden" name="flag_status_id" id="payment_flag_status_id" value="">
                    <input type="hidden" name="is_hub" id="is_hub" value="">
                    <div class="row" style="padding: 15px;">
                        <div class="col-md-2">
                            <label>Select ALL <span class="text-danger">*</span></label>
                            <label class="m-checkbox m-checkbox--bold m-checkbox--state-brand">
                                <input type="checkbox" id="payment-receive-checkall">

                                <span></span>
                            </label>
                        </div>
                        <div class="col-md-4 {!! $errors->has('receive_amount') ? 'has-error' : ''  !!}" id="">
                            <label>Received Amount <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="receive_amount" id="receive_amount" placeholder="amount">
                            {!! $errors->first('receive_amount', '<label class="error_txt_size">:message</label>') !!}
                        </div>
                        <div class="col-md-4 {!! $errors->has('notes') ? 'has-error' : ''  !!}" id="">
                            <label>Note <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="notes" id="notes" placeholder="Any Note">
                            {!! $errors->first('notes', '<label class="error_txt_size">:message</label>') !!}
                        </div>
                        <div class="col-md-2">
                            <label>&nbsp;&nbsp;</label>
                            <input type="submit" name="" class="btn btn-brand">
                        </div>
                    </div>
                    <div class="m-portlet__body">
                        <div class="m-section">
                            <div class="m-section__content">
                                <div class="table-responsive">
                                    <table id="payment-receive-details" class="table table-sm m-table m-table--head-bg-brand table-striped table-hover custom_table_responsive">
                                        <thead class="">
                                        <tr>
                                            <th>Sl.</th>
                                            <th>Consignment Id</th>
                                            <th>Delivery Date</th>
                                            <th>Status</th>
                                            <th>Amount to be collected</th>
                                            <th>Received Amount</th>
                                            <th>Note</th>
                                        </tr>
                                        </thead>
                                        <tbody id="payment-receive-details-body"></tbody>
                                    </table>
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
    <!--/ Metronic modal -->

    <script type="text/javascript">
        $(document).ready(function(){
            var table = $('#Deliverys-datatable').DataTable({
                processing: true,
                serverSide: true,
                paging: true,
                pageLength: 25,
                lengthChange: true,
                searching: true,
                ordering: true,
                info: true,
                autoWidth: false,


                /*scrollX:        true,
                scrollCollapse: true,
                fixedColumns: {
                    leftColumns: 0,
                    rightColumns: 1
                },*/

                dom : 'l<"#date-filter"><"#sorting-filter"><"#hub-filter">frtip',
                ajax: {
                    url: '{!! route('admin.datatable.delivery') !!}',
                    type: 'GET',
                    data: function( d ) {
                        d._token = "{{ csrf_token() }}";
                        d.delivery_date = "{{ date("Y-m-d") }}";
                        d.page_status = "amendment";
                    }
                },
                columns: [
                    {
                        data: 'deli_id',
                        name: 'deli_id',
                        searchable: false,
                        render: function (data, type, row, meta) {
                            return '<label class="m-checkbox m-checkbox--bold m-checkbox--state-brand">\n' +
                                '                            <input type="checkbox" name="deliveried_id[]" id="deliveried_id" value="'+row.deli_id+'">\n' +
                                '                            <span></span>\n' +
                                '                        </label>';
                        }

                    },
                    {
                        data: 'consignment_id',
                        name: 'consignment_id',
                        searchable: true,
                    },
                    {
                        data: 'merchant_order_id',
                        name: 'merchant_order_id',
                        searchable: true,
                    },
                    {
                        data: 'delivery_date',
                        name: 'delivery_date',
                        searchable: false
                    },
                    {
                        data: 'full_name',
                        name: 'full_name',
                        searchable: true,
                        render: function (data, type, row, meta) {
                            return row.business_name+'<br><small style="color: #716ACA">'+row.full_name+'</small>';
                        }

                    },
                    {
                        data: 'recipient_name',
                        name: 'recipient_name',
                        searchable: true,
                        render: function ( data, type, row ) {
                            return row.recipient_name+'<br><small style="color: #716aca;">'+row.recipient_email+'</small>'+'<br><small style="color: #716aca;">'+row.recipient_number+'</small>';
                        }
                    },
                    {
                        data: 'recipient_address',
                        name: 'recipient_address',
                        searchable: false

                    },
                    {
                        data: 'zone_name',
                        name: 'zone_name',
                        searchable: false

                    },
                    {
                        data: 'name',
                        name: 'name',
                        searchable: false

                    },
                    {
                        data: 'flag_text',
                        name: 'flag_text',
                        searchable: false,
                        render: function ( data, type, row ) {
                            var showText = row.flag_text;
                            return '<b style="color: '+row.color_code+';">'+showText+'</b>';

                        }

                    },
                    {
                        data: 'package_description',
                        name: 'package_description',
                        searchable: false

                    },
                    {
                        data: 'amount_to_be_collected',
                        name: 'amount_to_be_collected',
                        searchable: false

                    },
                    {
                        data: 'receive_amount',
                        name: 'receive_amount',
                        searchable: false

                    },
                    {
                        data: 'payment_status',
                        name: 'payment_status',
                        searchable: false,
                        render: function ( data, type, row ) {
                            var showText = '';
                            if(row.payment_status == 0)
                            {
                                showText = 'Unpaid';
                            }else{
                                showText = 'Paid';
                            }
                            return showText;
                        }

                    },
                    {
                        data: 'plan_name',
                        name: 'plan_name',
                        searchable: false

                    },
                    {
                        data: 'delivery_note',
                        name: 'delivery_note',
                        searchable: false

                    },
                    {
                        data: 'special_instruction',
                        name: 'special_instruction',
                        searchable: false
                    },
                    {
                        data: 'created_at',
                        name: 'created_at',
                        searchable: false
                    },
                    {
                        data: 'action_col',
                        name: 'action_col',
                        searchable: false
                    },
                ],
            });

            $('.dataTables_wrapper').removeClass('form-inline');
            $('.form-control').removeClass('input-sm');
            $('.dataTables_processing').addClass('m-loader m-loader--brand');

            $('#Deliverys-datatable_length').addClass('col-lg-2 col-md-2 col-sm-2');
            $('#Deliverys-datatable_length .form-control').addClass('m-bootstrap-select m-bootstrap-select--air filter-select');
            $('#sorting-filter').addClass('col-lg-2 col-md-2 col-sm-2');
            $('#hub-filter').addClass('col-lg-2 col-md-2 col-sm-2');
            $('#date-filter').addClass('col-lg-2 col-md-2 col-sm-2');
            $('#Deliverys-datatable_filter').addClass('col-lg-4 col-md-4 col-sm-4');


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

            var select_html =
                '<div class="form-group m-form__group clearfix">' +
                '<div class="input-group pull-right" >' +
                // '<label>select role</label>' +
                '<select id="sorting_role" class="form-control m_selectpicker1"><option value="">Filter by status</option><?php
                    if(!empty($allStatus)){
                        foreach ($allStatus as $status){
                            echo '<option value="'.$status->id.'">'.$status->flag_text.'</option>';
                        }
                    }?></select>' +
                '</div>' +
                '</div>';

            $('#sorting-filter').append(select_html);
            $('#sorting_role').change(function() {
                var role = $(this).val();
                table.columns(8).search(role).draw();
            });
            <?php if (get_admin_hub_id() > 0) { ?>
            //Zone Filter
            var zone_select_html =
                '<div class="form-group m-form__group clearfix">' +
                '<div class="input-group pull-right" >' +
                // '<label>select role</label>' +
                '<select id="sorting_zone" class="form-control m_selectpicker1"><option value="">Filter by Zone</option><?php
                    if(!empty($getZones)){
                        foreach ($getZones as $zone){
                            echo '<option value="'.$zone->id.'">'.$zone->zone_name.'</option>';
                        }
                    }?></select>' +
                '</div>' +
                '</div>';

            $('#hub-filter').append(zone_select_html);
            $('#sorting_zone').change(function() {
                var zones = $(this).val();
                table.columns(10).search(zones).draw();
            });

            <?php }else{ ?>
            //Hub Filter
            var hub_select_html =
                '<div class="form-group m-form__group clearfix">' +
                '<div class="input-group pull-right" >' +
                // '<label>select role</label>' +
                '<select id="sorting_hub" class="form-control m_selectpicker1"><option value="">Filter by Hub</option><?php
                    if(!empty($getHubs)){
                        foreach ($getHubs as $hub){
                            echo '<option value="'.$hub->id.'">'.$hub->hub_name.'</option>';
                        }
                    }?></select>' +
                '</div>' +
                '</div>';

            $('#hub-filter').append(hub_select_html);
            $('#sorting_hub').change(function() {
                var hub = $(this).val();
                table.columns(10).search(hub).draw();
            });

            <?php } ?>
            var consignment = [];
            $("#barcode_scanner").change(function () {
                var parcel = $(this).val().split("-");
                if(parcel[0] == 'PROVATI' && parcel[1].length == 8)
                {
                    consignment.push(parcel[1]);
                    table.columns(1).search(consignment).draw();
                    toastr.success('Data successfully found.');
                }else{
                    toastr.error('Invalid Input');
                }
                $("#barcode_scanner").val('');
            });

            $('#Deliverys-datatable_filter label:first-child input').attr('placeholder', 'by consignment id, Merchant Order ID, Recipient mobile no.');
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
                table.columns(13).search(start_date + ' ~ ' + end_date).draw();


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
        /*table.table.table-sm.m-table.m-table--head-bg-brand.table-striped.table-hover.dataTable.no-footer.DTFC_Cloned tr {
            background-color: #716aca!important;
        }
       .DTFC_RightWrapper {
           right: 0px!important;
       }
       i.fa.fa-cubes, i.fa.fa-edit{
           color:#fff!important
       }*/
    </style>

    <script src="{!! asset('backend/ezzyr_assets/jquery_validator/jquery.validate.js') !!}" type="text/javascript"></script>
    <script type="text/javascript">
        //== Class definition
        var Select2 = function() {
            //== Private functions
            var demos = function() {
                // basic
                $('#assign_to').select2({
                    placeholder: "Select"
                });
            };

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
        $(document).ready(function () {
            $("#div_hub").hide();
            $("#div_assign").hide();
            $("#div_receive_amount").hide();
            $("#form-sorting").validate({
                rules: {
                    // simple rule, converted to {required:true}
                    flag_status_id: {
                        required: true,
                    },

                    is_hub: {
                        required: function(element){
                            return $("#flag_status_id").val()!="" && $("#flag_status_id").val() == 10;
                        }
                    },

                    assign_to: {
                        required: function(element){
                            return $("#flag_status_id").val()!="" && $("#is_hub").val()!="" && $("#flag_status_id").val() == 10;
                        }
                    },
                },
                errorElement : 'label',
                errorClass : 'error_txt_size',
            });
        })
    </script>

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
                url: "{!! route('admin.deliverys.export') !!}",
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
        function viewProducts(id) {
            $.ajax({
                type: "POST",
                url: "{!! route('admin.delivery.product.view') !!}",
                data: {"_token": "{{ csrf_token() }}", "delivery_id": id},
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
                        $("#product-details-body tr").remove();
                        var sl =1;
                        var content ='';
                        var is_approve ='';
                        $.each(res.result, function (key, val) {
                            if(val['is_approve'] == 2){
                                is_approve = 'checked';
                            }else{
                                is_approve = '';
                            }
                            content +='<tr>';
                            content += '<td><input type="checkbox" '+is_approve+' class="m-checkbox m-checkbox--bold m-checkbox--state-brand" name="is_approve[]" value="'+val['id']+'">  </td>';
                            content += '<td>' + val['name'] + '</td>';
                            content += '<td>' + val['subtitle'] + '</td>';
                            content += '<td>' + val['sku'] + '</td>';
                            content += '<td>' + val['cat_name'] + '</td>';
                            content += '<td>' + val['store_name'] + '</td>';
                            content += '<td>' + val['description'] + '</td>';
                            content += '<td>' + val['sell_price'] + '</td>';
                            content += '<td>' + val['weight'] + '</td>';
                            content += '<td><small>Width : ' + val['width'] + '</small><br><small>Height : ' + val['height'] + '</small><br><small>Depth : ' + val['depth'] + '</small></td>';
                            content +='</tr>';
                        });
                        content +='';
                        $('#delivery_id').val(res.delivery_id);
                        $('#product-details-body').append(content);
                        $("#product-assign-Modal").modal('show');
                    }
                }
            });
        }

        $("#checkAll").click(function(){
            $('input:checkbox').not(this).prop('checked', this.checked);
        });
        $("#payment-receive-checkall").click(function(){
            $('.checkbox').not(this).prop('checked', this.checked);
        });
        $("#data-table-checkAll").click(function(){
            $('input:checkbox').not(this).prop('checked', this.checked);
        });

        function setApproval() {
            var formData = new FormData($('#product_details_form')[0]);
            formData.append("X-CSRF-Token", $('input[name="_token"]').val());
            $.ajax({
                type: "POST",
                url: "{!! route('admin.delivery.product.approval') !!}",
                data: formData,
                processData: false,
                contentType: false,
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
                        alert(res.msg);
                    }
                }
            });
        }


        $("#flag_status_id").change(function () {
            if ($(this).val() == 10){
                $.ajax
                ({
                    type: "POST",
                    url: "{!! route('admin.delivery.riders') !!}",
                    data: {"_token": "{{ csrf_token() }}","is_hub": 2},
                    cache: false,
                    beforeSend: function(){
                        $('#loader1').show();
                    },
                    complete: function(){
                        $('#loader1').hide();
                    },
                    success: function(html)
                    {
                        $("#label_assign_to").text("Select Rider");
                        $("#assign_to").html(html);
                    }
                });
                $("#div_assign").show();
            }else{
                $("#div_assign").hide();
            }
        });


        $("#flag_status_id").change(function () {
            if ($(this).val() == 5){
                $("#div_hub").show();
            }else{
                $("#div_hub").hide();
            }
            if ($(this).val() == 6 || $(this).val() ==7 || $(this).val() == 8){
                $.ajax
                ({
                    type: "POST",
                    url: "{!! route('admin.delivery.payment.received') !!}",
                    data: {"_token": "{{ csrf_token() }}","flag_status_id": $(this).val(),"delivery_id":getValueUsingID()},
                    cache: false,
                    beforeSend: function(){
                        $('#loader1').show();
                    },
                    complete: function(){
                        $('#loader1').hide();
                    },
                    success: function(res)
                    {
                        $("#payment-receive-details-body tr").remove();
                        var content ='';
                        var is_approve ='';
                        $.each(res.response, function (key, val) {
                            if(val['is_approve'] == 2){
                                is_approve = 'checked';
                            }else{
                                is_approve = '';
                            }
                            content +='<tr>';
                            content += '<td><input type="checkbox" class="checkbox modal-select-checkbox" '+is_approve+' class="m-checkbox m-checkbox--bold m-checkbox--state-brand" name="deliveried_id[]" value="'+val['deli_id']+'">  </td>';
                            content += '<td>' + val['consignment_id'] + '</td>';
                            content += '<td>' + val['delivery_date'] + '</td>';
                            content += '<td style="color: '+val['color_code']+'">' + val['flag_text'] + '</td>';
                            content += '<td>' + val['amount_to_be_collected'] + '</td>';
                            content += '<td><input type="text" class="form-control" value="' + val['amount_to_be_collected'] + '" name="received_amount'+val['deli_id']+'">';
                            content += '<input type="hidden" class="form-control" value="' + val['status'] + '" name="consignment_current_status[]">';
                            content += '<input type="hidden" class="form-control" value="' + val['consignment_id'] + '" name="consignment_id_'+val['deli_id']+'"></td>';
                            content += '<td><input type="text" class="form-control" name="note'+val['deli_id']+'"></td>';
                            content +='</tr>';
                        });
                        content +='';
                        $("#payment_flag_status_id").val($("#flag_status_id").val());
                        $('#payment-receive-details-body').append(content);
                        $("#product-payment-received-Modal").modal('show');
                    }
                });
                $("#div_receive_amount").show();
            }else{
                $("#div_receive_amount").hide();
            }
        });

        $("#is_hub").change(function () {
            if ($(this).val() == 2){
                $.ajax
                ({
                    type: "POST",
                    url: "{!! route('admin.delivery.riders') !!}",
                    data: {"_token": "{{ csrf_token() }}","is_hub": 2},
                    cache: false,
                    beforeSend: function(){
                        $('#loader1').show();
                    },
                    complete: function(){
                        $('#loader1').hide();
                    },
                    success: function(html)
                    {
                        $("#label_assign_to").text("Select Rider");
                        $("#assign_to").html(html);
                    }
                });
                $("#div_assign").show();
            }else if($(this).val() == 1){
                $.ajax
                ({
                    type: "POST",
                    url: "{!! route('admin.delivery.riders') !!}",
                    data: {"_token": "{{ csrf_token() }}","is_hub": 1},
                    cache: false,
                    beforeSend: function(){
                        $('#loader1').show();
                    },
                    complete: function(){
                        $('#loader1').hide();
                    },
                    success: function(html)
                    {
                        $("#label_assign_to").text("Select Hub");
                        $("#assign_to").html(html);
                    }
                });
                $("#div_assign").show();
            }else{
                $("#div_assign").hide();
            }
        });

        function getValueUsingID(){
            /* declare an checkbox array */
            var chkArray = [];

            /* look for all checkboes that have a class 'chk' attached to it and check if it was checked */
            $("#deliveried_id:checked").each(function() {
                chkArray.push($(this).val());
            });

            /* we join the array separated by the comma */
            var selected;
            selected = chkArray.join(',') ;

            /* check if there is selected checkboxes, by default the length is 1 as it contains one single comma */
            if(selected.length > 0){
                return selected;
            }else{
                alert("Please at least check one of the checkbox");
            }
        }
    </script>

@stop