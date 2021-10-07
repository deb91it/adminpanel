<html><head><link href="{!! asset('backend/ezzyr_assets/demo/default/base/profile_pdf.css') !!}" rel="stylesheet" type="text/css" />
</head><body>
	<div class="wrapper">
		<section>
			<div class="profile_type">
				{!! $types !!} Profile
			</div>
		</section>
		<section>
			<div class="profile_pic">
				<table>
					<tr>
						<td>
							@if($member->profile_pic != '')
								<?php 
									$filepath = public_path('/resources/profile_pic/').$member->profile_pic; 
								?>
					            @if (file_exists($filepath)) 
					                <img src="{{ url('/resources/profile_pic') }}/{!! $member->profile_pic !!}" alt="Profile Image" width="200" />
					            @else
					            	<img src="{{ url('backend/dist/img/avatar.png') }}">
					            @endif
							@else
								<img src="{{ url('backend/dist/img/avatar.png') }}">
							@endif
						</td>
						<td style="vertical-align: top;">
							<table style="margin-left: 30px;">
								<tr>
									<td width="100"><b>Name</b></td>
									<td width="50">:</td>
									<td><b>{!! $member->first_name !!} {!! $member->last_name !!}</b></td>
								</tr>
								<tr>
									<td width="100">Email</td>
									<td width="50">:</td>
									<td>{!! $member->email !!}</td>
								</tr>
								<tr>
									<td width="100">Phone</td>
									<td width="50">:</td>
									<td>{!! $member->mobile_no !!}</td>
								</tr>
							</table>
						</td>
					</tr>
				</table>
			</div>
		</section>
		<div class="table_wrapper">
			<br>

		</div>


	</div>
	
	<!--end:: Widgets/Company Summary-->
</body></html>
	







	
