@extends('admin.layout.master')
@section('title')Invoice  @stop


@section('page_name')
    Invoice  Management
    <small>Invoice Details</small>
@stop

@section ('breadcrumbs')
    <li> <a href="{!! route('admin.dashboard') !!}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
    <li class="active"> {!! link_to_route('admin.agents', 'Invoice  Management') !!} </li>
@stop

@section('content')
    <style>
        .invoice-title h2, .invoice-title h3 {
            display: inline-block;
        }

        .table > tbody > tr > .no-line {
            border-top: none;
        }

        .table > thead > tr > .no-line {
            border-bottom: none;
        }

        .table > tbody > tr > .thick-line {
            border-top: 2px solid;
        }

        @media print {
            body * {
                visibility: hidden;
            }
            #section-to-print, #section-to-print * {
                visibility: visible;
            }
            #section-to-print {
                position: absolute;
                left: 0;
                top: 0;
            }
            #section-to-print #ddd{
                visibility: hidden;
            }
        }
    </style>
    <?php
        $subTotal = 0;
        $total_charge = 0;
        $total = 0;
        $cod_charge = 0;
        $delivery_charge = 0;
        $collectedAmount = 0;
        $receivedAmount = 0;
        $invoiceAmount = 0;
        $invoiceAdditionalAmount = 0;
    ?>
    <!--begin::Portlet-->
    <div class="m-portlet">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <span class="m-portlet__head-icon m--font-brand">
                        <i class="fa fa-edit"></i>
                    </span>
                    <h3 class="m-portlet__head-text">
                        Invoice
                    </h3>

                </div>
            </div>
            <?php $invoiceId = !empty($_GET['invoice_id']) ? '&invoice_id='.$_GET['invoice_id'] : '' ?>
            <div class="m-portlet__head-tools">
                <a href="{{route('admin.invoice.notes',$merchant->id).'?invoice_type='.$_GET['invoice_type'].'&export=true'.$invoiceId}}" class="btn btn-outline-info"><i class="fa fa-download">&nbsp;</i></a>
                <span class="btn btn-brand m-btn m-btn--icon btn-lg m-btn--icon-only m-btn--pill m-btn--air" title="Print" onclick = "window.print()">
                    <i class="fa fa-print"></i>
                </span>
            </div>
        </div>
        <!--begin::Form-->
        <?php if ($_GET['invoice_type'] == "unpaid") { ?>
        <div class="card">
            <div class="card-body">
                <form method="get" action="{{route('admin.invoice.notes', $merchant->id.'?invoice_type='.$_GET['invoice_type'])}}">
                    <div class="row">
                        <input type="hidden" name="invoice_type" value="{{$_GET['invoice_type']}}">
                        <div class="col-md-4 col-sm-4 col-lg-4">
                            <input type="text" class="form-control" name="search_string" value="{{isset($_GET['search_string']) && $_GET['search_string'] ? $_GET['search_string'] : ''}}" placeholder="Search by consignment id or merchant order id">
                        </div>
                        <div id="date-filter" class="col-md-4 col-sm-4 col-lg-4">
                            <div class="form-group m-form__group clearfix">
                                <div class="input-group pull-right" id="m_daterangepicker">
                                    <input type="text" name="date_range" id="searchDateRangePicker" value="{{ isset($_GET['date_range']) && $_GET['date_range'] ? $_GET['date_range'] : ''}}" class="form-control m-input" readonly  placeholder="Select date range" value=""/>
                                    <span class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </span>
                                </div>
                            </div>;
                        </div>
                        <div class="col-md-2 col-sm-2 col-lg-2">
                            <button type="submit" class="btn btn-default"><i class="fa fa-search text-primary"> Search</i></button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <?php } ?>
        <form method="post" action="{{route('admin.invoice.note.store')}}">
            {{csrf_field()}}
            <input type="hidden" name="invoice_date" value="{{$invoice_date}}">
            <input type="hidden" name="merchant_id" value="{{$merchant->id}}">
            <div class="m-form m-form--fit m-form--label-align-right ">
                <div class="m-portlet__body" id="section-to-print">
                    <div class="row">
                        <div class="col-sm-12 col-md-12 col-md-12 col-lg-12 text-center">
                            <img src="{!! asset('backend/ezzyr_assets/app/media/img/logos/provati.png') !!}" width="250px">
                        </div>
                    </div>
                    <div class="row" style="padding: 35px;">
                        <div class="col-md-12">
                            <div class="invoice-title">
                                <h2>Invoice </h2><h5 class="pull-right">Date: {{$invoice_date}}</h5>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-xs-6">
                                    <address>
                                        <b>Merchant Info:</b><br>
                                        {{$merchant->first_name." ".$merchant->last_name}}<br>
                                        {{$merchant->business_name}}<br>
                                        {{$merchant->address}}<br>
                                    </address>
                                </div>
                                <div class="col-xs-6 text-right" style="padding-right: 15px;">
                                    <address>
                                        <h1 style="color: {{$_GET['invoice_type'] == 'unpaid' ? '#AB1803':'#036417' }}">{{$_GET['invoice_type'] == "unpaid" ? 'UNPAID' : 'PAID'}}</h1>
                                    </address>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row" style="padding: 35px;">
                        <div class="col-md-12">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h3 class="panel-title"><strong>Order summary</strong></h3>
                                </div>
                                <div class="panel-body">
                                    <div class="table-responsive">
                                        <table class="table table-condensed">
                                            <thead>
                                            <tr>
                                                <th class="text-left select-header"><strong><input type="checkbox" id="data-table-checkAll"></strong></th>
                                                <th class="text-left"><strong>Consignment ID</strong></th>
                                                <th class="text-center"><strong>Merchant Order ID</strong></th>
                                                <th class="text-center"><strong>Status</strong></th>
                                                <th class="text-center"><strong>Amount to be collected</strong></th>
                                                <th class="text-center"><strong>Received Amount</strong></th>
                                                <th class="text-center"><strong>Plan Charge</strong></th>
                                                <th class="text-center"><strong>COD Charge</strong></th>
                                                <th class="text-center"><strong>Recipient Name</strong></th>
                                                <th class="text-center"><strong>Package Entry date</strong></th>
                                                <th class="text-right"><strong>Totals</strong></th>
                                            </tr>
                                            </thead>
                                            <tbody id="invoice-summary">
                                            <!-- foreach ($order->lineItems as $line) or some such thing here -->
                                            @forelse($invoices as $delivery)
                                                <?php
                                                $total = $total + ($delivery->receive_amount - ($delivery->charge + $delivery->cod_charge));
                                                $total_charge = $total_charge + $delivery->charge;
                                                $cod_charge = $cod_charge + $delivery->cod_charge;
                                                $delivery_charge = $delivery_charge + $delivery->charge;
                                                $collectedAmount = $collectedAmount + $delivery->amount_to_be_collected;
                                                $receivedAmount = $receivedAmount + $delivery->receive_amount;
                                                if ($_GET['invoice_type'] == "paid")
                                                {
                                                    $invoiceAmount =  $delivery->invoice_amount;
                                                    $invoiceAdditionalAmount =  $delivery->additional_amount;
                                                }
                                                ?>
                                                <tr>
                                                    <td class="text-left select-header-text">
                                                        <input type="checkbox" onclick="summationOfCheckedVal()" class="checkbox_delivery" name="delivery_id[]" value="{{$delivery->id}}">
                                                    </td>
                                                    <td class="text-left">{{$delivery->consignment_id}}</td>
                                                    <td class="text-center">{{!empty($delivery->merchant_order_id) ? $delivery->merchant_order_id : '-----'}}</td>
                                                    <td class="text-center"><span class="label label-default" style="background-color: {{$delivery->color_code}}"> {{$delivery->flag_text}}</span></td>
                                                    <td class="text-center" id="amount_to_be_collected{{$delivery->id}}">{{round($delivery->amount_to_be_collected)}}</td>
                                                    <td class="text-center" id="receive_amount{{$delivery->id}}">{{round($delivery->receive_amount)}}</td>
                                                    <td class="text-center" id="charge{{$delivery->id}}"><span style="color: {{$delivery->color_code}}">{{round($delivery->charge)}}</span></td>
                                                    <td class="text-center" id="cod_charge{{$delivery->id}}">{{round($delivery->cod_charge)}}</td>
                                                    <td class="text-center">{{$delivery->recipient_name}}</td>
                                                    <td class="text-center">{{date('M j, Y', strtotime($delivery->created_at))}}</td>
                                                    <td class="text-right" id="totalAmount{{$delivery->id}}">{{round($delivery->receive_amount - ($delivery->charge + $delivery->cod_charge))}}</td>
                                                </tr>
                                            @empty
                                                <p></p>
                                            @endforelse
                                            <tr>
                                                <td class="thick-line select-header-line"></td>
                                                <td class="thick-line"></td>
                                                <td class="thick-line"></td>
                                                <td class="thick-line"></td>
                                                <td class="thick-line text-center text-bold" id="totalCollectedAmount" data-value="{{round($collectedAmount)}}">{{$_GET['invoice_type'] == "paid" ?  round($collectedAmount) : 0}}</td>
                                                <td class="thick-line text-center text-bold" id="totalReceivedAmount" data-value="{{round($receivedAmount)}}">{{$_GET['invoice_type'] == "paid" ? round($receivedAmount) : 0}}</td>
                                                <td class="thick-line text-center text-bold" id="totalDeliveryCharge" data-value="{{round($delivery_charge)}}"><b>{{$_GET['invoice_type'] == "paid" ? round($delivery_charge) : 0}}</b></td>
                                                <td class="thick-line text-center text-bold" id="totalCodCharge" data-value="{{round($cod_charge)}}"> <b>{{$_GET['invoice_type'] == "paid" ? round($cod_charge) : 0}}</b></td>
                                                <td class="thick-line text-center"><strong></strong></td>
                                                <td class="thick-line text-center"><strong></strong></td>
                                                <td class="thick-line"></td>
                                            </tr>

                                            <tr>
                                                <td class="no-line select-header-no-line"></td>
                                                <td class="no-line"></td>
                                                <td class="no-line"></td>
                                                <td class="no-line"></td>
                                                <td class="no-line"></td>
                                                <td class="no-line"></td>
                                                <td class="no-line text-center"></td>
                                                <td class="no-line text-center"> </td>
                                                <td class="no-line text-center"><strong>Total</strong></td>
                                                <td class="no-line text-right text-bold"> <span id="payMerchantTotalAmount" data-value="{{round($total)}}"><b>BDT {{$_GET['invoice_type'] == "paid" ? round($total) : 0}}</b></span></td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="row">
                                        <div class="col-xs-6">

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="col-md-12" id="ddd">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h3 class="panel-title"><strong>Activity</strong></h3>
                                </div>
                                <div class="panel-body">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Pay to merchant (BDT)</label>
                                            <input type="text" class="form-control" {{$_GET['invoice_type'] == "paid" ? "readonly" : ""}} placeholder="Amount" id="amount" name="amount" data-value="{{round($invoiceAmount)}}" value="{!! $_GET['invoice_type'] == "paid" ? round($invoiceAmount) : 0 !!}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Additional Charge</label>
                                            <input type="text" class="form-control" {{$_GET['invoice_type'] == "paid" ? "readonly" : ""}} placeholder="Amount" id="additional_amount" name="additional_amount" value="{!! $_GET['invoice_type'] == "paid" ? $invoiceAdditionalAmount : 0 !!}">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label>Notes</label>
                                        <textarea name="notes" class="form-control" {{$_GET['invoice_type'] == "paid" ? "readonly" : ""}} rows="4" placeholder="Invoice Notes ...">{!! !empty($inv_notes) && $_GET['invoice_type'] == "paid" ? $inv_notes->notes : '' !!}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="m-portlet__foot m-portlet__no-border m-portlet__foot--fit">
                    <div class="m-form__actions m-form__actions--solid">
                        <div class="row">
                            <?php if ($_GET['invoice_type'] == "unpaid") { ?>
                            <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12 col-lg-offser-2 col-md-offset-2 col-sm-offset-2">
                                <div class="pull-left">
                                    <button class="btn btn-default" tabindex="20">
                                        <i class="fa fa-check-circle text-success fa-lg" aria-hidden="true"></i>
                                        <span class="text-success"> Done </span>
                                    </button>
                                    &nbsp;
                                    <a href="{!! route('admin.invoices') !!}" class="btn btn-default" tabindex="20">
                                        {{-- <span class="glyphicon glyphicon-remove text-danger"></span>&nbsp; <span class="text-danger"> Cancel </span>--}}
                                        <i class="fa fa-remove text-danger fa-lg" aria-hidden="true"></i>&nbsp; <span class="text-danger"> Cancel </span>
                                    </a>
                                </div>
                            </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    <!--end::Form-->
    </div>
    <!--end::Portlet-->
    <script>
        <?php
            if ($_GET['invoice_type'] == "paid")
                {
        ?>
        $(".select-header").remove();
        $(".checkbox_delivery").remove();
        $(".select-header-text").remove();
        $(".select-header-line").remove();
        $(".select-header-no-line").remove();
        <?php } ?>
        //var itemsAmount = [];

        $("#additional_amount").change(function () {
            var additionalAmount = $(this).val() ? $(this).val() : 0;
            var calc =  parseFloat($("#payMerchantTotalAmount").text()) - parseFloat(additionalAmount);
            $("#amount").val(calc);
        })

        $("#data-table-checkAll").click(function(){
            $('input:checkbox').not(this).prop('checked', this.checked);
            summationOfCheckedVal();
        });

        function summationOfCheckedVal()
        {
            var sum = 0;
            var summationOfCollectedAmount = 0;
            var summationOfReceivedAmount = 0;
            var summationOfDeliveryCharge = 0;
            var summationOfCODCharge = 0;

            $("#invoice-summary input[type=checkbox]:checked").each(function () {
                // console.log($(this).val())
                sum = sum + parseFloat($("#totalAmount"+$(this).val()).text());
                summationOfCollectedAmount = summationOfCollectedAmount + parseFloat($("#amount_to_be_collected"+$(this).val()).text());
                summationOfReceivedAmount = summationOfReceivedAmount + parseFloat($("#receive_amount"+$(this).val()).text());
                summationOfDeliveryCharge = summationOfDeliveryCharge + parseFloat($("#charge"+$(this).val()).text());
                summationOfCODCharge = summationOfCODCharge + parseFloat($("#cod_charge"+$(this).val()).text());
            });
            // console.log(sum + ' c: '+summationOfCollectedAmount + ' R: '+summationOfReceivedAmount+' DC: '+summationOfDeliveryCharge +' CC: '+summationOfCODCharge)
            $("#amount").val(sum);
            $("#totalCollectedAmount").text(summationOfCollectedAmount);
            $("#totalReceivedAmount").text(summationOfReceivedAmount);
            $("#totalDeliveryCharge").text(summationOfDeliveryCharge);
            $("#totalCodCharge").text(summationOfCODCharge);
            $("#payMerchantTotalAmount").text(sum);
        }

        $(document).ready(function ()
        {
            $(window).keydown(function(event){
                if(event.keyCode == 13) {
                    event.preventDefault();
                    return false;
                }
            });
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
                $("#searchDateRangePicker").val(start_date + ' ~ ' + end_date);


                // To view beside export buttons
                $('.date-show-container').show();
                $('.start-select-date').html(start.format('DD-MM-YYYY'));
                $('.start-select-date').attr('name', start.format('YYYY-MM-DD'));
                $('.end-select-date').html(end.format('DD-MM-YYYY'));
                $('.end-select-date').attr('name', end.format('YYYY-MM-DD'));
            });
        })

    </script>
@stop