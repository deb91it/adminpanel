@extends('admin.layout.master')
@section('title') Permission Group List @stop


@section('page_name')
    Permission Group Management
	<small>All Groups</small>
@stop

@section ('breadcrumbs')
    <li> <a href="{!! route('admin.dashboard') !!}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
    <li class="active"> <a href="{{ url('/admin/permission-group') }}"> Permission Group </a> </li>
@stop

@section('content')




    <!--begin::Portlet-->
    <div class="m-portlet">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <h3 class="m-portlet__head-text">
                        <a href="{{ url('/admin/permission-group/create') }}" class="btn btn-sm btn-brand m-btn--pill">
                            <span class="fa fa-plus"></span>&nbsp; New Permission Group
                        </a>
                    </h3>
                </div>
            </div>
        </div>
        <div class="m-portlet__body">
            <!--begin::Section-->
            <div class="m-section">               
                <div class="m-section__content">               
                    <table class="table table-sm m-table m-table--head-bg-brand table-responsive table-striped table-hover">
                        <thead class="thead-inverse">
                            <tr>
                                <th>Sl.</th>
                                <th>Name</th>
                                <th>Created At</th>
                                <th><center> Action </center></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($groups as $group)
                                <tr>
                                    <td>{{ $group->id }}</td>
                                    <td>{{ $group->group_name }}</td>
                                    <td>{{ $group->created_at }}</td>
                                    <td>
                                        <center>
                                            <a href="{{ url('/admin/permission-group/'.$group->id.'/edit/') }}" class="btn btn-sm m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill" titel="Edit">
                                                <i class="fa fa-edit"></i>
                                            </a>
                                            <a href="{!! route('admin.permission-group.delete', array($group->id)) !!}" class="btn btn-sm m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill" title="Delete">
                                                <i class="fa fa-trash"></i>
                                            </a>
                                        </center>
                                    </td>
                                </tr>
                            @endforeach                        
                        </tbody>
                    </table>
                </div>
            </div>
            <!--end::Section-->
        </div>
        <div class="m-portlet__foot">
            <div class="row align-items-center">
                <div class="col-lg-6">
                   
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <div class="pull-right clearfix">
                        {!! $groups->render() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop