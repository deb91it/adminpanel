<?php namespace App\Repositories\Api\Outlet;

interface OutletRepository
{
    public function getOutletCategory($company_id);
    
    //public function getOutletGroupList();
    
    public function getOutletMake();
    
    //public function getOutletMakeAll($token_member_id = 0);
    
    public function getOutletRegAllData();
    
   //public function getOutletRegAllDataByMemberId($token_member_id = 0);

    public function addOutlet($inputs);

    public function addOutletOrder($inputs);

    public function getDriverOutlet($driver_id);

    public function getMarchantOutlet($marchant_id);

    public function getOutlet($request, $id, $tse);

    public function updateOutlet($inputs, $id);

    public function postOrderUpdate($inputs, $id);

    public function destroy($id, $driver_id);

    public function saveOutletImage($inputs);
}
