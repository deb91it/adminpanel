@extends('admin.layout.master')
@section('title') Statement List @stop


@section('page_name')
    Financial Statement Management
    <small>All Statement </small>
@stop

@section ('breadcrumbs')
    <li> <a href="{!! route('admin.dashboard') !!}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
    <li class="active"> {!! link_to_route('admin.financial.statement', ' Financial Statement Management') !!} </li>
@stop

@section('content')
<style>
    .cPad{
        padding: 15px;
    }
    .text-cust-color{
        color: #564EC0;
    }
    .custom-badge .m-badge {
        -webkit-border-radius: 3px!important;
        -moz-border-radius: 3px!important;
        -ms-border-radius: 3px!important;
        -o-border-radius: 3px!important;
        border-radius: 3px!important;
        min-height: 10px!important;
        min-width: 10px!important;
        margin-bottom: 3px!important;
    }



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

    .date-show{
        display: none;
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
        #section-to-print .hidden-on-print{
            visibility: hidden!important;
            display: none;
        }
        .date-hide{
            display: none;
        }

        #section-to-print .date-show{
            visibility: visible;
            display: block;
        }

        table tr td{
            width:200px;
        }

        table tr td:first-child{
            width:50px;
        }
    }
</style>
    <div class="m-portlet">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
            </div>
            <div class="m-portlet__head-tools">
                <span class="btn btn-brand m-btn m-btn--icon btn-lg m-btn--icon-only m-btn--pill m-btn--air" title="Print" onclick = "window.print()">
                    <i class="fa fa-print"></i>
                </span>
            </div>
        </div>
        <div class="m-portlet__body">
            <div class="m-section">
                <div class="m-section__content" id="section-to-print">
                    <form method="post" class="hidden-on-print" action="{{route('admin.financial.statement.search')}}">
                        {{csrf_field()}}
                        <div class="row">
                            <div class="col-md-3 col-sm-3 col-lg-3 col-xs-12">
                                <label>From <span class="text-danger">*</span></label>
                                <input type="text" value="{{isset($from_date) ? $from_date : ''}}" placeholder="{{date('Y-m-01')}}" name="from_date" class="form-control" id="from_date">
                            </div>
                            <div class="col-md-3 col-sm-3 col-lg-3 col-xs-12">
                                <label>To <span class="text-danger">*</span></label>
                                <input type="text" value="{{isset($to_date) ? $to_date : ''}}" placeholder="{{date('Y-m-d')}}" name="to_date" class="form-control" id="to_date">
                            </div>
                            <div class="col-md-2 col-sm-2 col-lg-2 col-xs-12">
                                <button style="margin-top: 28px;" type="submit" class="btn btn-brand"><i class="fa fa-search"> Search</i></button>
                            </div>
                        </div>
                    </form>
                    <div class="row mt-5">
                        <div class="col-md-6 col-sm-6 col-lg-6 col-xs-12">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th colspan="3" class="text-center text-success" style="border-bottom: 1px solid #f2f2f2;">
                                            <h4>Income</h4>
                                        </th>
                                    </tr>
                                    <tr>
                                       <th>Sl</th>
                                       <th>Date</th>
                                       <th>Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php
                                $rowCount = 1;
                                $sumIncome = 0;
                                ?>
                                @forelse($income as $key => $i)
                                    <?php $sumIncome = $sumIncome + round($i['income']); ?>
                                <tr>
                                    <td>{{$rowCount++ }}</td>
                                    <td>
                                        <a class="date-hide" href="{{route('admin.incomes')}}?income_date={{$i['date']}}" target="_blank">
                                            {{ !empty($i['date']) ? $i['date'] : '' }}
                                        </a>
                                        <span class="date-show">
                                            {{ !empty($i['date']) ? $i['date'] : '' }}
                                        </span>
                                    </td>
                                    <td>{{ !empty($i['income']) ? round($i['income']) : 0 }}</td>
                                </tr>
                                    @empty
                                <tr>
                                    <td>No Record found in this table</td>
                                </tr>
                                    @endforelse
                                </tbody>
                                <tfoot>
                                <tr>
                                    <th colspan="2" class="text-center">Total</th>
                                    <th>{{$sumIncome}}</th>
                                </tr>
                                </tfoot>
                            </table>
                        </div>
                        <div class="col-md-6 col-sm-6 col-lg-6 col-xs-12">
                            <table class="table table-bordered table-striped">
                                <thead>
                                <tr>
                                    <th colspan="3" class="text-center text-danger" style="border-bottom: 1px solid #f2f2f2;">
                                        <h4>Expense</h4>
                                    </th>
                                </tr>
                                <tr>
                                    <th>Sl</th>
                                    <th>Date</th>
                                    <th>Amount</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php $eCount = 1;$sumExpense = 0; ?>
                                @forelse($expense as $e)
                                    <?php $sumExpense = $sumExpense + round($e['expense']); ?>
                                    <tr>
                                        <td>{{$eCount++ }}</td>
                                        <td>
                                            <a class="date-hide" href="{{route('admin.expenses')}}?expense_date={{$e['date']}}" target="_blank">
                                            {{ !empty($e['date']) ? $e['date'] : '' }}
                                            </a>
                                            <span class="date-show">
                                                {{ !empty($e['date']) ? $e['date'] : '' }}
                                            </span>
                                        </td>
                                        <td>{{ !empty($e['expense']) ? round($e['expense']) : 0 }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td>No Record found in this table</td>
                                    </tr>
                                @endforelse
                                </tbody>
                                    <tfoot>
                                    <tr>
                                        <th colspan="2" class="text-center">Total</th>
                                        <th>{{$sumExpense}}</th>
                                    </tr>
                                    </tfoot>
                            </table>
                        </div>
                    </div>
                    <div class="row custom-badge mt-4">
                        <div class="col-md-12 col-sm-12 col-lg-12 col-xs-12">
                            <h6 class="text-cust-color">
                                <span class="m-badge m-badge--success mr-1"></span>
                                Profit Calculation = (Total Income - Total Expense)
                            </h6>
                            <h6 class="text-cust-color">
                                <span class="m-badge m-badge--success mr-1"></span>
                                Total Profit = <b>{{$sumIncome - $sumExpense}}</b>
                            </h6>
                        </div>
                    </div>
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

<script>
    //== Class definition
    var BootstrapDatetimepicker = function () {

        //== Private functions
        var demos = function () {
            $('#from_date, #to_date').datetimepicker({
                format: "yyyy-mm-dd",
                todayHighlight: true,
                autoclose: true,
                startView: 2,
                minView: 2,
                forceParse: 0,
                pickerPosition: 'bottom-left'
            });
        }
        return {
            // public functions
            init: function() {
                demos();
            }
        };
    }();

    jQuery(document).ready(function() {
        BootstrapDatetimepicker.init();
    });
</script>
@stop