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

        @media print{
            .ddd{
                clear: both;
                display: block!important;
                page-break-after: always!important;
            }
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

            .pagebreak {
                page-break-before: always!important;
                display: block!important;
            }

            .pagebreak {
                clear: both;
                page-break-after: always!important;
                display: block!important;
            }
        }

        .ddd
        {
            page-break-after: always;
            page-break-inside: avoid;
        }
    </style>
    <?php
        $subTotal[] = 0;
        $total_charge[] = 0;
        $total[] = 0;
        $cod_charge[] = 0;
        $singleItem_charge[] = 0;
    ?>
    <!--begin::Portlet-->
    @if(!empty($flashMessageSuccess))
        <div class="alert alert-success alert-dismissible" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"></button>
            {{ $flashMessageSuccess }}
        </div>
    @endif

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
            <div class="m-portlet__head-tools">
                <span class="btn btn-brand m-btn m-btn--icon btn-lg m-btn--icon-only m-btn--pill m-btn--air" title="Print" onclick = "window.print()">
                    <i class="fa fa-print"></i>
                </span>
            </div>
        </div>
        <!--begin::Form-->

            {{--<div class="m-form m-form--fit m-form--label-align-right ">--}}
                <div class="m-portlet__body" id="section-to-print">
                    @forelse($merchants as $m_key => $merchant)
                        <?php
                        $subTotal[$m_key] = 0;
                        $total_charge[$m_key] = 0;
                        $total[$m_key] = 0;
                        $cod_charge[$m_key] = 0;
                        $singleItem_charge[$m_key] = 0;
                        ?>

                    <div class="row ddd">
                        <div class="col-sm-12 col-md-12 col-md-12 col-lg-12">
                            <div class="row">
                                <div class="col-sm-12 col-md-12 col-md-12 col-lg-12 text-center">
                                    <img src="{!! asset('backend/ezzyr_assets/app/media/img/logos/provati.png') !!}" width="250px">
                                </div>
                            </div>
                            <div class="row" style="padding: 35px;">
                                <div class="col-md-12">
                                    <div class="invoice-title">
                                        <h2>Invoice </h2><h5 class="pull-right">Date: {{$invoice_date[$m_key]}}</h5>
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
                                                <h1 style="color: #036417">PAID</h1>
                                            </address>
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
                                                                <th class="text-left"><strong>Consignment ID</strong></th>
                                                                <th class="text-center"><strong>Merchant Order ID</strong></th>
                                                                <th class="text-center"><strong>Amount to be collected</strong></th>
                                                                <th class="text-center"><strong>Received Amount</strong></th>
                                                                <th class="text-center"><strong>Delivery Charge</strong></th>
                                                                <th class="text-center"><strong>COD Charge</strong></th>
                                                                <th class="text-center"><strong>Recipient Name</strong></th>
                                                                <th class="text-center"><strong>Package Entry date</strong></th>
                                                                <th class="text-right"><strong>Totals</strong></th>
                                                            </tr>
                                                            </thead>
                                                            <tbody>
                                                            <!-- foreach ($order->lineItems as $line) or some such thing here -->
                                                            @forelse($invoices[$m_key] as $singleItem)
                                                                <?php
                                                                $total[$m_key] = $total[$m_key] + ($singleItem->receive_amount - ($singleItem->charge + $singleItem->cod_charge));
                                                                $total_charge[$m_key] = $total_charge[$m_key] + $singleItem->charge;
                                                                $cod_charge[$m_key] = $cod_charge[$m_key] + $singleItem->cod_charge;
                                                                $singleItem_charge[$m_key] = $singleItem_charge[$m_key] + $singleItem->charge;
                                                                ?>
                                                                <tr>
                                                                    <td class="text-left">{{$singleItem->consignment_id}}</td>
                                                                    <td class="text-center">{{!empty($singleItem->merchant_order_id) ? $singleItem->merchant_order_id : '-----'}}</td>
                                                                    <td class="text-center">{{$singleItem->amount_to_be_collected}}</td>
                                                                    <td class="text-center">{{$singleItem->receive_amount}}</td>
                                                                    <td class="text-center">{{$singleItem->charge}}</td>
                                                                    <td class="text-center">{{$singleItem->cod_charge}}</td>
                                                                    <td class="text-center">{{$singleItem->recipient_name}}</td>
                                                                    <td class="text-center">{{date('M j, Y', strtotime($singleItem->created_at))}}</td>
                                                                    <td class="text-right">{{$singleItem->receive_amount - ($singleItem->charge + $singleItem->cod_charge)}}</td>
                                                                </tr>
                                                            @empty
                                                                <p></p>
                                                            @endforelse
                                                            <tr>
                                                                <td class="thick-line"></td>
                                                                <td class="thick-line"></td>
                                                                <td class="thick-line"></td>
                                                                <td class="thick-line"></td>
                                                                <td class="thick-line text-center text-bold"><b>{{$singleItem_charge[$m_key]}}</b></td>
                                                                <td class="thick-line text-center text-bold"> <b>{{$cod_charge[$m_key]}}</b></td>
                                                                <td class="thick-line text-center"><strong></strong></td>
                                                                <td class="thick-line text-center"><strong></strong></td>
                                                                <td class="thick-line"></td>
                                                            </tr>

                                                            <tr>
                                                                <td class="no-line"></td>
                                                                <td class="no-line"></td>
                                                                <td class="no-line"></td>
                                                                <td class="no-line"></td>
                                                                <td class="no-line"></td>
                                                                <td class="no-line text-center"></td>
                                                                <td class="no-line text-center"> </td>
                                                                <td class="no-line text-center"><strong>Total</strong></td>
                                                                <td class="no-line text-right text-bold"> <b>BDT {{$total[$m_key]}}</b></td>
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
                                                    <div class="form-group">
                                                        <label>Notes</label>
                                                        <textarea name="notes" class="form-control" rows="4" placeholder="Invoice Notes ...">{!! !empty($inv_notes[$m_key]) ? $inv_notes[$m_key]->notes : '' !!}</textarea>
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Pay to merchant (BDT)</label>
                                                        <input type="text" class="form-control" readonly style="width: 30%;" placeholder="Amount" name="amount" value="{!!  $total[$m_key] !!}">
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                            <div class="pagebreak"> </div>



                    @empty
                    @endforelse
                </div>
            {{--</div>--}}

    <!--end::Form-->
    </div>


    <!--end::Portlet-->
    
@stop