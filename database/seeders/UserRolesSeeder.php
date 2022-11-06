<?php

namespace Database\Seeders;

//use App\Models\User;
use Carbon\Carbon;
use Faker\Factory as Faker;
use Illuminate\Database\QueryException;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;

class UserRolesSeeder extends Seeder
{

    private function __addPermToRole($roleID, $permissionID)
    {
        DB::table('role_has_permission')->insert([
            'role_id' => $roleID,
            'permission_id' => $permissionID,
        ]);
    }


    /**
     * Bootstrap user roles and permission settings
     *
     * @return void
     */
    public function run()
    {
        $userRoles = ['admin', 'superuser', 'moderator', 'basic'];
        $userRoleIndexes = array_flip($userRoles);

        $permissions = ['ban_user', 'delete_post', 'access_statistics', 'access_accounting'];
        $permissionIndexes = array_flip($permissions);

        foreach($userRoles as $role) {
            DB::table('user_roles')->insert([
                'name' => $role
            ]);
        }


        foreach($permissions as $permission) {
            DB::table('permissions')->insert([
                'name' => $permission
            ]);
        }

        $this->__addPermRole($userRoleIndexes['admin'], $permissionIndexes['ban_user']);
        $this->__addPermRole($userRoleIndexes['admin'], $permissionIndexes['delete_post']);
        $this->__addPermRole($userRoleIndexes['admin'], $permissionIndexes['access_statistics']);
        $this->__addPermRole($userRoleIndexes['admin'], $permissionIndexes['access_accounting']);

        $this->__addPermRole($userRoleIndexes['superuser'], $permissionIndexes['ban_user']);
        $this->__addPermRole($userRoleIndexes['superuser'], $permissionIndexes['delete_post']);
        $this->__addPermRole($userRoleIndexes['superuser'], $permissionIndexes['access_statistics']);

        $this->__addPermRole($userRoleIndexes['moderator'], $permissionIndexes['delete_post']);
    }



}
