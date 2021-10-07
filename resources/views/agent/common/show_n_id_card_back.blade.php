@extends('agent.layout.master')
@section('title') {!! $nationalid->first_name !!} {!! $nationalid->last_name !!}, Request to approved license @stop


@section('page_name')
    Show national id card back
    <small>Approve license</small>
@stop

@section ('breadcrumbs')
    <li> <a href="{!! route('agent.dashboard') !!}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
    <li class="active"> <a href="{!! route('agent.approve.show_license', array($nationalid->member_id)) !!}"> <i class="fa fa-dashboard"></i> {!! $nationalid->first_name !!} {!! $nationalid->last_name !!} </a></li>
@stop

@section('content')
    <!--begin::Portlet-->
    <div class="m-portlet">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <h3 class="m-portlet__head-text">
                        <a href="{!! route('agent.drivers') !!}" class="btn btn-sm btn-brand m-btn--pill">
                            <span class="fa fa-arrow-left"></span>&nbsp; Back
                        </a>
                    </h3>
                </div>
            </div>
            <div class="m-portlet__head-tools">
            </div>
        </div>
        <div class="m-portlet__body">
            <!--begin::Section-->
            <div class="m-section">               
                <div class="m-section__content"> 
                    <div class="row"> 
                        <div class="col-lg-6 col-md-6 col-sm-6"> 
                            <div class="m-card-profile">
                                <div class="m-card-profile__title m--hide">
                                    Your Profile
                                </div>
                                <div class="m-card-profile__pic">
                                    <div class="m-card-profile__pic-wrapper">
                                        <img src="{{ url('backend/dist/img/user1-128x128.jpg') }}" class="img-circle" alt="User Image">                                  
                                    </div>
                                </div>
                                <div class="m-card-profile__details">
                                    <span class="m-card-profile__name">
                                        {!! $nationalid->first_name !!} {!! $nationalid->last_name !!}
                                    </span>
                                    <span>
                                        {!! $nationalid->mobile_no !!}
                                    </span><br>
                                    <span>
                                        {!! $nationalid->email !!}
                                    </span>                                  
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-6"> 
                            <img class="img-responsive pad" src="{{ url('resources/national_id_back/'.$nationalid->image_file) }}" alt="Photo" style="width:100%;height:auto;">
                            <ul class="list-group">
                                {{--<li class="list-group-item"> <span class="pull-right" style="font-size: 16px; color: #00a157">{!! $license->license_number !!}</span> License Number </li>
                                <li class="list-group-item"> <span class="pull-right" style="font-size: 16px; color: #00a157">{!! $license->expire_date !!}</span> License Expire Date </li>--}}
                                <li class="list-group-item">
                                    <span class="pull-right" style="font-size: 16px; color: #00a157">
                                        @if ($nationalid->is_verified == 1)
                                            <span class="glyphicon glyphicon-ok text-green"> </span> Yes
                                        @else
                                            <span class="glyphicon glyphicon-remove text-red"> </span> No
                                        @endif
                                    </span>
                                    Is Approved
                                </li>
                            </ul>
                        </div>
                    </div> 
                    <div class="row">  
                        <div class="col-lg-6 col-md-6 col-sm-6">
                            
                        </div>     
                    </div>    
                </div>
            </div>
            <!--end::Section-->
        </div>
        <div class="m-portlet__foot">
            <div class="row align-items-center">
                <div class="col-lg-6">
                   
                    <span class="showSuccessMessage"> </span>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <button class="btn @if ($nationalid->is_verified == 1) btn-danger @else btn-success @endif pull-right approvedLicense" name="{!! $nationalid->member_id !!}" value="asdasd">
                        <span class="glyphicon @if ($nationalid->is_verified == 1)  glyphicon-remove @else  glyphicon-ok @endif text-white"> </span> 
                            @if ($nationalid->is_verified == 1) 
                                Disapprove 
                            @else 
                                Approve 
                            @endif  
                            <span class="glyphicon glyphicon-repeat normal-right-spinner" style="display: none"></span>
                    </button>
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript">

        $(document).ready(function(){
            $(".approvedLicense").click(function()
            {
                if (confirm('Are you sure, want to approve ?')) {
                    var member_id = $(this).attr('name');
                    var notification_id = $(this).attr('value');
                    $.ajax
                    ({
                        type: "POST",
                        url: "{!! route('agent.approve.national_id_back') !!}",
                        data: {"_token": "{{ csrf_token() }}", "member_id": member_id},
                        cache: false,
                        beforeSend: function(){
                            $('.normal-right-spinner').show();
                        },
                        complete: function(){
                            $('.normal-right-spinner').hide();
                        },
                        success: function(html)
                        {
                            alert("Successfully done");
                            //$(".showSuccessMessage").html(html);
                            location.reload();
                        }
                    });
                }
            });
        });
    </script>

@stop