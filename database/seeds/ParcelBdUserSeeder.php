<?php

use Illuminate\Database\Seeder;

class ParcelBdUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //Empty all ACL related table
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('permission_groups')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('permissions')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('admin_users')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('members')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('roles')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('role_member')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('role_permissions')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('merchants')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        //Make data array
        $user_data = [
            [
                'group_name' => 'Permission group',
                'status' => '1',
                'permissions' => [
                    [
                        'name' => 'view_permission_group',
                        'display_name'  => 'View',
                        'status'  => 1,
                    ],
                    [
                        'name' => 'add_permission_group',
                        'display_name'  => 'Add',
                        'status'  => 1,
                    ],
                    [
                        'name' => 'edit_permission_group',
                        'display_name'  => 'Edit',
                        'status'  => 1,
                    ],
                    [
                        'name' => 'delete_permission_group',
                        'display_name'  => 'Delete',
                        'status'  => 1,
                    ],
                    [
                        'name' => 'execute_permission_group',
                        'display_name'  => 'Execute',
                        'status'  => 1,
                    ]
                ]
            ],

            [
                'group_name' => 'Permission',
                'status' => '1',
                'permissions' => [
                    [
                        'name' => 'view_permission',
                        'display_name'  => 'View',
                        'status'  => 1,
                    ],
                    [
                        'name' => 'add_permission',
                        'display_name'  => 'Add',
                        'status'  => 1,
                    ],
                    [
                        'name' => 'edit_permission',
                        'display_name'  => 'Edit',
                        'status'  => 1,
                    ],
                    [
                        'name' => 'delete_permission',
                        'display_name'  => 'Delete',
                        'status'  => 1,
                    ],
                    [
                        'name' => 'execute_delete_permission',
                        'display_name'  => 'Execute',
                        'status'  => 1,
                    ]
                ]
            ],

            [
                'group_name' => 'User role',
                'status' => '1',
                'permissions' => [
                    [
                        'name' => 'view_role',
                        'display_name'  => 'View',
                        'status'  => 1,
                    ],
                    [
                        'name' => 'add_role',
                        'display_name'  => 'Add',
                        'status'  => 1,
                    ],
                    [
                        'name' => 'edit_role',
                        'display_name'  => 'Edit',
                        'status'  => 1,
                    ],
                    [
                        'name' => 'delete_role',
                        'display_name'  => 'Delete',
                        'status'  => 1,
                    ],
                    [
                        'name' => 'execute_role',
                        'display_name'  => 'Execute',
                        'status'  => 1,
                    ]
                ]
            ],

            [
                'group_name' => 'Settings',
                'status' => '1',
                'permissions' => [
                    [
                        'name' => 'view_settings',
                        'display_name'  => 'View',
                        'status'  => 1,
                    ],
                    [
                        'name' => 'add_settings',
                        'display_name'  => 'Add',
                        'status'  => 1,
                    ],
                    [
                        'name' => 'edit_settings',
                        'display_name'  => 'Edit',
                        'status'  => 1,
                    ],
                    [
                        'name' => 'delete',
                        'display_name'  => 'Delete',
                        'status'  => 1,
                    ],
                    [
                        'name' => 'execute_settings',
                        'display_name'  => 'Execute',
                        'status'  => 1,
                    ]
                ]
            ],

            [
                'group_name' => 'Dashboard',
                'status' => '1',
                'permissions' => [
                    [
                        'name' => 'view_dashboard',
                        'display_name'  => 'View',
                        'status'  => 1,
                    ],
                    [
                        'name' => '',
                        'display_name'  => 'Add',
                        'status'  => 1,
                    ],
                    [
                        'name' => '',
                        'display_name'  => 'Edit',
                        'status'  => 1,
                    ],
                    [
                        'name' => '',
                        'display_name'  => 'Delete',
                        'status'  => 1,
                    ],
                    [
                        'name' => 'execute_dashboard',
                        'display_name'  => 'Execute',
                        'status'  => 1,
                    ]
                ]
            ],

            [
                'group_name' => 'Merchant dashboard',
                'status' => '1',
                'permissions' => [
                    [
                        'name' => 'view_merchant_dash',
                        'display_name'  => 'View',
                        'status'  => 1,
                    ],
                    [
                        'name' => '',
                        'display_name'  => '',
                        'status'  => 1,
                    ],
                    [
                        'name' => '',
                        'display_name'  => '',
                        'status'  => 1,
                    ],
                    [
                        'name' => '',
                        'display_name'  => '',
                        'status'  => 1,
                    ],
                    [
                        'name' => 'execute_merchant_dash',
                        'display_name'  => 'Execute',
                        'status'  => 1,
                    ]
                ]
            ],

            [
                'group_name' => 'Rider dashboard',
                'status' => '1',
                'permissions' => [
                    [
                        'name' => 'view_rider_dash',
                        'display_name'  => 'View',
                        'status'  => 1,
                    ],
                    [
                        'name' => '',
                        'display_name'  => '',
                        'status'  => 1,
                    ],
                    [
                        'name' => '',
                        'display_name'  => '',
                        'status'  => 1,
                    ],
                    [
                        'name' => '',
                        'display_name'  => '',
                        'status'  => 1,
                    ],
                    [
                        'name' => 'execute_rider_dash',
                        'display_name'  => 'Execute',
                        'status'  => 1,
                    ]
                ]
            ],

            [
                'group_name' => 'Admin User',
                'status' => '1',
                'permissions' => [
                    [
                        'name' => 'view_admin_user',
                        'display_name'  => 'View',
                        'status'  => 1,
                    ],
                    [
                        'name' => 'add_admin_user',
                        'display_name'  => 'Add',
                        'status'  => 1,
                    ],
                    [
                        'name' => 'edit_admin_user',
                        'display_name'  => 'Edit',
                        'status'  => 1,
                    ],
                    [
                        'name' => 'delete_admin_user',
                        'display_name'  => 'Delete',
                        'status'  => 1,
                    ],
                    [
                        'name' => 'execute_admin_user',
                        'display_name'  => 'Execute',
                        'status'  => 1,
                    ]
                ]
            ],

            [
                'group_name' => 'Rider',
                'status' => '1',
                'permissions' => [
                    [
                        'name' => 'view_rider',
                        'display_name'  => 'View',
                        'status'  => 1,
                    ],
                    [
                        'name' => 'add_rider',
                        'display_name'  => 'Add',
                        'status'  => 1,
                    ],
                    [
                        'name' => 'edit_rider',
                        'display_name'  => 'Edit',
                        'status'  => 1,
                    ],
                    [
                        'name' => 'delete_rider',
                        'display_name'  => 'Delete',
                        'status'  => 1,
                    ],
                    [
                        'name' => 'execute_rider',
                        'display_name'  => 'Execute',
                        'status'  => 1,
                    ]
                ]
            ],

            [
                'group_name' => 'Merchant',
                'status' => '1',
                'permissions' => [
                    [
                        'name' => 'view_merchant',
                        'display_name'  => 'View',
                        'status'  => 1,
                    ],
                    [
                        'name' => 'add_merchant',
                        'display_name'  => 'Add',
                        'status'  => 1,
                    ],
                    [
                        'name' => 'edit_merchant',
                        'display_name'  => 'Edit',
                        'status'  => 1,
                    ],
                    [
                        'name' => 'delete_merchant',
                        'display_name'  => 'Delete',
                        'status'  => 1,
                    ],
                    [
                        'name' => 'execute_merchant',
                        'display_name'  => 'Execute',
                        'status'  => 1,
                    ]
                ]
            ],

            [
                'group_name' => 'Profile',
                'status' => '1',
                'permissions' => [
                    [
                        'name' => 'view_profile',
                        'display_name'  => 'View',
                        'status'  => 1,
                    ],
                    [
                        'name' => '',
                        'display_name'  => 'Add',
                        'status'  => 1,
                    ],
                    [
                        'name' => 'edit_profile',
                        'display_name'  => 'Edit',
                        'status'  => 1,
                    ],
                    [
                        'name' => '',
                        'display_name'  => 'Delete',
                        'status'  => 1,
                    ],
                    [
                        'name' => 'execute_profile',
                        'display_name'  => 'Execute',
                        'status'  => 1,
                    ]
                ]
            ],

            [
                'group_name' => 'Hub',
                'status' => '1',
                'permissions' => [
                    [
                        'name' => 'view_hub',
                        'display_name'  => 'View',
                        'status'  => 1,
                    ],
                    [
                        'name' => 'add_hub',
                        'display_name'  => 'Add',
                        'status'  => 1,
                    ],
                    [
                        'name' => 'edit_hub',
                        'display_name'  => 'Edit',
                        'status'  => 1,
                    ],
                    [
                        'name' => 'delete_hub',
                        'display_name'  => 'Delete',
                        'status'  => 1,
                    ],
                    [
                        'name' => 'execute_hub',
                        'display_name'  => 'Execute',
                        'status'  => 1,
                    ]
                ]
            ],

            [
                'group_name' => 'Delivery',
                'status' => '1',
                'permissions' => [
                    [
                        'name' => 'view_delivery',
                        'display_name'  => 'View',
                        'status'  => 1,
                    ],
                    [
                        'name' => 'add_delivery',
                        'display_name'  => 'Add',
                        'status'  => 1,
                    ],
                    [
                        'name' => 'edit_delivery',
                        'display_name'  => 'Edit',
                        'status'  => 1,
                    ],
                    [
                        'name' => 'delete_delivery',
                        'display_name'  => 'Delete',
                        'status'  => 1,
                    ],
                    [
                        'name' => 'execute_delivery',
                        'display_name'  => 'Execute',
                        'status'  => 1,
                    ]
                ]
            ],

            [
                'group_name' => 'Store',
                'status' => '1',
                'permissions' => [
                    [
                        'name' => 'view_store',
                        'display_name'  => 'View',
                        'status'  => 1,
                    ],
                    [
                        'name' => 'add_store',
                        'display_name'  => 'Add',
                        'status'  => 1,
                    ],
                    [
                        'name' => 'edit_store',
                        'display_name'  => 'Edit',
                        'status'  => 1,
                    ],
                    [
                        'name' => 'delete_store',
                        'display_name'  => 'Delete',
                        'status'  => 1,
                    ],
                    [
                        'name' => 'execute_store',
                        'display_name'  => 'Execute',
                        'status'  => 1,
                    ]
                ]
            ],

            [
                'group_name' => 'Product',
                'status' => '1',
                'permissions' => [
                    [
                        'name' => 'view_product',
                        'display_name'  => 'View',
                        'status'  => 1,
                    ],
                    [
                        'name' => 'add_product',
                        'display_name'  => 'Add',
                        'status'  => 1,
                    ],
                    [
                        'name' => 'edit_product',
                        'display_name'  => 'Edit',
                        'status'  => 1,
                    ],
                    [
                        'name' => 'delete_product',
                        'display_name'  => 'Delete',
                        'status'  => 1,
                    ],
                    [
                        'name' => 'execute_product',
                        'display_name'  => 'Execute',
                        'status'  => 1,
                    ]
                ]
            ],

            [
                'group_name' => 'Invoice',
                'status' => '1',
                'permissions' => [
                    [
                        'name' => 'view_invoice',
                        'display_name'  => 'View',
                        'status'  => 1,
                    ],
                    [
                        'name' => 'add_invoice',
                        'display_name'  => 'Add',
                        'status'  => 1,
                    ],
                    [
                        'name' => 'edit_invoice',
                        'display_name'  => 'Edit',
                        'status'  => 1,
                    ],
                    [
                        'name' => 'delete_invoice',
                        'display_name'  => 'Delete',
                        'status'  => 1,
                    ],
                    [
                        'name' => 'execute_invoice',
                        'display_name'  => 'Execute',
                        'status'  => 1,
                    ]
                ]
            ]
        ];

        foreach($user_data as $data)
        {
            $id = DB::table('permission_groups')->insertGetId(
                [
                    'group_name'    => $data['group_name'],
                    'status'        => $data['status'],
                    'created_at'    => date('Y-m-d H:i:s'),
                    'updated_at'    => date('Y-m-d H:i:s'),
                ]
            );

            foreach($data['permissions'] as $val)
            {
                DB::table('permissions')->insert([
                    [
                        'name'          => $val['name'],
                        'display_name'  => $val['display_name'],
                        'permission_group_id'  => $id,
                        'status'        => $val['status'],
                        'created_at'    => date('Y-m-d H:i:s'),
                        'updated_at'    => date('Y-m-d H:i:s'),
                    ]
                ]);
            }
        }

        //Make role
        DB::table('roles')->insert([
            [
                'role_name'     => 'Super admin',
                'status'        => 1,
                'created_at'    => date('Y-m-d H:i:s'),
                'updated_at'    => date('Y-m-d H:i:s')
            ],

            [
                'role_name'     => 'Rider',
                'status'        => 1,
                'created_at'    => date('Y-m-d H:i:s'),
                'updated_at'    => date('Y-m-d H:i:s')
            ],

            [
                'role_name'     => 'Merchant',
                'status'        => 1,
                'created_at'    => date('Y-m-d H:i:s'),
                'updated_at'    => date('Y-m-d H:i:s')
            ]
        ]);

        //Make super admin user
        //Login info
        $user_id = DB::table('members')->insertGetId(
            [
                'email'         => 'admin@admin.com',
                'password'      => bcrypt('123456'),
                'mobile_no'     => '01016000000',
                'model_id'      => 1,
                'user_type'     => 0,
                'is_active'     => 1,
                'can_login'     => 1,
                'status'        => 1
            ]
        );

        //Basic info of admin user
        DB::table('admin_users')->insertGetId(
            [
                'first_name'    => 'Super',
                'last_name'     => 'Admin',
                'member_id'     => $user_id,
                'status'        => 1
            ]
        );

        //User Role relation
        DB::table('role_member')->insert(
            [
                'role_id'       => 1,
                'member_id'     => $user_id,
                'created_at'    => date('Y-m-d H:i:s'),
                'updated_at'    => date('Y-m-d H:i:s')
            ]
        );

        //Role permission relation
        $role_permissions = [
            [
                'role_id'       => 1,
                'permissions'   => ',view_dashboard,'
            ],
            //Agent
            [
                'role_id'       => 2,
                'permissions'   => ',execute_rider_dash,view_rider_dash,'
            ],
            //Merchant
            [
                'role_id'       => 3,
                'permissions'   => ',view_merchant_dash,execute_merchant_dash,'
            ]
        ];

        foreach($role_permissions as $role_permission)
        {
            DB::table('role_permissions')->insert(
                [
                    'role_id'       => $role_permission['role_id'],
                    'permissions'   => $role_permission['permissions'],
                    'created_at'    => date('Y-m-d H:i:s'),
                    'updated_at'    => date('Y-m-d H:i:s')
                ]
            );
        }
    }
}
