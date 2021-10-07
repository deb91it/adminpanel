<?php
    // Auth
    Route::get('login', ['as' => 'agent.user.login', 'uses' => 'AgentAuthController@postLogin']);
    Route::get('dashboard', ['as' => 'agent.dashboard', 'middleware' => 'acl:view_agent_dash', 'uses' => 'DashboardController@index']);
    Route::get('dashboard/bar-graph', ['as' => 'agent.dashboard.barGraph', 'middleware' => 'acl:view_agent_dash', 'uses' => 'DashboardController@getDashboardBarGraphData']);

    // Agent
    Route::get('employee', ['middleware' => 'acl:view_employee', 'as' => 'agents', 'uses' => 'AgentController@index']);
    Route::get('employee_data', ['middleware' => 'acl:view_employee', 'as' => 'agent.datatable', 'uses' => 'AgentController@getDataTableReport']);
    Route::post('employee-export', ['middleware' => 'acl:view_employee', 'as' => 'agents.export', 'uses' => 'AgentController@postExportFile']);
    Route::get('employee/new', ['middleware' => 'acl:add_employee', 'as' => 'agent.new', 'uses' => 'AgentController@create']);
    Route::post('employee/store', ['middleware' => 'acl:add_employee', 'as' => 'agent.store', 'uses' => 'AgentController@store']);
    Route::get('employee/{id}/edit', ['middleware' => 'acl:edit_employee', 'as' => 'agent.edit', 'uses' => 'AgentController@edit']);
    Route::post('employee/{id}/update', ['middleware' => 'acl:edit_employee', 'as' => 'agent.update', 'uses' => 'AgentController@update']);
    Route::get('employee/{id}/delete', ['middleware' => 'acl:delete_employee', 'as' => 'agent.delete', 'uses' => 'AgentController@destroy']);
    Route::post('zone', ['as' => 'agent.employee.zone', 'uses' => 'AgentController@getZoneByCountryId']);
    Route::get('employee/organogram', ['middleware' => 'acl:view_employee', 'as' => 'organograms', 'uses' => 'AgentController@organogramData']);


    //Report
    Route::get('report/attendance-report', ['middleware' => 'acl:view_attendance', 'as' => 'agent.report.attendance-report', 'uses' => 'AttendanceReportController@index']);
    Route::post('report/attendance-report', ['middleware' => 'acl:execute_attendance', 'as' => 'agent.report.attendance-report-export', 'uses' => 'AttendanceReportController@getExportData']);
    Route::get('report/attendance-report-pdf', ['middleware' => 'acl:execute_attendance', 'as' => 'agent.report.attendance-report-pdf', 'uses' => 'AttendanceReportController@attendanceReportPdfView']);
    Route::get('report/attendance-report-pdf-download', ['middleware' => 'acl:execute_attendance', 'as' => 'agent.report.attendance-report-pdf-download', 'uses' => 'AttendanceReportController@attendanceReportPdfDownload']);

    // Profile
    Route::get('edit-profile', ['middleware' => 'acl:edit_profile', 'as' => 'agent.edit_profile', 'uses' => 'AgentController@getEditProfile']);
    Route::post('update-profile', ['middleware' => 'acl:edit_profile', 'as' => 'agent.update_profile', 'uses' => 'AgentController@postEditProfile']);
    Route::get('change-password', ['middleware' => 'acl:execute_profile', 'as' => 'agent.change_password', 'uses' => 'AgentController@changePassword']);
    Route::post('change-password', ['middleware' => 'acl:execute_profile', 'as' => 'agent.change_password', 'uses' => 'AgentController@postChangePassword']);
    Route::get('company-info', ['middleware' => 'acl:execute_profile', 'as' => 'agent.company_info', 'uses' => 'AgentController@getCompanyProfile']);

    Route::group(['middleware' => ['afmid']], function () {
        Route::get('user-profile', ['middleware' => 'acl:view_profile', 'as' => 'agent.user_profile', 'uses' => 'AgentController@getViewProfile']);
    });

    //Outlet category
    Route::get('outlet-category', ['middleware' => 'acl:view_outlet_cat', 'as' => 'outlet.category', 'uses' => 'OutletCategoryController@index']);
    Route::get('outlet-category-data', ['middleware' => 'acl:view_outlet_cat', 'as' => 'outlet.category.data', 'uses' => 'OutletCategoryController@getDataTableReport']);
    Route::get('outlet-category/create', ['middleware' => 'acl:add_outlet_cat', 'as' => 'outlet.category.create', 'uses' => 'OutletCategoryController@create']);
    Route::post('outlet-category/store', ['middleware' => 'acl:add_outlet_cat', 'as' => 'outlet.category.store', 'uses' => 'OutletCategoryController@store']);
    Route::get('outlet-category/{id}/edit/{agent_id}', ['middleware' => 'acl:edit_outlet_cat', 'as' => 'outlet.category.edit', 'uses' => 'OutletCategoryController@edit']);
    Route::post('outlet-category/update', ['middleware' => 'acl:edit_outlet_cat', 'as' => 'outlet.category.update', 'uses' => 'OutletCategoryController@update']);
    Route::get('outlet-category/{id}/delete/{agent_id}', ['middleware' => 'acl:delete_outlet_cat', 'as' => 'outlet.category.delete', 'uses' => 'OutletCategoryController@destroy']);

    //Outlet
    Route::get('outlets', ['middleware' => 'acl:view_outlet', 'as' => 'agent.outlet', 'uses' => 'OutletController@index']);
    Route::get('outlet_data', ['middleware' => 'acl:view_outlet', 'as' => 'agent.datatable.outlet', 'uses' => 'OutletController@getDataTableReport']);

    //Employee
    Route::post('get-employee-details', ['middleware' => 'acl:execute_employee', 'as' => 'agent.employee.details', 'uses' => 'AgentController@getEmployeeDetails']);
    Route::post('employee-details-update', ['middleware' => 'acl:execute_employee', 'as' => 'agent.employee.details.update', 'uses' => 'AgentController@updateEmployeeDetails']);

    //Attendance policy head
    Route::get('attendance-policy-head', ['middleware' => 'acl:view_attendance_policy', 'as' => 'attendance.policy.head', 'uses' => 'AttendancePolicyHeadController@index']);
    Route::get('attendance-policy-head-data', ['middleware' => 'acl:view_attendance_policy', 'as' => 'attendance.policy.head.data', 'uses' => 'AttendancePolicyHeadController@getDataTableReport']);
    Route::get('attendance-policy-head/create', ['middleware' => 'acl:add_attendance_policy', 'as' => 'attendance.policy.head.create', 'uses' => 'AttendancePolicyHeadController@create']);
    Route::post('attendance-policy-head/store', ['middleware' => 'acl:add_attendance_policy', 'as' => 'attendance.policy.head.store', 'uses' => 'AttendancePolicyHeadController@store']);
    Route::get('attendance-policy-head/{id}/edit/{agent_id}', ['middleware' => 'acl:edit_attendance_policy', 'as' => 'attendance.policy.head.edit', 'uses' => 'AttendancePolicyHeadController@edit']);
    Route::post('attendance-policy-head/update', ['middleware' => 'acl:edit_attendance_policy', 'as' => 'attendance.policy.head.update', 'uses' => 'AttendancePolicyHeadController@update']);
    Route::get('attendance-policy-head/{id}/delete/{agent_id}', ['middleware' => 'acl:delete_attendance_policy', 'as' => 'attendance.policy.head.delete', 'uses' => 'AttendancePolicyHeadController@destroy']);

    //Attendance policy
    Route::get('attendance-policy', ['middleware' => 'acl:view_attendance_policy', 'as' => 'attendance.policy', 'uses' => 'AttendancePolicyController@index']);
    Route::get('attendance-policy-data', ['middleware' => 'acl:view_attendance_policy', 'as' => 'attendance.policy.data', 'uses' => 'AttendancePolicyController@getDataTableReport']);
    Route::get('attendance-policy/create', ['middleware' => 'acl:add_attendance_policy', 'as' => 'attendance.policy.create', 'uses' => 'AttendancePolicyController@create']);
    Route::post('attendance-policy/store', ['middleware' => 'acl:add_attendance_policy', 'as' => 'attendance.policy.store', 'uses' => 'AttendancePolicyController@store']);
    Route::get('attendance-policy/{id}/edit/{agent_id}', ['middleware' => 'acl:edit_attendance_policy', 'as' => 'attendance.policy.edit', 'uses' => 'AttendancePolicyController@edit']);
    Route::post('attendance-policy/update', ['middleware' => 'acl:edit_attendance_policy', 'as' => 'attendance.policy.update', 'uses' => 'AttendancePolicyController@update']);
    Route::get('attendance-policy/{id}/delete/{agent_id}', ['middleware' => 'acl:delete_attendance_policy', 'as' => 'attendance.policy.delete', 'uses' => 'AttendancePolicyController@destroy']);
    Route::get('attendance-policy/view-details/{head_id}', ['middleware' => 'acl:view_attendance_policy', 'as' => 'attendance.policy.view.details', 'uses' => 'AttendancePolicyController@viewHeadDetails']);

    //Attendance List
    Route::get('attendance-list', ['middleware' => 'acl:view_attendance', 'as' => 'attendance.list', 'uses' => 'AttendanceListController@index']);
    Route::get('attendance-list-data', ['middleware' => 'acl:view_attendance', 'as' => 'attendance.list.data', 'uses' => 'AttendanceListController@getDataTableReport']);
    Route::get('attendance-list/create', ['middleware' => 'acl:add_attendance', 'as' => 'attendance.list.create', 'uses' => 'AttendanceListController@create']);
    Route::post('attendance-list/export', ['middleware' => 'acl:execute_attendance', 'as' => 'attendance.list.export', 'uses' => 'AttendanceListController@postExportFile']);
    Route::post('attendance-list/edit', ['middleware' => 'acl:edit_attendance', 'as' => 'attendance.list.edit', 'uses' => 'AttendanceListController@edit']);
    Route::post('attendance-list/update', ['middleware' => 'acl:edit_attendance', 'as' => 'attendance.list.update', 'uses' => 'AttendanceListController@updateAttendanceManually']);
    Route::get('attendance-list/view-details/{user_id}', ['middleware' => 'acl:view_attendance', 'as' => 'attendance.list.view', 'uses' => 'AttendanceListController@viewAttendanceDetails']);
    Route::get('attendance-list-search', ['middleware' => 'acl:view_attendance', 'as' => 'attendance.list.search', 'uses' => 'AttendanceListController@searchAttendanceDetails']);

    //Holiday Settings
    Route::get('holiday-settings', ['middleware' => 'acl:view_holiday_setup', 'as' => 'agent.holiday_settings', 'uses' => 'HolidaySettingsController@index']);
    Route::get('holiday_settings_data', ['middleware' => 'acl:view_holiday_setup', 'as' => 'agent.datatable.holiday_settings', 'uses' => 'HolidaySettingsController@getDataTableReport']);
    Route::get('holiday-settings/new', ['middleware' => 'acl:add_holiday_setup', 'as' => 'agent.holiday_settings.create', 'uses' => 'HolidaySettingsController@create']);
    Route::post('holiday-settings/store', ['middleware' => 'acl:add_holiday_setup', 'as' => 'agent.holiday-settings.store', 'uses' => 'HolidaySettingsController@store']);
    Route::get('holiday-settings/{id}/edit', ['middleware' => 'acl:edit_holiday_setup', 'as' => 'agent.holiday-settings.edit', 'uses' => 'HolidaySettingsController@edit']);
    Route::post('holiday-settings/{id}/update', ['middleware' => 'acl:edit_holiday_setup', 'as' => 'agent.holiday-settings.update', 'uses' => 'HolidaySettingsController@update']);
    Route::get('holiday-settings/{id}/delete', ['middleware' => 'acl:delete_holiday_setup', 'as' => 'agent.holiday-settings.delete', 'uses' => 'HolidaySettingsController@destroy']);

    //Order List
    Route::get('orders', ['middleware' => 'acl:view_order', 'as' => 'order.list', 'uses' => 'OrderController@index']);
    Route::post('order-list/remarks', ['middleware' => 'acl:view_order', 'as' => 'order.list.remarks', 'uses' => 'OrderController@remarks']);
    Route::get('order-list-data', ['middleware' => 'acl:view_order', 'as' => 'order.list.data', 'uses' => 'OrderController@getDataTableReport']);

    //Zones
    Route::get('zones', ['middleware' => 'acl:view_order', 'as' => 'zones.search', 'uses' => 'ZoneController@index']);
    Route::post('get-zones', ['middleware' => 'acl:view_order', 'as' => 'zones.division', 'uses' => 'ZoneController@getZones']);


