<div id="m_ver_menu" class="m-aside-menu  m-aside-menu--skin-dark m-aside-menu--submenu-skin-dark " data-menu-vertical="true" data-menu-scrollable="false" data-menu-dropdown-timeout="500" >
	<ul class="m-menu__nav  m-menu__nav--dropdown-submenu-arrow ">
		<li class="m-menu__item {{ setActive('agent') }}" aria-haspopup="true" >
			<a  href="{{ url('/') }}" class="m-menu__link ">
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

		<li class="m-menu__item {{ setActive('agent') }}" aria-haspopup="true">
			<a  href="{!! route('agents') !!}" class="m-menu__link">
				<i class="m-menu__link-icon fa fa-diamond"></i>
				<span class="m-menu__link-text">
					Agent
				</span>
			</a>
		</li>

		<li class="m-menu__item {{ setActive('agent/merchant') }}" aria-haspopup="true">
			<a  href="{!! route('agent.merchants') !!}" class="m-menu__link">
				<i class="m-menu__link-icon fa fa-male"></i>
				<span class="m-menu__link-text">
					Merchant
				</span>
			</a>
		</li>

		<li class="m-menu__item {{ setActive('agent/driver') }}" aria-haspopup="true">
			<a  href="{!! route('agent.drivers') !!}" class="m-menu__link">
				<i class="m-menu__link-icon fa fa-wheelchair"></i>
				<span class="m-menu__link-text">
					Driver
				</span>
			</a>
		</li>

		<li class="m-menu__item {{ setActive('agent/employee') }}" aria-haspopup="true">
			<a  href="{!! route('agent.employee') !!}" class="m-menu__link">
				<i class="m-menu__link-icon fa fa-male"></i>
				<span class="m-menu__link-text">
					Employee
				</span>
			</a>
		</li>

		<li class="m-menu__item {{ setActive('agent/vehicle') }}" aria-haspopup="true">
			<a  href="{!! route('agent.vehicles') !!}" class="m-menu__link">
				<i class="m-menu__link-icon fa fa-truck"></i>
				<span class="m-menu__link-text">
					Vehicle
				</span>
			</a>
		</li>
	</ul>
</div>
<!-- END: Aside Menu -->