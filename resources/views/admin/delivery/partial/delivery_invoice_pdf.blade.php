{{--<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
</head>
<body>--}}
@extends('admin.layout.master')
@section('title') Delivery invoice @stop
@section('page_name')
    Delivery invoice
@stop

@section ('breadcrumbs')
    <li> <a href="{!! route('admin.dashboard') !!}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
    <li class="active"> {!! link_to_route('admin.deliverys', 'Delivery Management') !!} </li>
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

        #pagebreak
        {
            page-break-after: always;
            page-break-inside: avoid;
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
                width:100%;
            }
        }

    </style>
    <div class="m-portlet">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <span class="m-portlet__head-icon m--font-brand">
                        <i class="fa fa-star-o" aria-hidden="true"></i>
                    </span>
                    <h3 class="m-portlet__head-text">
                        Delivery invoice
                    </h3>
                </div>
            </div>
            <div class="m-portlet__head-tools">
                {{--<a style="margin-top: 0px;" class="btn btn-brand" href="{!! route('admin.delivery.invoice.pdf.download',json_encode($invoices)) !!}">download</a>--}}
                <span class="btn btn-brand m-btn m-btn--icon btn-lg m-btn--icon-only m-btn--pill m-btn--air" title="Print" onclick = "window.print()">
                    <i class="fa fa-print"></i>
                </span>
            </div>
        </div>

        <div class="m-portlet__body" id="section-to-print">
            <div class="m-section">
                <div class="m-section__content">
                    <div class="row justify-content-center">
                        <div class="col-md-4">
                            @forelse($invoices as $data)
                                <?php $delivery = \App\DB\Admin\Delivery::with('merchant')->find($data); ?>

                                <div class="row justify-content-center mb-5" id="pagebreak">
                                    <div class="col-md-12">
                                        <div class="row mt-3 pb-5">
                                            <div class="col-md-12">
                                                <img src="{!! asset('backend/ezzyr_assets/app/media/img/logos/provati.png') !!}" width="250px">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <h5>Merchant Info:</h5>
{{--                                                {{$delivery->merchant->first_name." ".$delivery->merchant->last_name}},<br>--}}
                                                {{$delivery->merchant->business_name}},<br>
                                                @if($delivery->merchant->member->mobile_no != '')
                                                    {{$delivery->merchant->member->mobile_no}},<br>
                                                @else
                                                @endif
{{--                                                {{$delivery->merchant->address}}<br>--}}
                                            </div>
                                        </div>
                                        <div class="row mt-3">
                                            <div class="col-md-12">
                                                <h5>Shipped To:</h5>
                                                {{$delivery->recipient_name}},<br>
                                                {{$delivery->recipient_number}},<br>
                                                {{$delivery->receipent_alternative_number ? $delivery->receipent_alternative_number : ''}},<br>
                                                {{$delivery->recipient_email}}<br>
                                                {{$delivery->recipient_address}}
                                            </div>
                                        </div>
                                        <div class="row mt-4">
                                            <div class="col-md-12">
                                                <h5>Order summary</h5>
                                                <b>Item: </b><span>{{$delivery->consignment_id}}</span><br>
                                                <b>Merchant Order ID: </b><span>{{ $delivery->merchant_order_id }}</span><br>
                                                <b>Order Date: </b>
                                                @if($delivery->created_at != '')
                                                    <span>{{ date('Y-m-d',strtotime($delivery->created_at)) }}</span>
                                                @else
                                                @endif
                                                <br>
                                                <b>Delivery Date: </b>
                                                @if($delivery->delivery_date != '')
                                                    <span>{{ $delivery->delivery_date }}</span>
                                                @else
                                                @endif
                                                <br>
                                                <b>Total Receivable: </b><span style="font-weight: bold">{{$delivery->amount_to_be_collected}} Tk</span>
                                            </div>
                                        </div>
{{--                                        <div class="row mt-3">--}}
{{--                                            <div class="col-md-12">--}}
{{--                                                <h6>Delivery Note:</h6>--}}
{{--                                                {{$delivery->delivery_note}}--}}
{{--                                            </div>--}}
{{--                                        </div>--}}
                                        <div class="row mt-3">
                                            <div class="col-md-12">
                                                <h6>Special Instruction:</h6>
                                                {{$delivery->special_instruction}}<br>
                                            </div>
                                        </div>
{{--                                        <div class="row mt-5">--}}
{{--                                            <div class="col-md-12">--}}
{{--                                                <span style="display: block;border-top: 1px dotted #000; padding-top: 5px;">Recipient Signature</span><br>--}}
{{--                                            </div>--}}
{{--                                        </div>--}}
                                        <div class="row mt-1">
                                            <div class="col-md-12">
                                                <?php
                                                    echo '<img src="data:image/png;base64,' . Milon\Barcode\DNS1D::getBarcodePNG("PROVATI-".$delivery->consignment_id, "C93",1,50) . '" alt="barcode"   />';
                                                ?>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-8">
                                                <span class="font-weight-bold" style="padding-left: 55px;"><b>{{$delivery->consignment_id}}</b></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <p></p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <style>
        .invoice-wrapper {
            border: 1px solid #f2f2f2;
        }
        .invoice-header{
            border-bottom: 1px solid #f2f2f2;
            padding-top: 8px;
            margin-bottom: 13px;
        }
        .wrapper{
            width:700px;
            margin: 0 auto;
        }
        .wrapper-header {
            border: 1px solid #f2f2f2;
            border-bottom: 0px;
            padding: 0px 15px;
            border-radius: 4px 4px 0px 0px;
        }
        .wrapper-content{
            padding: 15px;
            border: 1px solid #f2f2f2;
            margin-bottom: 40px;
            border-radius:0px 0px 4px 4px;
            -webkit-box-shadow: 0 1px 1px rgba(0,0,0,.05);
            box-shadow: 0 1px 1px rgba(0,0,0,.05);
        }
        .wrapper .wrapper-row{
            clear: both;
            display: block;
        }
        .left-part{
            float: left;
            display: inline-block;
            width:50%;
        }
        .right-part{
            text-align: right;
            float: right;
        }
        .clearfix{
            clear: both;
        }
        .text-right{
            text-align: right;
        }

    </style>
@stop
