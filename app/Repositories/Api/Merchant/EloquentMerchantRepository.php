<?php namespace App\Repositories\Api\Merchant;

use App\DB\Api\Merchant;
use DB;
use DateTime;

class EloquentMerchantRepository implements MerchantRepository
{

    protected $merchant;

    function __construct(Merchant $merchant)
    {
        $this->merchant = $merchant;
    }

    public function getUserDetails($member_id)
    {
        return $this->merchant->where(['status' => 1, 'member_id' => $member_id])->first();
    }
}
