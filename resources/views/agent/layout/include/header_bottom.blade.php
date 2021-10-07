<div class="m-header__bottom">
    <div class="m-container m-container--responsive m-container--xxl m-container--full-height m-page__container">
        <div class="m-stack m-stack--ver m-stack--desktop">
            <!-- begin::Horizontal Menu -->
            <div class="m-stack__item m-stack__item--middle m-stack__item--fluid">
                <button class="m-aside-header-menu-mobile-close m-aside-header-menu-mobile-close--skin-light" id="m_aside_header_menu_mobile_close_btn"><i class="la la-close"></i>
                </button>
                <div id="m_header_menu" class="m-header-menu m-aside-header-menu-mobile m-aside-header-menu-mobile--offcanvas  m-header-menu--skin-dark m-header-menu--submenu-skin-light m-aside-header-menu-mobile--skin-light m-aside-header-menu-mobile--submenu-skin-light "  >
                    <ul class="m-menu__nav  m-menu__nav--submenu-arrow">
                        <li class="m-menu__item  {{ setActive('dashboard') }}"  aria-haspopup="true">
                            <a  href="{{ url('/') }}" class="m-menu__link "><span class="m-menu__item-here"></span><span class="m-menu__link-text">Dashboard</span></a>
                        </li>

                        @if(hasAccessAbility('view_employee', $roles))
                            <li class="m-menu__item  m-menu__item--submenu m-menu__item--rel {{ setActive('employee*') }}" data-menu-submenu-toggle="click" aria-haspopup="true">
                                <a  href="#" class="m-menu__link m-menu__toggle"><span class="m-menu__item-here"></span>
                                    <span class="m-menu__link-text"> Employee </span><i class="m-menu__hor-arrow la la-angle-down"></i><i class="m-menu__ver-arrow la la-angle-right"></i>
                                </a>
                                <div class="m-menu__submenu m-menu__submenu--classic m-menu__submenu--left"><span class="m-menu__arrow m-menu__arrow--adjust"></span>
                                    <ul class="m-menu__subnav">
                                        @if(hasAccessAbility('view_employee', $roles))
                                            <li class="m-menu__item {{ setActive('employee') }}" data-redirect="true" aria-haspopup="true">
                                                <a  href="{!! route('agents') !!}" class="m-menu__link ">
                                                    <i class="m-menu__link-icon fa fa-list-alt"></i>
                                                    <span class="m-menu__link-text"> Employee List </span>
                                                </a>
                                            </li>
                                        @endif

                                        @if(hasAccessAbility('view_employee', $roles))
                                            <li class="m-menu__item {{ setActive('employee/organogram') }}" data-redirect="true" aria-haspopup="true">
                                                <a  href="{!! route('organograms') !!}" class="m-menu__link ">
                                                    <i class="m-menu__link-icon fa fa-vcard-o"></i>
                                                    <span class="m-menu__link-text"> Organogram (Alpha)</span>
                                                </a>
                                            </li>
                                        @endif
                                    </ul>
                                </div>
                            </li>
                        @endif

                        @if(hasAccessAbility('view_attendance', $roles) || hasAccessAbility('view_attendance_policy', $roles) || hasAccessAbility('view_holiday_setup', $roles))
                            <li class="m-menu__item  m-menu__item--submenu m-menu__item--rel {{ setActive('*report*') }} {{ setActive('holiday-settings*') }}"  data-menu-submenu-toggle="click" aria-haspopup="true">
                                <a  href="#" class="m-menu__link m-menu__toggle"><span class="m-menu__item-here"></span>
                                    <span class="m-menu__link-text"> Attendance </span><i class="m-menu__hor-arrow la la-angle-down"></i><i class="m-menu__ver-arrow la la-angle-right"></i>
                                </a>
                                <div class="m-menu__submenu m-menu__submenu--classic m-menu__submenu--left"><span class="m-menu__arrow m-menu__arrow--adjust"></span>
                                    <ul class="m-menu__subnav">
                                        @if(hasAccessAbility('view_attendance', $roles))
                                            <li class="m-menu__item {{ setActive('attendance-list') }}"  data-redirect="true" aria-haspopup="true">
                                                <a  href="{!! route('attendance.list') !!}" class="m-menu__link ">
                                                    <i class="m-menu__link-icon fa fa-list-alt"></i>
                                                    <span class="m-menu__link-text"> Attendance List </span>
                                                </a>
                                            </li>
                                        @endif

                                        @if(hasAccessAbility('view_attendance_policy', $roles))
                                            <li class="m-menu__item {{ setActive('attendance-policy') }}"  data-redirect="true" aria-haspopup="true">
                                                <a  href="{!! route('attendance.policy') !!}" class="m-menu__link ">
                                                    <i class="m-menu__link-icon flaticon-truck"></i>
                                                    <span class="m-menu__link-text"> Attendance Policy </span>
                                                </a>
                                            </li>
                                        @endif

                                        @if(hasAccessAbility('view_holiday_setup', $roles))
                                            <li class="m-menu__item {{ setActive('holiday-settings*') }}"  data-redirect="true" aria-haspopup="true">
                                                <a  href="{!! route('agent.holiday_settings') !!}" class="m-menu__link ">
                                                    <i class="m-menu__link-icon fa fa-cog"></i>
                                                    <span class="m-menu__link-text"> Holiday Settings </span>
                                                </a>
                                            </li>
                                        @endif
                                    </ul>
                                </div>
                            </li>
                        @endif

                        @if(hasAccessAbility('view_outlet_cat', $roles) || hasAccessAbility('view_outlet', $roles))
                            <li class="m-menu__item  m-menu__item--submenu m-menu__item--rel {{ setActive('outlet*') }} "  data-menu-submenu-toggle="click" aria-haspopup="true">
                                <a  href="#" class="m-menu__link m-menu__toggle"><span class="m-menu__item-here"></span>
                                    <span class="m-menu__link-text"> Outlet </span><i class="m-menu__hor-arrow la la-angle-down"></i><i class="m-menu__ver-arrow la la-angle-right"></i>
                                </a>
                                <div class="m-menu__submenu m-menu__submenu--classic m-menu__submenu--left"><span class="m-menu__arrow m-menu__arrow--adjust"></span>
                                    <ul class="m-menu__subnav">
                                        @if(hasAccessAbility('view_outlet_cat', $roles))
                                            <li class="m-menu__item {{ setActive('outlet-category') }}"  data-redirect="true" aria-haspopup="true">
                                                <a  href="{!! route('outlet.category') !!}" class="m-menu__link ">
                                                    <i class="m-menu__link-icon flaticon-truck"></i>
                                                    <span class="m-menu__link-text"> Outlet category </span>
                                                </a>
                                            </li>
                                        @endif

                                        @if(hasAccessAbility('view_outlet', $roles))
                                            <li class="m-menu__item {{ setActive('outlets') }}"  data-redirect="true" aria-haspopup="true">
                                                <a  href="{!! route('agent.outlet') !!}" class="m-menu__link ">
                                                    <i class="m-menu__link-icon fa fa-list-alt"></i>
                                                    <span class="m-menu__link-text"> Outlet list </span>
                                                </a>
                                            </li>
                                        @endif
                                    </ul>
                                </div>
                            </li>
                        @endif

                        @if(hasAccessAbility('view_order', $roles))
                            <li class="m-menu__item {{ setActive('order*') }}"  aria-haspopup="true">
                                <a  href="{!! route('order.list') !!}" class="m-menu__link "><span class="m-menu__item-here"></span><span class="m-menu__link-text">Order</span></a>
                            </li>
                        @endif

                        @if(hasAccessAbility('view_order', $roles))
                            <li class="m-menu__item  m-menu__item--submenu m-menu__item--rel {{ setActive('zones*') }} "  data-menu-submenu-toggle="click" aria-haspopup="true">
                                <a  href="#" class="m-menu__link m-menu__toggle"><span class="m-menu__item-here"></span>
                                    <span class="m-menu__link-text"> Settings </span><i class="m-menu__hor-arrow la la-angle-down"></i><i class="m-menu__ver-arrow la la-angle-right"></i>
                                </a>
                                <div class="m-menu__submenu m-menu__submenu--classic m-menu__submenu--left"><span class="m-menu__arrow m-menu__arrow--adjust"></span>
                                    <ul class="m-menu__subnav">
                                        @if(hasAccessAbility('view_order', $roles))
                                            <li class="m-menu__item {{ setActive('zones') }}"  data-redirect="true" aria-haspopup="true">
                                                <a  href="{!! route('zones.search') !!}" class="m-menu__link ">
                                                    <i class="m-menu__link-icon fa fa-map-marker"></i>
                                                    <span class="m-menu__link-text"> Zones </span>
                                                </a>
                                            </li>
                                        @endif
                                    </ul>
                                </div>
                            </li>
                        @endif
                    </ul>
                </div>
            </div>
            <!-- end::Horizontal Menu -->
        </div>
    </div>
</div>