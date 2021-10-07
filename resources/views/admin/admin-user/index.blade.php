@extends('admin.layout.master')
@section('title') Admin Use List @stop


@section('page_name')
	Admin User Management
	<small>All Users</small>
@stop

@section ('breadcrumbs')
    <li> <a href="{!! route('admin.dashboard') !!}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
    <li class="active"> {!! link_to_route('admin.admin-users', 'Admin User Management') !!} </li>
@stop

@section('content')
    <div class="m-portlet">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="row">
                    <div class="m-portlet__head-title col-sm-6">
                        <h3 class="m-portlet__head-text">
                            <a href="{!! route('admin.admin-user.new') !!}" class="btn btn-sm btn-brand m-btn--pill">
                                <span class="fa fa-plus"></span>&nbsp; New Admin User
                            </a>
                        </h3>
                    </div>
                </div>
            </div>
        </div>
        <div class="m-portlet__body">
            <!--begin::Section-->
            <div class="m-section">               
                <div class="m-section__content">               
                    <table class="table m-table table-responsive table-sm m-table--head-bg-brand table-striped table-hover">
                        <thead class="thead-inverse">
                            <tr>
                                <th>Sl.</th>
                                <th>Name</th>
                                <th>Hub</th>
                                <th>Email</th>
                                <th>Mobile</th>
                                <th>Is active</th>
                                <th>Can login</th>
                                <th>Role</th>
                                <th><center> Action </center></th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(count($users))
                            <?php $i = 0; ?>
                                @foreach($users as $user)
                                    <?php $i++ ?>
                                    <tr>
                                        <td>{{ $i }}</td>
                                        <td>{{ $user->first_name }} {{ $user->last_name }}</td>
                                        <td>{{ $user->hub_name }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td>{{ $user->mobile_no }}</td>
                                        <td>
                                            @if ($user->is_active == 1)
                                                <span class="fa fa-check m--font-success isActiveSts" style="cursor: pointer;" name="{{ $user->member_id }}"></span>
                                            @else
                                                <span class="fa fa-remove m--font-danger isActiveSts" style="cursor: pointer;" name="{{ $user->member_id }}"></span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($user->can_login == 1)
                                                <span class="fa fa-check m--font-success canLoginSts" style="cursor: pointer;" name="{{ $user->member_id }}"></span>
                                            @else
                                                <span class="fa fa-remove m--font-danger canLoginSts" style="cursor: pointer;" name="{{ $user->member_id }}"></span>
                                            @endif
                                        </td>
                                        <td>{{ $user->role_name }}</td>
                                        <td>
                                            <center>
                                                @if ($user->member_id !== 1)
                                                    <a href="{!! route('admin.admin-user.change.password',array($user->member_id)) !!}" class="btn btn-sm m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill" Title="Change Password">
                                                        <i class="fa fa-cogs"></i>
                                                    </a>
                                                    <a href="{!! route('admin.admin-user.edit',array($user->member_id)) !!}" class="btn btn-sm m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill" Title="Edit">
                                                        <i class="fa fa-edit"></i>
                                                    </a>
                                                    <a href="{!! route('admin.admin-user.delete', array($user->member_id)) !!}" class="btn btn-sm m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill" onclick="return confirm('Are you sure?')" title="Delete">
                                                        <i class="fa fa-trash"></i>
                                                    </a>
                                                @endif
                                            </center>
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="7" class="text-center text-danger">{{ "No data found" }}</td>
                                </tr>
                            @endif
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
                        {!! $users->render() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>







    <script type="text/javascript">
        $(document).ready(function(){
            $(".canLoginSts").click(function()
            {
                var member_id = $(this).attr('name');
                if (member_id == 1) return false;

                if (confirm('Are you sure you want to change ?')) {
                    $.ajax
                    ({
                        type: "POST",
                        url: "{!! route('admin.member.change_can_login') !!}",
                        data: {"_token": "{{ csrf_token() }}", "member_id": member_id},
                        cache: false,
                        success: function (datas) {
                            location.reload();
                            //$(".test").html(datas);
                        }
                    });
                }
            });
        });

        $(document).ready(function(){
            $(".isActiveSts").click(function()
            {
                var member_id = $(this).attr('name');
                if (member_id == 1) return false;

                if (confirm('Are you sure you want to change ?')) {
                    var member_id = $(this).attr('name');
                    $.ajax
                    ({
                        type: "POST",
                        url: "{!! route('admin.member.change_is_active') !!}",
                        data: {"_token": "{{ csrf_token() }}", "member_id": member_id},
                        cache: false,
                        success: function (datas) {
                            location.reload();
                            //$(".test").html(datas);
                        }
                    });
                }
            });
        });
    </script>
@stop