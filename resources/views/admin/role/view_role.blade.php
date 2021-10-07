@extends('admin.layout.master')
@section('title') Show Role @stop


@section('page_name')
    {{--Show Role--}}
	<small></small>
@stop

@section ('breadcrumbs')
    <li> <a href="{!! route('admin.dashboard') !!}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
    <li> <a href="{{ url('/admin/role') }}"> Role </a> </li>
    <li class="active"> <a href="{{ url('/admin/role/'.$role->id.'/show/') }}"> Show Role</a> </li>
@stop

@section('content')

    <!--begin::Portlet-->
    <div class="m-portlet">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <h3 class="m-portlet__head-text">
                        Role Name: <i class="fa fa-hand-o-right text-info fa-2x" aria-hidden="true"></i> {{$role->role_name}}
                    </h3>
                </div>
            </div>
            <div class="m-portlet__head-tools">
                &nbsp;
                <a href="{{ url('/admin/role') }}" class="btn btn-default" tabindex="20">
                    <i class="fa fa-arrow-left text-danger fa-lg" aria-hidden="true"></i>&nbsp; <span class="text-danger"> Back </span>
                </a>
                &nbsp;
                <a href="{{ url('/admin/role/'.$role->id.'/edit/') }}" class="btn btn-success">
                    <i class="glyphicon glyphicon-edit glyphicon-white"></i> Edit
                </a>  
            </div>
        </div>
        <div class="m-portlet__body">
            <!--begin::Section-->
            <div class="m-section">               
                <div class="m-section__content"> 
                    <?php
                        $assigned_perms = explode(",",$role->permission->permissions);
                    ?>             
                    <table class="table table-sm m-table m-table--head-bg-brand table-responsive table-striped table-hover">
                        <thead class="thead-inverse">
                            <tr>
                                <th>Module/Group Name</th>
                                <th>View</th>
                                <th>Add</th>
                                <th>Edit</th>
                                <th>Delete</th>
                                <th>Execute</th>
                                <th>Others</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($groups as $group)
                                <tr>
                                    <td>
                                        {{ $group->group_name }}
                                    </td>
                                    @for($i = 0; $i < 5; $i++)
                                        <td style="vertical-align: middle">
                                            @if(isset($group->permissions[$i]->name) && $group->permissions[$i]->name != '')
                                                @if(in_array($group->permissions[$i]->name, $assigned_perms))
                                                    <label class="m-checkbox">
                                                        <input name="permission[]" type="checkbox" checked = "checked" value="{{ $group->permissions[$i]->name }}" disabled="disabled">
                                                        <span></span>
                                                    </label>
                                                @else
                                                    <label class="m-checkbox">
                                                        <input name="permission[]" type="checkbox" value="{{ $group->permissions[$i]->name }}" disabled="disabled">
                                                        <span></span>
                                                    </label>
                                                @endif
                                            @else
                                                {{ '--' }}
                                            @endif
                                        </td>
                                    @endfor
                                    <td>
                                        @for($i = 5; $i < count($group->permissions); $i++)
                                            @if(isset($group->permissions[$i]->name) && $group->permissions[$i]->name != '')
                                                @if(in_array($group->permissions[$i]->name, $assigned_perms))
                                                    <label class="checkbox-inline">
                                                        <input name="permission[]" type="checkbox" checked = "checked" value="{{ $group->permissions[$i]->name }}" disabled="disabled">
                                                         {{ $group->permissions[$i]->display_name }}
                                                    </label><br>
                                                @else
                                                    <label class="checkbox-inline">
                                                        <input name="permission[]" type="checkbox" value="{{ $group->permissions[$i]->name }}" disabled="disabled">
                                                        {{ $group->permissions[$i]->display_name }}
                                                    </label><br>
                                                @endif
                                            @else
                                                {{ '--' }}
                                            @endif
                                        @endfor
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
                <div class="pull-left">
                    &nbsp;
                    <a href="{{ url('/admin/role') }}" class="btn btn-default" tabindex="20">
                        <i class="fa fa-arrow-left text-danger fa-lg" aria-hidden="true"></i>&nbsp; <span class="text-danger"> Back </span>
                    </a>
                    &nbsp;
                    <a href="{{ url('/admin/role/'.$role->id.'/edit/') }}" class="btn btn-success">
                        <i class="glyphicon glyphicon-edit glyphicon-white"></i> Edit
                    </a> 
                </div>
            </div>
        </div>
        {!! Form::close() !!}
    </div>

    <style type="text/css">
        .m-checkbox input{
            position: inherit!important;
        }
        .m-checkbox{
            margin-bottom:0px!important;
        }
    </style>
@stop