<?php

namespace App\Repositories\UserRelations;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;


class GuardianshipRepository
{

    public function __construct()
    {
    }


    /**
     * getAffiliatedUsers
     *
     * @param  int $requestID
     * @return array
     */
    public function getAffiliatedUsers(int $requestID)
    {
        $row = DB::table('guardianship')
            ->where('id', '=', $requestID);
        $users = $row->select('uid_protected', 'uid_guardian')
            ->first();
        return [
            "guardian" => $users->uid_guardian,
            "protected" => $users->uid_protected
        ];
    }


    /**
     * Respond as a guardian
     *
     * @param  int $requestID
     * @param  string $response
     * @return void
     */
    public function respondAsGuardian(int $requestID, string $response)
    {
        DB::table('guardianship')
            ->where('id', '=', $requestID)
            ->update(['signed_guardian' => $response]);
    }


    /**
     * Respond as a protected
     *
     * @param  int $requestID
     * @param  string $response
     * @return void
     */
    public function respondAsProtected(int $requestID, string $response)
    {
        DB::table('guardianship')
            ->where('id', '=', $requestID)
            ->update(['signed_protected' => $response]);
    }


    /**
     * Accept being a guardian
     *
     * @param  int $requestID
     * @param  string $response
     * @return void
     */
    public function acceptAsGuardian(int $requestID, string $response)
    {
        DB::table('guardianship')
            ->where('id', '=', $requestID)
            ->update(['signed_guardian' => "ACCEPTED"]);
    }


    /**
     * Reject being a guardian
     *
     * @param  int $requestID
     * @param  string $response
     * @return void
     */
    public function rejectAsGuardian(int $requestID, string $response)
    {
        DB::table('guardianship')
            ->where('id', '=', $requestID)
            ->update(['signed_guardian' => "DENIED"]);
    }



    /**
     * Accept or reject being a protected
     *
     * @param  int $requestID
     * @param  string $response
     * @return void
     */
    public function acceptAsProtected(int $requestID, string $response)
    {
        DB::table('guardianship')
            ->where('id', '=', $requestID)
            ->update(['signed_protected' => "ACCEPTED"]);
    }


    /**
     * Accept or reject being a protected
     *
     * @param  int $requestID
     * @param  string $response
     * @return void
     */
    public function rejectAsProtected(int $requestID, string $response)
    {
        DB::table('guardianship')
            ->where('id', '=', $requestID)
            ->update(['signed_protected' => "DENIED"]);
    }
}
