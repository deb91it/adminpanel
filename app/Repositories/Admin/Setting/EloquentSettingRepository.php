<?php namespace App\Repositories\Admin\Setting;

use App\DB\Admin\MailSettings;
use App\DB\Admin\Settings;
use DB;

class EloquentSettingRepository implements SettingRepository
{
    protected $setting;

    function __construct(Settings $setting)
    {
        $this->setting = $setting;
    }

    /**
     * @param array $ar_filter_params
     * @param int $status
     * @param string $order_by
     * @param string $sort
     * @return mixed
     */
    public function getAll($ar_filter_params = [], $status = 1, $order_by = 'id', $sort = 'asc')
    {
        $setting = $this->setting->find(1);
        if (! is_null($setting)) return $setting;
        return (object) [
            'id' => '',
            'distance_unit' => '',
            'provider_time_out' => '',
            'change_provider_tolerance' => '',
            'sms_notification' => '',
            'push_notification' => '',
            'email_notification' => '',
            'referral_code_activation' => '',
            'referral_code_validation' => '',
            'total_ride_to_get_referral_bonus' => '',
            'referral_bonus_to_refered_user' => '',
            'referral_bonus_to_referral' => '',
            'promo_code_activation' => '',
            'admin_email' => '',
            'admin_phone' => '',
            'center_latitude' => '',
            'center_longitude' => '',
            'base_distance' => '',
            'price_per_unit' => '',
            'base_price' => '',
            'pk_base_fare' => '',
            'pk_unit_fare' => '',
            'pk_waiting_min' => '',
            'pk_wtng_min_charge' => '',
            'opk_base_fare' => '',
            'opk_unit_fare' => '',
            'opk_waiting_min' => '',
            'opk_wtng_min_charge' => '',
            'merchant_commission_rate' => '',
        ];
    }

    /**
     * @param $id
     * @param int $status
     * @return mixed
     */
    public function getById($id, $status = 1)
    {
        return $this->setting->find($id);
    }

    /**
     * @param $inputs
     * @return mixed
     */
    public function create($input)
    {
        $setting = $this->setting->find(1);

        if (empty($setting)) {
            $setting = new Settings();
        }

        $setting->distance_unit             = $input['distance_unit'];
        $setting->provider_time_out         = $input['provider_time_out'];
        $setting->change_provider_tolerance = $input['provider_tolerance'];
        $setting->sms_notification          = $input['sms_notification'];
        $setting->push_notification         = $input['push_notification'];
        $setting->email_notification        = $input['email_notification'];
        $setting->referral_code_activation  = $input['referral_code_activation'];
        $setting->referral_code_validation  = $input['referral_code_validation'];
        $setting->total_ride_to_get_referral_bonus  = $input['total_ride_to_get_referral_bonus'];
       // $setting->profit_on_card_payment  = $input['referral_code_activation'];
       // $setting->profit_on_cash_payment  = $input['profit_on_cash_payment'];
        $setting->referral_bonus_to_refered_user     = $input['bonus_to_refered_user'];
        $setting->referral_bonus_to_referral         = $input['bonus_to_referral'];
        $setting->promotional_code_activation        = $input['promo_code_activation'];
        $setting->admin_email               = $input['admin_email'];
        $setting->admin_phone               = $input['admin_phone'];
        $setting->center_latitude           = $input['center_latitude'];
        $setting->center_longitude          = $input['center_longitude'];

        $setting->pk_base_fare              = $input['pk_base_fare'];
        $setting->pk_unit_fare              = $input['pk_unit_fare'];
        //$setting->pk_waiting_min            = $input['pk_waiting_min'];
        $setting->pk_wtng_min_charge        = $input['pk_wtng_min_charge'];

        $setting->opk_base_fare              = $input['opk_base_fare'];
        $setting->opk_unit_fare              = $input['opk_unit_fare'];
        //$setting->opk_waiting_min            = $input['opk_waiting_min'];
        $setting->opk_wtng_min_charge        = $input['opk_wtng_min_charge'];

        $setting->merchant_commission_rate   = $input['merchant_commission_rate'];
        $setting->status                    = 1;

        if ($setting->save()) {
            return $setting->id;
        }

        return 0;
    }

    /**
     * @param $id
     * @param $inputs
     * @return mixed
     */
    public function update($inputs, $id)
    {
        // TODO: Implement update() method.
    }

    /**
     * @param $id
     * @return mixed
     */
    public function delete($id)
    {
        // TODO: Implement delete() method.
    }

    /**
     * @param $id
     * @return mixed
     */
    public function destroy($id)
    {
        // TODO: Implement destroy() method.
    }

    /**
     * @return mixed
     */
    public function getErrors()
    {
        // TODO: Implement getErrors() method.
    }

    public function storeMailConfiguration($request)
    {
        if (!empty($request->id)){
            $conf = MailSettings::find($request->id);
        }else{
            $conf = new MailSettings();
        }
        $conf->mail_driver = $request->mail_driver;
        $conf->mail_host = $request->mail_host;
        $conf->mail_port = $request->mail_port;
        $conf->mail_username = $request->mail_username;
        $conf->mail_password = $request->mail_password;
        $conf->mail_encryption = !empty($request->mail_encryption) ? $request->mail_encryption : 'null';
        $conf->status = 1;
        if (!empty($request->id)){
            $conf->updated_at = date('Y-m-d H:i:s');
        }else{
            $conf->created_at = date('Y-m-d H:i:s');
        }
        if ($conf->save())
        {
            return $conf->id;
        }

    }
}
