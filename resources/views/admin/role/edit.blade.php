@extends('admin.layout.master')
@section('title') Edit Role @stop


@section('page_name')
   {{-- Edit Role--}}
	<small></small>
@stop

@section ('breadcrumbs')
    <li> <a href="{!! route('admin.dashboard') !!}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
    <li> <a href="{{ url('/admin/role') }}"> Role </a> </li>
    <li class="active"> <a href="{{ url('/admin/role/'.$role->id.'/edit/') }}"> Edit Role </a> </li>
@stop

@section('content')
    <!--begin::Portlet-->
    <div class="m-portlet">
        {!! Form::open(['route' => ['admin.role.update',$role->id], 'role' => 'form', 'method' => 'post', 'id' => 'edit-role']) !!}
         <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <h3 class="m-portlet__head-text">
                        <a href="{{ url('/admin/role') }}" class="btn btn-sm btn-brand m-btn--pill">
                            <span class="fa fa-plus"></span>&nbsp; Back
                        </a>
                    </h3>
                </div>
            </div>
            <div class="m-portlet__head-tools">
                <button class="btn btn-default" tabindex="20">
                    <i class="fa fa-check-circle text-success fa-lg" aria-hidden="true"></i>
                    <span class="text-success"> Save </span>
                </button>
                &nbsp;
                <span class="btn btn-default reset-form" tabindex="20">
                    <i class="fa fa-history text-info fa-lg" aria-hidden="true"></i>
                    <span class="text-success"> Reset </span>
                </span>
                &nbsp;
                <a href="{{ url('/admin/role') }}" class="btn btn-default" tabindex="20">
                    <i class="fa fa-remove text-danger fa-lg" aria-hidden="true"></i>&nbsp; <span class="text-danger"> Cancel </span>
                </a>  
            </div>
        </div>
        <div class="m-portlet__body">
            <!--begin::Section-->
            <div class="m-section">               
                <div class="m-section__content">
                    <div class="row">
                        <div class="form-group m-form__group col-lg-6 col-md-6 col-sm-6">
                            <label for="example_input_full_name">
                                Role Name:
                            </label>
                            {!! Form::text('role_name', $role->role_name, ['class' => 'form-control m-input','id'=>'role_name','value'=>Input::old('role_name'), 'placeholder' => 'Enter Role Name']) !!}
                            {!! $errors->first('role_name', '<label class="error_txt_size">:message</label>') !!}
                        </div>
                    </div>
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
                                                        <input name="permission[]" type="checkbox" checked = "checked" value="{{ $group->permissions[$i]->name }}">
                                                        <span></span>
                                                    </label>
                                                @else
                                                    <label class="m-checkbox">
                                                        <input name="permission[]" type="checkbox" value="{{ $group->permissions[$i]->name }}">
                                                        <span></span>
                                                    </label>
                                                @endif
                                            @else
                                                {{'--' }}
                                            @endif
                                        </td>
                                    @endfor
                                    <td>
                                        @for($i = 5; $i < count($group->permissions); $i++)
                                            @if(isset($group->permissions[$i]->name) && $group->permissions[$i]->name != '')
                                                @if(in_array($group->permissions[$i]->name, $assigned_perms))
                                                    <label class="checkbox-inline">
                                                        <input name="permission[]" type="checkbox" checked = "checked" value="{{ $group->permissions[$i]->name }}">
                                                        {{ $group->permissions[$i]->display_name }}
                                                    </label><br>
                                                @else
                                                    <label class="checkbox-inline">
                                                        <input name="permission[]" type="checkbox" value="{{ $group->permissions[$i]->name }}">
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
                    <button class="btn btn-default" tabindex="20">
                        <i class="fa fa-check-circle text-success fa-lg" aria-hidden="true"></i>
                        <span class="text-success"> Save </span>
                    </button>
                    &nbsp;
                    <span class="btn btn-default reset-form" tabindex="20">
                        <i class="fa fa-history text-info fa-lg" aria-hidden="true"></i>
                        <span class="text-success"> Reset </span>
                    </span>
                    &nbsp;
                    <a href="{{ url('/admin/role') }}" class="btn btn-default" tabindex="20">
                        <i class="fa fa-remove text-danger fa-lg" aria-hidden="true"></i>&nbsp; <span class="text-danger"> Cancel </span>
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
    <script type="text/javascript">
        $(document).ready(function(){
            $(".reset-form").click(function() {
                $(':input','#edit-role').not(':button, :submit, :reset, :hidden').val('').removeAttr('checked').removeAttr('selected');
            })
        });
    </script>
@stop