<?php

namespace App\Repositories\User;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;


class UserRoleRepository
{
    public function __construct() {}


    public function getRoleIndexByName(string $name) {
        return DB::table('user_roles')
            ->where('name', '=', $name)
            ->first()
            ->id;
    }


    public function createUserRole(int $userID, string $roleName){
        DB::table('user_has_role')
            ->insert([
                'user_id' => $userID,
                'role_id' => $this->getRoleIndexByName($roleName)
            ]);
    }
}
