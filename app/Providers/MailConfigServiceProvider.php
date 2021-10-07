<?php

namespace App\Providers;

use Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;

class MailConfigServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {

    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        if (\Schema::hasTable('mail_settings')) {
            $mail = DB::table('mail_settings')->first();
            if ($mail) //checking if table is not empty
            {
                $config = array(
                    'driver'     => $mail->mail_driver,
                    'host'       => $mail->mail_host,
                    'port'       => $mail->mail_port,
                    'encryption' => $mail->mail_encryption,
                    'username'   => $mail->mail_username,
                    'password'   => $mail->mail_password,
                );
                Config::set('mail', $config);
            }
        }
    }
}