@extends('agent.layout.master')
@section('title') User Profile @stop


@section('page_name')
    &nbsp;
    Company profile
    <small></small>
@stop

@section ('breadcrumbs')
    <li> <a href="{!! route('agent.dashboard') !!}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
    <li class="active"> {!! link_to_route('agent.user_profile', 'User profile') !!} </li>
@stop

@section('content')
<div class="row">
    <div class="col-lg-3">
        <div class="m-portlet m-portlet--full-height ">
            <div class="m-portlet__body">
                <div class="m-card-profile">
                    <div class="m-card-profile__title m--hide">
                        Company Profile
                    </div>
                    <div class="m-card-profile__pic">
                        <div class="m-card-profile__pic-wrapper">

                            @if($info->logo != '')
                                @if(file_exists(public_path().'/resources/company_logo/'.$info->logo))
                                    <img src="{{ url('/resources/company_logo') }}/{{ $info->logo }}" alt="User Image">
                                @endif
                            @else
                                {!! 'No image exist' !!}
                            @endif

                        </div>
                    </div>

                    <div class="m-card-profile__details">
                        <span class="m-card-profile__name">
                            {{ $info->name }}
                        </span>
                        <a href="" class="m-card-profile__email m-link">
                            {{ $info->moto }}
                        </a>
                    </div>
                </div>
                <ul class="m-nav m-nav--hover-bg m-portlet-fit--sides">
                    <li class="m-nav__separator m-nav__separator--fit"></li>
                    <li class="m-nav__section m--hide">
                        <span class="m-nav__section-text">
                            Section
                        </span>
                    </li>
                    <li class="m-nav__item">
                        {{--<a href="#" class="m-nav__link">
                            <i class="m-nav__link-icon flaticon-profile-1"></i>
                            <span class="m-nav__link-title">
                                <span class="m-nav__link-wrap">
                                    <span class="m-nav__link-text">
                                        Company Profile
                                    </span>
                                    <span class="m-nav__link-badge">
                                    </span>
                                </span>
                            </span>
                        </a>--}}
                    </li>
                </ul>
                <div class="m-portlet__body-separator"></div>
            </div>
        </div>
    </div>
    <div class="col-lg-9">


        <div class="m-portlet m-portlet--full-height">
            <div class="m-portlet__head">
                <div class="m-portlet__head-caption">
                    <div class="m-portlet__head-title">
                        <h3 class="m-portlet__head-text">
                            Company Information
                        </h3>
                    </div>
                </div>
            </div>
            <div class="m-portlet__body">
                <div class="m-widget13">
                    <div class="m-widget13__item">
                        <span class="m-widget13__desc m--align-right">
                            Company name:
                        </span>
                        <span class="m-widget13__text m-widget13__text-bolder">
                            {{ $info->name }}
                        </span>
                    </div>
                    <div class="m-widget13__item">
                        <span class="m-widget13__desc m--align-right">
                            Moto:
                        </span>
                        <span class="m-widget13__text m-widget13__text-bolder">
                            {{ $info->moto }}
                        </span>
                    </div>
                    <div class="m-widget13__item">
                        <span class="m-widget13__desc m--align-right">
                            Group name:
                        </span>
                        <span class="m-widget13__text m-widget13__text-bolder">
                           {{ $info->group_name }}
                        </span>
                    </div>
                    <div class="m-widget13__item">
                        <span class="m-widget13__desc m--align-right">
                            Admin:
                        </span>
                        <span class="m-widget13__text m-widget13__text-bolder">
                           {{ $info->agent }}
                        </span>
                    </div>
                    <div class="m-widget13__item">
                        <span class="m-widget13__desc m--align-right">
                            Address:
                        </span>
                        <span class="m-widget13__text m-widget13__text-bolder">
                            {{ $info->address }}
                        </span>
                    </div>
                    <div class="m-widget13__item">
                        <span class="m-widget13__desc m--align-right">
                            Created at:
                        </span>
                        <span class="m-widget13__text m-widget13__text-bolder">
                            {{ $info->created_at }}
                        </span>
                    </div>
                    <div class="m-widget13__item">
                        <span class="m-widget13__desc m--align-right">
                            Last Update:
                        </span>
                        <span class="m-widget13__text m-widget13__text-bolder">
                            {{ $info->updated_at }}
                        </span>
                    </div>
                    <div class="m-widget13__action m--align-right">
                        {{--<a href="{{ url('/edit-profile') }}">--}}
                            {{--<button type="button" class="btn m-btn--pill m-btn--air btn-brand">--}}
                                {{--<i class="fa fa-edit" aria-hidden="true"></i><span> Edit</span>--}}
                            {{--</button>--}}
                        {{--</a>--}}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@stop