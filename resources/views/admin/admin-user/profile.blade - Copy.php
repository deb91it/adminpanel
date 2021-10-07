@extends('admin.layout.master')
@section('title') Create New User @stop


@section('page_name')
    &nbsp;
    User profile
    <small></small>
@stop

@section ('breadcrumbs')
    <li> <a href="{!! route('admin.dashboard') !!}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
    <li class="active"> {!! link_to_route('admin.user_profile', 'User profile') !!} </li>
@stop

@section('content')
    <div class="row">
        <div class="col-md-3">

            <!-- Profile Image -->
            <div class="box box-primary">
                <div class="box-body box-profile">
                    @if($user->profile_pic != '')
                        <img src="{{ url('resources/profile_pic/'.$user->profile_pic) }}" class="img-circle" alt="User Image" height="200" width="240">
                    @else
                        <img src="{{ url('backend/dist/img/avatar.png') }}" class="img-circle" alt="User Image" height="200" width="240">
                    @endif

                    <h3 class="profile-username text-center"> {{ $user->first_name }} {{ $user->last_name }}</h3>
                    <p class="text-muted text-center">{{ $user->designation }}</p>

                   {{-- <ul class="list-group list-group-unbordered">
                        <li class="list-group-item">
                            <b>Followers</b> <a class="pull-right">1,322</a>
                        </li>
                        <li class="list-group-item">
                            <b>Following</b> <a class="pull-right">543</a>
                        </li>
                        <li class="list-group-item">
                            <b>Friends</b> <a class="pull-right">13,287</a>
                        </li>
                    </ul>--}}

                    <a href="{{ url('/admin/edit-profile') }}" class="btn btn-primary btn-block">Edit</a>
                </div><!-- /.box-body -->
            </div><!-- /.box -->
        </div>

        <div class="col-md-9">
            <div class="nav-tabs-custom">
                <ul class="nav nav-tabs">
                    <li class="active"><a href="#activity" data-toggle="tab">Details </a></li>
                </ul>
                <div class="tab-content">
                    <div class="box box-widget widget-user-2">
                        <div class="box-footer no-padding">
                            <ul class="nav nav-stacked">
                                <li><a href="#">First name <span class="pull-right"> {{ $user->first_name }} </span></a></li>
                                <li><a href="#">Last name <span class="pull-right"> {{ $user->last_name }} </span></a></li>
                                <li><a href="#">Designation <span class="pull-right"> {{ $user->designation }} </span></a></li>
                                <li><a href="#">Email <span class="pull-right"> {{ $user->email }} </span></a></li>
                                <li><a href="#">Mobile no. <span class="pull-right"> {{ $user->mobile_no }} </span></a></li>
                                <li><a href="#">Gender <span class="pull-right"> @if($user->gender == 1){{ "Male" }} @else {{ 'Female' }} @endif </span></a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop