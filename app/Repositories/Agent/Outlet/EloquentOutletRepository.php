<?php namespace App\Repositories\Agent\Outlet;

use DB;
use Datatables;

class EloquentOutletRepository implements OutletRepository
{
    public function getReportPaginated($request, $company_id){

        $start_date = '';
        $end_date = '';
        $date_range = $request->get('columns')[6]['search']['value'];
        $category_id = $request->get('columns')[3]['search']['value'];

        if ($date_range != '') {
            list($start_date, $end_date) = explode('~', preg_replace('/\s+/', '', $date_range));
            if (! date_validate($start_date)  || ! date_validate($end_date)) {
                $start_date = '';
                $end_date = '';
            }
        }

        $query = DB::table('outlets as otl')
            ->select(
                'otl.id as outlet_id', 'otl.name as outlet_name','otl.address as outlet_address', 'oc.name as category',
                DB::raw('CONCAT(otlownr.first_name, " ", otlownr.last_name) AS owner_name'), 'otlownr.email as owner_email',
                'otlownr.phone as owner_phone',
                DB::raw("DATE_FORMAT(otl.created_at,'%M %d %Y %h:%i %p') AS created_date "),
                DB::raw('CONCAT(ag.first_name, " ", ag.last_name) AS sr_name'), 'm.email as sr_email', 'm.mobile_no as sr_phone'
            )
            ->join('outlet_categories as oc', 'oc.id', '=',  'otl.category_id')
            ->join('agents as ag', 'ag.id', '=', 'otl.sr_id')
            ->join('members as m', 'm.id', '=', 'ag.member_id')
            ->join('outlet_owners as otlownr', 'otlownr.id', '=', 'otl.owner_id')
            ->where('otl.company_id', '=', $company_id);

            if ($category_id != '' && $category_id >= 1) {
                $query = $query->where('oc.id', $category_id);
            }
            if ($start_date != '' && $end_date != '') {
                $query = $query->whereBetween('otl.created_at', [$start_date ." 00:00:00" , $end_date ." 23:59:59"]);
            }

        return Datatables::of($query)
            ->filterColumn('outlet_name', function($query, $keyword) {
                $query->whereRaw("otl.name like ?", ["%{$keyword}%"]);
            })
            ->filterColumn('outlet_address', function($query, $keyword) {
                $query->whereRaw("otl.address like ?", ["%{$keyword}%"]);
            })
            ->filterColumn('owner_name', function($query, $keyword) {
                $query->whereRaw("CONCAT(otlownr.first_name, otlownr.last_name, otlownr.email, otlownr.phone) like ?", ["%{$keyword}%"]);
            })
            ->filterColumn('sr_name', function($query, $keyword) {
                $query->whereRaw("CONCAT(ag.first_name, ag.last_name, m.email, m.mobile_no) like ?", ["%{$keyword}%"]);
            })
            ->make(true);
    }
}