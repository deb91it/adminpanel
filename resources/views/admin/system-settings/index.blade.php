@extends('admin.layout.master')
@section('title') System Settings @stop

@section('page_name')
    System Settings Management
    <small>System Settings</small>
@stop

@section ('breadcrumbs')
    <li> <a href="{!! route('admin.dashboard') !!}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
    <li class="active"> <a href=""> System Settings </a> </li>
@stop

@section('content')
    <div class="row">

        <div class="col-lg-12">
            <div class="m-portlet m-portlet--full-height m-portlet--tabs ">
                <div class="m-portlet__head">
                    <div class="m-portlet__head-tools">
                        <ul class="nav nav-tabs m-tabs m-tabs-line   m-tabs-line--left m-tabs-line--primary" role="tablist">
                            <li class="nav-item m-tabs__item">
                                <a class="nav-link m-tabs__link active" data-toggle="tab" href="#m_user_profile_tab_1-" role="tab">
                                    <i class="flaticon-share m--hide"></i>
                                    Settings Main
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="m-portlet__head">
                    <div class="m-portlet__head-caption">
                        <div class="m-portlet__head-title">
                            <h3 class="m-portlet__head-text">
                                <a href="{!! route('admin.system-settings.create') !!}" class="btn btn-sm btn-brand m-btn--pill">
                                    <span class="la la-plus"></span>&nbsp; New Settings
                                </a>
                            </h3>
                        </div>
                    </div>
                </div>
                <div class="tab-content">
                    <div class="tab-pane active" id="m_user_profile_tab_1">
                        <div class="m-portlet__body">
                            <div class="m-section">
                                <div class="m-section__content">
                                    <table class="table table-sm m-table m-table--head-bg-brand table-responsive table-striped table-hover custom_table_responsive">
                                        <thead class="">
                                        <tr>
                                            <th>Sl.</th>
                                            <th>Company Name</th>
                                            <th>Logo</th>
                                            <th><center> Action </center></th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @if(count($settings))
                                            <?php $i = 1; ?>
                                            @foreach($settings as $sett)
                                                <tr>
                                                    <td>{{ $i }}</td>
                                                    <td>
                                                        {{ $sett->name }} <br/>
                                                    </td>
                                                    <td><img src="{{ $sett->logo_url  }}" height="40px" width="40px"> </td>
                                                    <td>
                                                        <a href="{!! route('admin.system-settings.edit',array($sett->id)) !!}" class="btn btn-sm m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill" title="Edit"><i class="fa fa-edit"></i></a>
                                                        <a onclick="viewDetails({{ $sett->id  }})" class="btn btn-sm m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill" title="Details"><i class="fa fa-eye"></i></a>
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
                                {!! $settings->render() !!}
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane" id="m_user_profile_tab_4">tab three</div>
                </div>
            </div>
        </div>
    </div>
    <!-- Metronic modal -->
    <!--begin::Modal-->
    <div class="modal fade" id="settingsDetailsModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Settings Details</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="m-portlet__body">
                        <div class="m-section">
                            <div class="m-section__content">
                                <table id="settings-details" class="table table-sm m-table m-table--head-bg-brand table-responsive table-striped table-hover custom_table_responsive">
                                    <thead class="">
                                    <tr>
                                        <th>Sl</th>
                                        <th>Name</th>
                                        <th>Value</th>
                                    </tr>
                                    </thead>
                                    <tbody id="settings-details-body"></tbody>
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

    <style>
        a.m-nav__link.active {
            background-color: #eeeeee;
            border-color: #eee #eee #ddd;
            border-radius: 0px!important;
        }
    </style>
    <script>
        function viewDetails(company_id) {
            $.ajax({
                type: "GET",
                url: "{{URL('admin/system-settings/details')}}/" + company_id,
                cache: false,
                beforeSend: function () {
                    $('#loading-indicator').show();
                },
                complete: function () {
                    $('#loading-indicator').hide();
                },
                success: function (res) {
                    if (res.success === false) {
                        $("#employee-details")[0].reset();
                    } else {
                        $("#settings-details-body tr").remove();
                        var content ='';
                        var i =1;
                        $.each(res, function (key, val) {
                            content +='<tr>';
                            content += '<td>' + i++ + '</td>';
                            content += '<td>' + val['config_key'].replace(/_/g, ' ').toUpperCase() + '</td>';
                            content += '<td>' + val['config_value'] + '</td>';
                            content +='</tr>';
                        });
                        content +='';
                        $('#settings-details-body').append(content);
                    }
                    $('#settingsDetailsModal').modal('show');
                }
            });
        }
    </script>
@stop