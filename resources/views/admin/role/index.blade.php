@extends('admin.layout.master')
@section('title') Role List @stop

@section('page_name')
    Role Management
	<small>All Role</small>
@stop

@section ('breadcrumbs')
    <li> <a href="{!! route('admin.dashboard') !!}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
    <li class="active"> <a href="{{ url('/admin/role') }}"> Role </a> </li>
@stop

@section('content')
    <!--begin::Portlet-->
    <div class="m-portlet">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <h3 class="m-portlet__head-text">
                        <a href="{{ url('/admin/role/new') }}" class="btn btn-sm btn-brand m-btn--pill">
                            <span class="fa fa-plus"></span>&nbsp; New Role
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
                                <th>Role Name</th>
                                <th>Created At</th>
                                <th><center> Action </center></th>
                            </tr>
                        </thead>
                        <tbody>



                            @foreach($roles as $role)
                            <tr>
                                <td>{{ $role->id }}</td>
                                <td>{{ $role->role_name }}</td>
                                <td>{{ $role->created_at }}</td>
                                <td>
                                    <center>
                                        @if ($role->id !== 1)
                                            <a href="{{ url('/admin/role/'.$role->id.'/show/') }}" class="btn-sm btn btn-outline-info">
                                                <i class="glyphicon glyphicon-eye-open glyphicon-white"></i> Show permission
                                            </a>

                                            <a href="{{ url('/admin/role/'.$role->id.'/edit/') }}" class="btn-sm btn btn-outline-success">
                                                <i class="glyphicon glyphicon-edit glyphicon-white"></i> Edit
                                            </a>
                                            @if ($role->id > 4)
                                                <a href="{!! route('admin.role.delete', array($role->id)) !!}" class="btn-sm btn btn-outline-danger" onclick="return confirm('Are you sure?')">
                                                    <i class="glyphicon glyphicon-trash glyphicon-white"></i> Delete
                                                </a>
                                            @endif
                                        @endif
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
                        {!! $roles->render() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop