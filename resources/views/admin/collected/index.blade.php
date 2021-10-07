@extends('admin.layout.master')
@section('title') Collected List @stop


@section('page_name')
     Collected  Management
    <small>All Collected </small>
@stop

@section ('breadcrumbs')
    <li> <a href="{!! route('admin.dashboard') !!}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
    <li class="active"> {!! link_to_route('admin.collected', ' Collected Management') !!} </li>
@stop

@section('content')

    <div class="m-portlet">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
{{--                <div class="m-portlet__head-title">--}}
{{--                    <h3 class="m-portlet__head-text">--}}
{{--                        <a href="{!! route('admin.deliveries.create') !!}" class="btn btn-sm btn-brand m-btn--pill">--}}
{{--                            <span class="la la-plus"></span>&nbsp; New Collected--}}
{{--                        </a>--}}
{{--                    </h3>--}}
{{--                </div>--}}
            </div>
            {{--            <div class="m-portlet__head-tools">--}}
            {{--                <div class="m-btn-group m-btn-group--pill btn-group-sm btn-group date-show-container export-date-show-container" role="group" aria-label="First group" style="display:none; margin-left: 5px; margin-right: 5px">--}}
            {{--                    <span class="m--font-brand"><b>Export from :</b></span> <span class="start-select-date" name=""> </span> <span class="m--font-brand"><b>To</b></span> <span class="end-select-date" name=""> </span>--}}
            {{--                </div>--}}

            {{--                <div class="m-btn-group m-btn-group--pill btn-group-sm btn-group" role="group" aria-label="First group">--}}
            {{--                    <span class="m-btn btn btn-light m-loader m-loader--brand m-loader--right m-loader--lg" id="loading-indicator" style="display: none" ></span>--}}
            {{--                </div>--}}

            {{--                <div class="m-btn-group m-btn-group--pill btn-group" role="group" aria-label="First group">--}}
            {{--                    <button type="button" class="m-btn btn btn-primary export-file" title="Print" name="xlsx">--}}
            {{--                        <i class="fa fa-file-excel-o"></i>--}}
            {{--                    </button>--}}
            {{--                    <button type="button" class="m-btn btn btn-success export-file" name="csv" title="Csv">--}}
            {{--                        <i class="fa fa-file-o"></i>--}}
            {{--                    </button>--}}
            {{--                    <button type="button" class="m-btn btn btn-brand export-file" title="Pdf" name="pdf">--}}
            {{--                        <i class="fa fa-file-pdf-o"></i>--}}
            {{--                    </button>--}}
            {{--                </div>--}}
            {{--            </div>--}}
        </div>
        <div class="m-portlet__body">
            <div class="m-section">
                <div class="m-section__content table-responsive">
                    <form method="post" action="{{route('admin.collected.hub.store')}}">
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
                            <div class="col-md-4 {!! $errors->has('is_hub') ? 'has-error' : ''  !!}" id="">
                                <label>Note</label>
                                <input type="text" name="notes" class="form-control" placeholder="your note .. ">
                                {!! $errors->first('is_hub', '<label class="error_txt_size">:message</label>') !!}
                            </div>
                            <div class="col-md-3 {!! $errors->has('assign_to') ? 'has-error' : ''  !!}" id="div_assign">
                                <label>Assign To <span class="text-danger">*</span></label>
                                <select class="form-control m-input" name="assign_to" id="assign_to">
                                </select>
                                {!! $errors->first('assign_to', '<label class="error_txt_size">:message</label>') !!}
                            </div>
                            <div class="col-md-1">
                                <label> &nbsp;</label>
                                <button style="margin-top: 0px;" type="submit" class="btn btn-brand" href="#" onclick="sortingDeliveris()">submit</button>
                            </div>
                        </div>
                        <table id="collected-datatable" class="table table-sm m-table m-table--head-bg-brand table-responsive table-striped table-hover custom_table_responsive">
                            <thead class="">
                            <tr>
                                <th>Sl.</th>
                                <th>Consignment Id</th>
                                <th>Merchant Name</th>
                                <th>Recipient Info</th>
                                <th>Recipient Address</th>
                                <th>Zone Name</th>
                                <th>Store</th>
                                <th>Status</th>
                                <th>Package Description</th>
                                <th>Amount to be collected</th>
                                <th>Plan Name</th>
                                <th>Collected note</th>
                                <th>Special Instruction</th>
                                <th>Collected Date</th>
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
{{--                        <div class="m-portlet__head">--}}
{{--                            <div class="m-portlet__head-caption">--}}
{{--                                <div class="m-portlet__head-title">--}}
{{--                                    <div class="m-section">--}}

{{--                                    </div>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        </div>--}}
                        <div style="padding-bottom: 15px;">
                            <label class="m-checkbox m-checkbox--bold m-checkbox--state-brand">
                                <input type="checkbox" id="checkAll">
                                Select ALL
                                <span></span>
                            </label>
                            <button class="btn btn-brand pull-right" onclick="setApproval()">Approve</button>
                        </div>
                        <form id="product_details_form">
                            <input type="hidden" name="Collected_id" id="Collected_id">
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

    <script type="text/javascript">
        $(document).ready(function(){
            var table = $('#collected-datatable').DataTable({
                processing: true,
                serverSide: true,
                paging: true,
                pageLength: 25,
                lengthChange: true,
                searching: false,
                ordering: true,
                info: true,
                autoWidth: false,
                dom : 'l<"#date-filter">frtip',
                ajax: {
                    url: '{!! route('admin.datatable.collected') !!}',
                    type: 'GET',
                    data: function( d ) {
                        d._token = "{{ csrf_token() }}";
                    }
                },
                columns: [
                    {
                        data: 'deli_id',
                        name: 'deli_id',
                        searchable: false,
                        render: function (data, type, row, meta) {
                            return '<label class="m-checkbox m-checkbox--bold m-checkbox--air">\n' +
                                '                            <input type="checkbox" name="deliveried_id[]" id="deliveried_id" value="'+row.deli_id+'">\n' +
                                '                            <span></span>\n' +
                                '                        </label>';
                        }

                    },
                    {
                        data: 'consignment_id',
                        name: 'consignment_id',
                        searchable: false,
                    },
                    {
                        data: 'full_name',
                        name: 'full_name',
                        searchable: false,
                        render: function (data, type, row, meta) {
                            return row.business_name+'<br><small style="color: #716ACA">'+row.full_name+'</small>';
                        }

                    },
                    {
                        data: 'recipient_name',
                        name: 'recipient_name',
                        searchable: false,
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
                            {{--if (row.is_hub == 1 && row.flag_status_id == 10 && row.assign_to == {{get_admin_hub_id()}}){--}}
                            {{--    showText = 'ASSIGN TO THIS HUB';--}}
                            {{--}--}}
                            {{--else if (row.is_hub == 2 && row.flag_status_id == 10 && row.assign_to != {{get_admin_hub_id()}}){--}}
                            {{--    showText = 'ASSIGN BY THIS HUB ';--}}
                            {{--}--}}
                            return '<b style="color: #716aca;">'+showText+'</b>';
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
                        data: 'delivery_date',
                        name: 'delivery_date',
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

            $('#collected-datatable_length').addClass('col-lg-6 col-md-6 col-sm-6');
            $('#collected-datatable_length .form-control').addClass('m-bootstrap-select m-bootstrap-select--air filter-select');
            $('#date-filter').addClass('col-lg-3 col-md-3 col-sm-3');
            $('#collected-datatable_filter').addClass('col-lg-3 col-md-3 col-sm-3');


            var date_picker_html =
                '<div class="form-group m-form__group clearfix">' +
                '<div class="input-group pull-right" id="m_daterangepicker">' +
                '<input type="text" class="form-control m-input" readonly  placeholder="Select date range" value=""/>' +
                '<span class="input-group-addon">' +
                '<i class="fa fa-calendar"></i>' +
                '</span>' +
                '</div>' +
                '</div>';

            // $('#date-filter').append(date_picker_html);
            $('#collected-datatable_filter label:first-child input').attr('placeholder', 'Name, mobile, email');


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
                startDate: moment().subtract(29, 'days'),
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
                url: "{!! route('admin.collected.export') !!}",
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
                url: "{!! route('admin.collected.product.view') !!}",
                data: {"_token": "{{ csrf_token() }}", "collected_id": id},
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
                        $('#Collected_id').val(res.Collected_id);
                        $('#product-details-body').append(content);
                        $("#product-assign-Modal").modal('show');
                    }
                }
            });
        }

        $("#checkAll").click(function(){
            $('input:checkbox').not(this).prop('checked', this.checked);
        });
        $("#data-table-checkAll").click(function(){
            $('input:checkbox').not(this).prop('checked', this.checked);
        });

        function setApproval() {
            var formData = new FormData($('#product_details_form')[0]);
            formData.append("X-CSRF-Token", $('input[name="_token"]').val());
            $.ajax({
                type: "POST",
                url: "{!! route('admin.collected.product.approval') !!}",
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
    </script>
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
    </script>
    <script>
        //City Select
        $(document).ready(function () {
            $("#div_hub").hide();
            $("#div_assign").hide();
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
            $(".selectMerchant").change(function()
            {

                var merchant_id = $(this).val();
                if (merchant_id == '')
                    return false;
                $.ajax
                ({
                    type: "POST",
                    url: "{!! route('admin.delivery.products') !!}",
                    data: {"_token": "{{ csrf_token() }}", "merchant_id": merchant_id},
                    cache: false,
                    beforeSend: function(){
                        $('#loader1').show();
                    },
                    complete: function(){
                        $('#loader1').hide();
                    },
                    success: function(html)
                    {
                        $("#selectProduct").html(html);
                    }
                });
                $.ajax
                ({
                    type: "POST",
                    url: "{!! route('admin.delivery.stores') !!}",
                    data: {"_token": "{{ csrf_token() }}", "merchant_id": merchant_id},
                    cache: false,
                    beforeSend: function(){
                        $('#loader1').show();
                    },
                    complete: function(){
                        $('#loader1').hide();
                    },
                    success: function(html)
                    {
                        $("#selectStore").html(html);
                    }
                });
            });
        });
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
    </script>
@stop