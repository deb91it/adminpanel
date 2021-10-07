<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
</head>
<body>
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
</style>

@forelse($invoices as $data)
    <?php $delivery = \App\DB\Admin\Delivery::with('merchant')->find($data); ?>
    <div class="wrapper">
        <div class="wrapper-header wrapper-row">
            <div class="left-part">
                <h3>Invoice </h3>
            </div>
            <div class="right-part">
                <h3>Order # {{$delivery->consignment_id}}</h3>
            </div>
        </div>
        <div class="clearfix"></div>
        <div class="wrapper-content wrapper-row">
            <div class="left-part">
                <h4><strong>Merchant Info:</strong></h4>
                {{$delivery->merchant->first_name." ".$delivery->merchant->last_name}}<br>
                {{$delivery->merchant->business_name}}<br>
                {{$delivery->merchant->address}}<br>
            </div>
            <div class="right-part">
                <h4> <strong>Shipped To:</strong></h4>
                {{$delivery->recipient_name}}<br>
                {{$delivery->recipient_number}}<br>
                {{$delivery->recipient_email}}<br>
                {{$delivery->recipient_address}}
            </div>
        </div>
        <div class="wrapper-content wrapper-row">
            <div class="left-part">
                <h4 class="margin-bottom-0"><strong>Delivery Date:</strong></h4>
                {{date('M J, Y',strtotime($delivery->delivery_date))}}
            </div>
            <div class="right-part">
                <h4 class="margin-bottom-0"> <strong>Order Date:</strong></h4>
                {{date('M J, Y',strtotime($delivery->created_at))}}<br>
            </div>
        </div>
        <div class="wrapper-summary wrapper-row">
            <h3><strong>Order summary</strong></h3>
            <table class="table" width="100%" cellspacing="0" cellpadding="0">
                <thead>
                <tr>
                    <th class="text-left"><strong>Item</strong></th>
                    <th class="text-left"><strong>Price</strong></th>
                    <th class="text-left"><strong>Package Description</strong></th>
                    <th class="text-right"><strong>Totals</strong></th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td>{{$delivery->consignment_id}}</td>
                    <td>{{$delivery->amount_to_be_collected}}</td>
                    <td>{{$delivery->package_description}}</td>
                    <td class="text-right">{{$delivery->amount_to_be_collected}}</td>
                </tr>
                <tr>
                    <td class="thick-line"></td>
                    <td class="thick-line"></td>
                    <td class="thick-line text-center"><strong>Total</strong></td>
                    <td class="thick-line text-right">{{$delivery->amount_to_be_collected}}</td>
                </tr>
{{--                <tr>--}}
{{--                    <td class="no-line"></td>--}}
{{--                    <td class="no-line"></td>--}}
{{--                    <td class="no-line text-center"><strong>Shipping</strong></td>--}}
{{--                    <td class="no-line text-right">BDT {{$delivery->charge}}</td>--}}
{{--                </tr>--}}
{{--                <tr>--}}
{{--                    <td class="no-line"></td>--}}
{{--                    <td class="no-line"></td>--}}
{{--                    <td class="no-line text-center"><strong>Total</strong></td>--}}
{{--                    <td class="no-line text-right">{{(float) $delivery->amount_to_be_collected + (float) $delivery->charge}}</td>--}}
{{--                </tr>--}}
                </tbody>
            </table>
        </div>
        <div class="wrapper-footer wrapper-row">
            <div class="left-part">
                <address>
                    <strong>Delivery Note:</strong><br>
                    {{$delivery->delivery_note}}
                </address>
            </div>
            <div class="right-part">
                <strong>Special Instruction:</strong><br>
                {{$delivery->special_instruction}}<br><br>
            </div>
        </div>
    </div>
    <div class="page-break"></div>
@empty
    <p></p>
@endforelse


<style>
    .margin-0{
        margin:0px!important;
    }
    .text-left{
        text-align: left!important;
    }
    .margin-bottom-0{
        margin-bottom:0px!important;
    }

    .wrapper{
        width:700px;
        margin: 0 auto;
        border:1px solid #f2f2f2;
    }
    .wrapper-header {
        border-bottom: 1px solid #f2f2f2;
        padding: 0px 15px;
        border-radius: 4px 4px 0px 0px;
    }
    .wrapper-content{
        padding: 0px 15px 0px 15px;
        -webkit-box-shadow: 0 1px 1px rgba(0,0,0,.05);
        box-shadow: 0 1px 1px rgba(0,0,0,.05);
    }
    .wrapper-summary{
        padding: 0px 15px 0px 15px;
    }
    .wrapper-footer{
        padding: 20px 15px 0px 15px;
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
        display: inline-block;
        width:50%;
    }
    .clearfix{
        clear: both;
    }
    .page-break{
        page-break-after: always!important;
    }
    .text-right{
        text-align: right;
    }
</style>
</body>
</html>
