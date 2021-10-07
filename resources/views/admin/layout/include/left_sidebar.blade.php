<div id="m_ver_menu" class="m-aside-menu  m-aside-menu--skin-dark m-aside-menu--submenu-skin-dark " data-menu-vertical="true" data-menu-scrollable="false" data-menu-dropdown-timeout="500" >
	<ul class="m-menu__nav  m-menu__nav--dropdown-submenu-arrow ">
		<li class="m-menu__item {{ setActive('admin') }}" aria-haspopup="true" >
			<a  href="{{ url('/admin') }}" class="m-menu__link ">
				<i class="m-menu__link-icon flaticon-line-graph"></i>
				<span class="m-menu__link-title">
					<span class="m-menu__link-wrap">
						<span class="m-menu__link-text">
							Dashboard
						</span>
					</span>
				</span>
			</a>
		</li>
		
		@if(hasAccessAbility('view_role', $roles))
			<li class="m-menu__item  m-menu__item--submenu {{ setActive('admin/users*') }} {{ setActive('admin/permission-group*') }} {{ setActive('admin/permission*') }} {{ setActive('admin/role*') }}" aria-haspopup="true"  data-menu-submenu-toggle="hover">
				<a  href="#" class="m-menu__link m-menu__toggle">
					<i class="m-menu__link-icon fa fa-id-badge "></i>
					<span class="m-menu__link-text">
						Role Management
					</span>
					<i class="m-menu__ver-arrow la la-angle-right"></i>
				</a>
				<div class="m-menu__submenu">
					<span class="m-menu__arrow"></span>
					<ul class="m-menu__subnav">
						@if(hasAccessAbility('view_role', $roles))
							<li class="m-menu__item {{ setActive('admin/role') }}" aria-haspopup="true" >
								<a  href="{{ url('/admin/role') }}" class="m-menu__link ">
									<i class="m-menu__link-bullet m-menu__link-bullet--dot">
										<span></span>
									</i>
									<span class="m-menu__link-text">
										Role
									</span>
								</a>
							</li>
						@endif

						@if(hasAccessAbility('view_permission_group', $roles))
							<li class="m-menu__item {{ setActive('admin/permission-group*') }}" aria-haspopup="true" >
								<a  href="{{ url('/admin/permission-group') }}" class="m-menu__link">
									<i class="m-menu__link-bullet m-menu__link-bullet--dot">
										<span></span>
									</i>
									<span class="m-menu__link-text">
										Permission Group
									</span>
								</a>
							</li>
						@endif

						@if(hasAccessAbility('view_permission', $roles))
							<li class="m-menu__item {{ setActive('admin/permission') }} {{ setActive('admin/permission/create') }} {{ setActive('admin/permission/') }}" aria-haspopup="true" >
								<a  href="{{ url('/admin/permission') }}" class="m-menu__link ">
									<i class="m-menu__link-bullet m-menu__link-bullet--dot">
										<span></span>
									</i>
									<span class="m-menu__link-text">
										Permission
									</span>
								</a>
							</li>
						@endif
					</ul>
				</div>
			</li>
		@endif

		@if(hasAccessAbility('view_admin_user', $roles))
			<li class="m-menu__item {{ setActive('admin/admin-users*') }}" aria-haspopup="true">
				<a  href="{!! route('admin.admin-users') !!}" class="m-menu__link">
					<i class="m-menu__link-icon fa fa-users"></i>
					<span class="m-menu__link-text">
						Admin User
					</span>
				</a>
			</li>
		@endif

		@if(hasAccessAbility('view_hub', $roles))
			<li class="m-menu__item {{ setActive('admin/hub') }} {{ setActive('admin/hub/new') }}" aria-haspopup="true">
				<a  href="{!! route('admin.hubs') !!}" class="m-menu__link">
					<i class="m-menu__link-icon fa fa-home"></i>
					<span class="m-menu__link-text">
						Hubs
					</span>
				</a>
			</li>
		@endif

		@if(hasAccessAbility('view_agent', $roles))
			<li class="m-menu__item {{ setActive('admin/agent') }} {{ setActive('admin/agent/new') }}" aria-haspopup="true">
				<a  href="{!! route('admin.agents') !!}" class="m-menu__link">
					<i class="m-menu__link-icon fa fa-first-order"></i>
					<span class="m-menu__link-text">
						Riders
					</span>
				</a>
			</li>
		@endif

		@if(hasAccessAbility('view_merchant', $roles))
			<li class="m-menu__item {{ setActive('admin/merchant') }} {{ setActive('admin/merchant/new') }}" aria-haspopup="true">
				<a  href="{!! route('admin.merchants') !!}" class="m-menu__link">
					<i class="m-menu__link-icon fa fa-blind"></i>
					<span class="m-menu__link-text">
						Merchant
					</span>
				</a>
			</li>
		@endif

{{--		@if(hasAccessAbility('view_company', $roles))--}}
{{--			<li class="m-menu__item {{ setActive('admin/company') }} {{ setActive('admin/company/new') }}" aria-haspopup="true">--}}
{{--				<a  href="{!! route('admin.company') !!}" class="m-menu__link">--}}
{{--					<i class="m-menu__link-icon fa fa-industry"></i>--}}
{{--					<span class="m-menu__link-text">--}}
{{--						Company--}}
{{--					</span>--}}
{{--				</a>--}}
{{--			</li>--}}
{{--		@endif--}}
		
		@if(hasAccessAbility('view_product', $roles))
			<li class="m-menu__item {{ setActive('admin/product') }} {{ setActive('admin/product/new') }}" aria-haspopup="true">
				<a  href="{!! route('admin.products') !!}" class="m-menu__link">
					<i class="m-menu__link-icon fa fa-cubes"></i>
					<span class="m-menu__link-text">
						Products
					</span>
				</a>
			</li>
		@endif

		@if(hasAccessAbility('view_store', $roles))
			<li class="m-menu__item {{ setActive('admin/store') }} {{ setActive('admin/store/new') }}" aria-haspopup="true">
				<a  href="{!! route('admin.stores') !!}" class="m-menu__link">
					<i class="m-menu__link-icon fa fa-building-o"></i>
					<span class="m-menu__link-text">
						Stores
					</span>
				</a>
			</li>
		@endif

{{--		@if(hasAccessAbility('view_delivery', $roles))--}}
{{--			<li class="m-menu__item {{ setActive('admin/collected') }}" aria-haspopup="true">--}}
{{--				<a  href="{!! route('admin.collected') !!}" class="m-menu__link">--}}
{{--					<i class="m-menu__link-icon fa fa-linode"></i>--}}
{{--					<span class="m-menu__link-text">--}}
{{--						Collection--}}
{{--					</span>--}}
{{--				</a>--}}
{{--			</li>--}}
{{--		@endif--}}

		@if(hasAccessAbility('view_delivery', $roles))
			<li class="m-menu__item {{ setActive('admin/delivery') }} {{ setActive('admin/delivery/new') }}" aria-haspopup="true">
				<a  href="{!! route('admin.deliverys') !!}" class="m-menu__link">
					<i class="m-menu__link-icon fa fa-ship"></i>
					<span class="m-menu__link-text">
						Deliveries
					</span>
				</a>
			</li>
		@endif

		@if(hasAccessAbility('view_amendment_hub', $roles))
			<li class="m-menu__item {{ setActive('admin/amendment-delivery') }}" aria-haspopup="true">
				<a  href="{!! route('admin.amendment.delivery') !!}" class="m-menu__link">
					<i class="m-menu__link-icon fa fa-universal-access"></i>
					<span class="m-menu__link-text">
						Amendment Deliveries
					</span>
				</a>
			</li>
		@endif

		@if(hasAccessAbility('view_invoice', $roles))
			<li class="m-menu__item  m-menu__item--submenu {{ setActive('admin/invoice*') }} {{ setActive('admin/unpaid-invoice*') }}" aria-haspopup="true"  data-menu-submenu-toggle="hover">
				<a  href="#" class="m-menu__link m-menu__toggle">
					<i class="m-menu__link-icon fa fa-file-o "></i>
					<span class="m-menu__link-text">
						Invoices
					</span>
					<i class="m-menu__ver-arrow la la-angle-right"></i>
				</a>
				<div class="m-menu__submenu">
					<span class="m-menu__arrow"></span>
					<ul class="m-menu__subnav">

						@if(hasAccessAbility('view_invoice', $roles))
							<li class="m-menu__item {{ setActive('admin/unpaid-invoice') }} {{ setActive('admin/invoice/new') }}" aria-haspopup="true">
								<a  href="{!! route('admin.unpaid.invoices') !!}" class="m-menu__link">
									<i class="m-menu__link-bullet m-menu__link-bullet--dot">
										<span></span>
									</i>
									<span class="m-menu__link-text">
										Unpaid Invoices
									</span>
								</a>
							</li>
						@endif

						@if(hasAccessAbility('view_invoice', $roles))
							<li class="m-menu__item {{ setActive('admin/invoice') }} {{ setActive('admin/invoice/paid/invoice') }}" aria-haspopup="true" >
								<a  href="{{ route('admin.invoices') }}" class="m-menu__link ">
									<i class="m-menu__link-bullet m-menu__link-bullet--dot">
										<span></span>
									</i>
									<span class="m-menu__link-text">
										Paid Invoices
									</span>
								</a>
							</li>
						@endif
					</ul>
				</div>
			</li>
		@endif


		@if(hasAccessAbility('view_expense', $roles))
			<li class="m-menu__item  m-menu__item--submenu {{ setActive('admin/expense*') }}" aria-haspopup="true"  data-menu-submenu-toggle="hover">
				<a  href="#" class="m-menu__link m-menu__toggle">
					<i class="m-menu__link-icon fa fa-money "></i>
					<span class="m-menu__link-text">
						Expense
					</span>
					<i class="m-menu__ver-arrow la la-angle-right"></i>
				</a>
				<div class="m-menu__submenu">
					<span class="m-menu__arrow"></span>
					<ul class="m-menu__subnav">

						@if(hasAccessAbility('view_expense-category', $roles))
							<li class="m-menu__item {{ setActive('admin/expense-category') }} {{ setActive('admin/expense-category/new') }}" aria-haspopup="true">
								<a  href="{!! route('admin.expense-categorys') !!}" class="m-menu__link">
									<i class="m-menu__link-bullet m-menu__link-bullet--dot">
										<span></span>
									</i>
									<span class="m-menu__link-text">
										Category
									</span>
								</a>
							</li>
						@endif

						@if(hasAccessAbility('view_expense', $roles))
							<li class="m-menu__item {{ setActive('admin/expense') }} {{ setActive('admin/expense/new') }}" aria-haspopup="true" >
								<a  href="{{ route('admin.expenses') }}" class="m-menu__link ">
									<i class="m-menu__link-bullet m-menu__link-bullet--dot">
										<span></span>
									</i>
									<span class="m-menu__link-text">
										Voucher
									</span>
								</a>
							</li>
						@endif
					</ul>
				</div>
			</li>
		@endif

		@if(hasAccessAbility('view_income', $roles))
			<li class="m-menu__item {{ setActive('admin/income') }}" aria-haspopup="true">
				<a  href="{!! route('admin.incomes') !!}" class="m-menu__link">
					<i class="m-menu__link-icon fa fa-dollar"></i>
					<span class="m-menu__link-text">
						Incomes
					</span>
				</a>
			</li>
		@endif

		@if(hasAccessAbility('view_financial_statement', $roles))
			<li class="m-menu__item {{ setActive('admin/financial-statement') }}" aria-haspopup="true">
				<a  href="{!! route('admin.financial.statement') !!}" class="m-menu__link">
					<i class="m-menu__link-icon fa flaticon-analytics"></i>
					<span class="m-menu__link-text">
						Financial Statement
					</span>
				</a>
			</li>
		@endif

		@if(hasAccessAbility('view_settings', $roles))
			<li class="m-menu__item  m-menu__item--submenu {{ setActive('admin/system-settings*') }} {{ setActive('admin/plan*') }} {{ setActive('admin/courier-zone') }} {{ setActive('admin/category*') }} {{ setActive('admin/mail/configuration*') }} {{ setActive('admin/vehicle-model*') }} {{ setActive('admin/vehicle-registration-city*') }}
				" aria-haspopup="true"  data-menu-submenu-toggle="hover">
				<a  href="#" class="m-menu__link m-menu__toggle">
					<i class="m-menu__link-icon fa fa-cogs"></i>
					<span class="m-menu__link-text">
						Settings
					</span>
					<i class="m-menu__ver-arrow la la-angle-right"></i>
				</a>
{{--				<div class="m-menu__submenu">--}}
{{--					<span class="m-menu__arrow"></span>--}}
{{--					<ul class="m-menu__subnav">--}}
{{--						<li class="m-menu__item {{ setActive('admin/system-settings*') }}" aria-haspopup="true" >--}}
{{--							<a  href="{!! route('admin.system-settings') !!}" class="m-menu__link ">--}}
{{--								<i class="m-menu__link-bullet m-menu__link-bullet--dot">--}}
{{--									<span></span>--}}
{{--								</i>--}}
{{--								<span class="m-menu__link-text">--}}
{{--									System Settings (Dev.)--}}
{{--								</span>--}}
{{--							</a>--}}
{{--						</li>--}}
{{--					</ul>--}}
{{--				</div>--}}
				<div class="m-menu__submenu">
					<span class="m-menu__arrow"></span>
					<ul class="m-menu__subnav">
						<li class="m-menu__item {{ setActive('admin/plan*') }}" aria-haspopup="true" >
							<a  href="{!! route('admin.plans') !!}" class="m-menu__link ">
								<i class="m-menu__link-bullet m-menu__link-bullet--dot">
									<span></span>
								</i>
								<span class="m-menu__link-text">
									Plans
								</span>
							</a>
						</li>
					</ul>
				</div>
				<div class="m-menu__submenu">
					<span class="m-menu__arrow"></span>
					<ul class="m-menu__subnav">
						<li class="m-menu__item {{ setActive('admin/courier-zone*') }}" aria-haspopup="true" >
							<a  href="{!! route('admin.courier_zones') !!}" class="m-menu__link ">
								<i class="m-menu__link-bullet m-menu__link-bullet--dot">
									<span></span>
								</i>
								<span class="m-menu__link-text">
									Courier Zones
								</span>
							</a>
						</li>
					</ul>
				</div>
				<div class="m-menu__submenu">
					<span class="m-menu__arrow"></span>
					<ul class="m-menu__subnav">
						<li class="m-menu__item {{ setActive('admin/category*') }}" aria-haspopup="true" >
							<a  href="{!! route('admin.categorys') !!}" class="m-menu__link ">
								<i class="m-menu__link-bullet m-menu__link-bullet--dot">
									<span></span>
								</i>
								<span class="m-menu__link-text">
									Category
								</span>
							</a>
						</li>
					</ul>
				</div>
				<div class="m-menu__submenu">
					<span class="m-menu__arrow"></span>
					<ul class="m-menu__subnav">
						<li class="m-menu__item {{ setActive('admin/mail/configuration*') }}" aria-haspopup="true" >
							<a  href="{!! route('admin.mail.configure') !!}" class="m-menu__link ">
								<i class="m-menu__link-bullet m-menu__link-bullet--dot">
									<span></span>
								</i>
								<span class="m-menu__link-text">
									Email-Configuration
								</span>
							</a>
						</li>
					</ul>
				</div>
			</li>
		@endif
	</ul>
</div>
<!-- END: Aside Menu -->