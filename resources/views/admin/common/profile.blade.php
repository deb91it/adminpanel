@extends('admin.layout.master')
@section('title') Profile Data @stop
@section('page_name')
    Profile Data
    <small>{!! $types !!} Profile</small>
@stop

@section ('breadcrumbs')
    <li> <a href="{!! route('admin.dashboard') !!}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
    <li class="active"> {!! link_to_route('admin.merchants', 'Merchant Management') !!} </li>
@stop

@section('content')
	<div class="m-portlet m-portlet--full-height">
		<div class="m-portlet__head">
			<div class="m-portlet__head-caption">
				<div class="m-portlet__head-title">
					<h3 class="m-portlet__head-text" style="text-transform:capitalize;">
						{!! $types !!} Profile
					</h3>
				</div>
			</div>
			<div class="m-portlet__head-tools">
				<ul class="m-portlet__nav">
					<li class="m-portlet__nav-item m-dropdown m-dropdown--inline m-dropdown--arrow m-dropdown--align-right m-dropdown--align-push" data-dropdown-toggle="hover" aria-expanded="true">
{{--						<a href="{!! route('admin.member.profile', [$member->member_id, 'has_downlaod' => true]) !!}" class="m-portlet__nav-link m-dropdown__toggle btn btn--sm m-btn--pill btn-secondary m-btn m-btn--label-primary">--}}
{{--							<span><i class="fa fa-file-pdf-o"></i>--}}
{{--								<span>Pdf</span>--}}
{{--							</span>--}}
{{--						</a>--}}
					</li>
				</ul>
			</div>
		</div>
		<div class="m-portlet__body">
			<div class="row">
				<div class="col-lg-6 col-lg-offset-3">
					<div class="m-card-profile">
						<div class="m-card-profile__title m--hide">
							Your Profile
						</div>
						<div class="m-card-profile__pic">
							<div class="m-card-profile__pic-wrapper">
								@if($member->profile_pic != '')
									<?php
										$filepath = public_path('/resources/profile_pic/').$member->profile_pic;
									?>
						            @if (file_exists($filepath))
						                <a href="{{ route('admin.member.profile.image.download', array($member->member_id)) }}"><img src="{{ url('/resources/profile_pic') }}/{!! $member->profile_pic !!}" alt=""/></a>
						            @else
						            	<img src="{{ url('backend/dist/img/avatar.png') }}">
						            @endif
								@else
									<img src="{{ url('backend/dist/img/avatar.png') }}">
								@endif

							</div>
						</div>
						<div class="m-card-profile__details">
							<span class="m-card-profile__name">
								{!! $member->first_name !!} {!! $member->last_name !!}
							</span>
						</div>
					</div>
					<div class="m-portlet__body-separator"></div>
					<div class="m-widget1 m-widget1--paddingless">
						<div class="m-widget1__item">
							<div class="row m-row--no-padding align-items-center">
								<div class="col">
									<h3 class="m-widget1__title">
										Email
									</h3>
								</div>
								<div class="col m--align-right">
									<span class="m-widget1__number m--font-brand">
										{!! $member->email !!}
									</span>
								</div>
							</div>
						</div>
						<div class="m-widget1__item">
							<div class="row m-row--no-padding align-items-center">
								<div class="col">
									<h3 class="m-widget1__title">
										Phone
									</h3>
								</div>
								<div class="col m--align-right">
									<span class="m-widget1__number m--font-brand">
										{!! $member->mobile_no !!}
									</span>
								</div>
							</div>
						</div>
						@if($types == 'merchant')
						<div class="m-widget1__item">
							<div class="row m-row--no-padding align-items-center">
								<div class="col">
									<h3 class="m-widget1__title">
										Merchant
									</h3>
								</div>
								<div class="col m--align-right">
									<span class="m-widget1__number m--font-brand">
										{!! $member->first_name !!} {!! $member->last_name !!}
									</span>
								</div>
							</div>
						</div>
						<div class="m-widget1__item">
							<div class="row m-row--no-padding align-items-center">
								<div class="col">
									<h3 class="m-widget1__title">
										{{strtoupper(str_replace('_', ' ', $member->id_type))}} NUMBER
									</h3>
								</div>
								<div class="col m--align-right">
									<span class="m-widget1__number m--font-brand">
										{!! $member->id_number !!}
									</span>
								</div>
							</div>
						</div>
						@if(!empty($member->front_image_url))
						<div class="m-widget1__item">
							<div class="row m-row--no-padding align-items-center">
								<div class="col">
									<h3 class="m-widget1__title">
										{{strtoupper(str_replace('_', ' ', $member->id_type))}} IMAGE
									</h3>
								</div>
								<div class="col m--align-right">
									<span class="m-widget1__number m--font-brand">
										<a href="{{route('admin.merchant.id.image.download', array($member->member_id))}}"><img src="{{asset($member->front_image_url)}}" style="height: 80px;width: 80px;" class="img-thumbnail img-responsive"></a>
									</span>
								</div>
							</div>
						</div>
						@endif
						<div class="m-widget1__item">
							<div class="row m-row--no-padding align-items-center">
								<div class="col">
									<h3 class="m-widget1__title">
										Business Name
									</h3>
								</div>
								<div class="col m--align-right">
								<span class="m-widget1__number m--font-brand">
									{{$member->business_name}}
								</span>
								</div>
							</div>
						</div>
						<div class="m-widget1__item">
							<div class="row m-row--no-padding align-items-center">
								<div class="col">
									<h3 class="m-widget1__title">
										Media Link
									</h3>
								</div>
								<div class="col m--align-right">
								<span class="m-widget1__number m--font-brand">
									{{$member->media_link}}
								</span>
								</div>
							</div>
						</div>
						<div class="m-widget1__item">
							<div class="row m-row--no-padding align-items-center">
								<div class="col">
									<h3 class="m-widget1__title">
										Address
									</h3>
								</div>
								<div class="col m--align-right">
								<span class="m-widget1__number m--font-brand">
									{{$member->address}}
								</span>
								</div>
							</div>
						</div>
							@if(!empty($member->operator_name))
							<div class="m-widget1__item">
								<div class="row m-row--no-padding align-items-center">
									<div class="col">
										<h3 class="m-widget1__title">
											Operator Name
										</h3>
									</div>
									<div class="col m--align-right">
									<span class="m-widget1__number m--font-brand">
										{{$member->operator_name}}
									</span>
									</div>
								</div>
							</div>
							@endif
							@if(!empty($member->operator_number))
							<div class="m-widget1__item">
								<div class="row m-row--no-padding align-items-center">
									<div class="col">
										<h3 class="m-widget1__title">
											Operator Number
										</h3>
									</div>
									<div class="col m--align-right">
									<span class="m-widget1__number m--font-brand">
										{{$member->operator_number}}
									</span>
									</div>
								</div>
							</div>
							@endif
							@if(!empty($member->operator_email))
							<div class="m-widget1__item">
								<div class="row m-row--no-padding align-items-center">
									<div class="col">
										<h3 class="m-widget1__title">
											Operator Email
										</h3>
									</div>
									<div class="col m--align-right">
									<span class="m-widget1__number m--font-brand">
										{{$member->operator_email}}
									</span>
									</div>
								</div>
							</div>
							@endif
							@if(!empty($member->payment_type))
							<div class="m-widget1__item">
								<div class="row m-row--no-padding align-items-center">
									<div class="col">
										<h3 class="m-widget1__title">
											Payment Type
										</h3>
									</div>
									<div class="col m--align-right">
									<span class="m-widget1__number m--font-brand">
										<?php
											$paymentType = '';
											if($member->payment_type == 1)
											{
												$paymentType = 'Mobile';
											}elseif ($member->payment_type == 3)
											{
												$paymentType = 'Both';
											}elseif ($member->payment_type == 4)
											{
												$paymentType = 'Cash';
											}else{
												$paymentType = 'Bank';
											}
											echo $paymentType
										?>
									</span>
									</div>
								</div>
							</div>
							@endif
							@if(!empty($member->payment_type == 1 || $member->payment_type == 3))
							<div class="m-widget1__item">
								<div class="row m-row--no-padding align-items-center">
									<div class="col">
										<h3 class="m-widget1__title">
											Wallet Provider
										</h3>
									</div>
									<div class="col m--align-right">
									<span class="m-widget1__number m--font-brand">
										{{$member->payment_details[0]->payment_name}}
									</span>
									</div>
								</div>
							</div>
							@endif
							@if(!empty($member->payment_type == 1 || $member->payment_type == 3))
							<div class="m-widget1__item">
								<div class="row m-row--no-padding align-items-center">
									<div class="col">
										<h3 class="m-widget1__title">
											Account Holder Name
										</h3>
									</div>
									<div class="col m--align-right">
									<span class="m-widget1__number m--font-brand">
										{{$member->payment_details[0]->account_holder_name}}
									</span>
									</div>
								</div>
							</div>
							@endif
							@if(!empty($member->payment_type == 1 || $member->payment_type == 3))
							<div class="m-widget1__item">
								<div class="row m-row--no-padding align-items-center">
									<div class="col">
										<h3 class="m-widget1__title">
											Account Number
										</h3>
									</div>
									<div class="col m--align-right">
									<span class="m-widget1__number m--font-brand">
										{{$member->payment_details[0]->account_number}}
									</span>
									</div>
								</div>
							</div>
							@endif
							<?php
							$indexKey = 0;
							if ($member->payment_type == 3)
							{
								$indexKey = 1;
							}
							?>
							@if($member->payment_type == 2 || $member->payment_type == 3 && array_key_exists($indexKey, $member->payment_details))
							<div class="m-widget1__item">
								<div class="row m-row--no-padding align-items-center">
									<div class="col">
										<h3 class="m-widget1__title">
											Bank Name
										</h3>
									</div>
									<div class="col m--align-right">
									<span class="m-widget1__number m--font-brand">
										{{$member->payment_details[$indexKey]->payment_name}}
									</span>
									</div>
								</div>
							</div>
							@endif
							@if($member->payment_type == 2 || $member->payment_type == 3 && array_key_exists($indexKey, $member->payment_details))
							<div class="m-widget1__item">
								<div class="row m-row--no-padding align-items-center">
									<div class="col">
										<h3 class="m-widget1__title">
											Bank Account Holder Name
										</h3>
									</div>
									<div class="col m--align-right">
									<span class="m-widget1__number m--font-brand">
										{{$member->payment_details[$indexKey]->account_holder_name}}
									</span>
									</div>
								</div>
							</div>
							@endif

							@if($member->payment_type == 2 || $member->payment_type == 3 && array_key_exists($indexKey, $member->payment_details))
							<div class="m-widget1__item">
								<div class="row m-row--no-padding align-items-center">
									<div class="col">
										<h3 class="m-widget1__title">
											Bank Account Number
										</h3>
									</div>
									<div class="col m--align-right">
									<span class="m-widget1__number m--font-brand">
										{{$member->payment_details[$indexKey]->account_number}}
									</span>
									</div>
								</div>
							</div>
							@endif

							@if($member->payment_type == 2 || $member->payment_type == 3 && array_key_exists($indexKey, $member->payment_details))
							<div class="m-widget1__item">
								<div class="row m-row--no-padding align-items-center">
									<div class="col">
										<h3 class="m-widget1__title">
											Bank Account Type
										</h3>
									</div>
									<div class="col m--align-right">
									<span class="m-widget1__number m--font-brand">
										{{$member->payment_details[$indexKey]->bank_account_type}}
									</span>
									</div>
								</div>
							</div>
							@endif

							@if($member->payment_type == 2 || $member->payment_type == 3 && array_key_exists($indexKey, $member->payment_details))
							<div class="m-widget1__item">
								<div class="row m-row--no-padding align-items-center">
									<div class="col">
										<h3 class="m-widget1__title">
											Bank Brunch Name
										</h3>
									</div>
									<div class="col m--align-right">
									<span class="m-widget1__number m--font-brand">
										{{$member->payment_details[$indexKey]->bank_brunch_name}}
									</span>
									</div>
								</div>
							</div>
							@endif
							@if($member->payment_type == 2 || $member->payment_type == 3 && array_key_exists($indexKey, $member->payment_details))
							<div class="m-widget1__item">
								<div class="row m-row--no-padding align-items-center">
									<div class="col">
										<h3 class="m-widget1__title">
											Bank Routing Number
										</h3>
									</div>
									<div class="col m--align-right">
									<span class="m-widget1__number m--font-brand">
										{{$member->payment_details[$indexKey]->bank_routing_number}}
									</span>
									</div>
								</div>
							</div>
							@endif
						@endif
					</div>
				</div>
			</div>
		</div>
		<div class="m-portlet__foot">
            <div class="row align-items-center">
            </div>
        </div>
	</div>
	<!--end:: Widgets/Company Summary-->
	<style type="text/css">
		.m-widget13 .m-widget13__item .m-widget13__desc {
		    width: 20%!important;
		}
	</style>
@stop