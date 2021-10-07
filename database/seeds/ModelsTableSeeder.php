<?php

use Illuminate\Database\Seeder;

class ModelsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('models')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $model_data = [
            [
                'display_name' => 'Admin',
                'model_key' => 'admin',
                'model_class_name' => 'EloquentAdminRepository'
            ],

            [
                'display_name' => 'Passenger',
                'model_key' => 'passenger',
                'model_class_name' => 'EloquentPassengerRepository'
            ],

            [
                'display_name' => 'Driver',
                'model_key' => 'driver',
                'model_class_name' => 'EloquentDriverRepository'
            ],

            [
                'display_name' => 'Merchant',
                'model_key' => 'merchant',
                'model_class_name' => 'EloquentMercentRepository'
            ]
        ];

        foreach($model_data as $val)
        {
            DB::table('models')->insert([
                [
                    'display_name' => $val['display_name'],
                    'model_key'  => $val['model_key'],
                    'model_class_name' => $val['model_class_name'],
                    'created_at'    => date('Y-m-d H:i:s'),
                    'updated_at'    => date('Y-m-d H:i:s'),
                ]
            ]);
        }
    }
}
