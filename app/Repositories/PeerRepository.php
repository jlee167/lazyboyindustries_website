<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;


class PeerRepository
{

    public function __construct()
    {
    }


    /**
     * getGuardians
     *
     * @param  int $userID
     * @param  int $size
     * @param  int $cursor
     * @return array
     */
    public function getGuardians(int $userID, int $size, int $cursor)
    {
        $query = DB::table('guardianship')
            ->join('users', 'users.id', '=', 'guardianship.uid_guardian')
            ->where('uid_protected', '=', $userID)
            ->where('signed_protected', '=', 'ACCEPTED')
            ->where('signed_guardian', '=', 'ACCEPTED')
            ->whereNotNull('username');

        if ($cursor) {
            $qeury = $query->where('guardianship.id', '>', $cursor);
        }

        if (intval($size)) {
            $query = $query->limit($size);
        } else {
            $query = $query->limit(20);
        }

        return $query->select(
            'users.id',
            'users.username',
            'users.image_url',
            'users.email',
            'users.cell',
            'guardianship.id as guardianshipID'
        )->get();
    }


    /**
     * Send a guardianship request to the user specified by uid
     *
     * @param  int $userID
     * @param  int $guardianID
     * @return void
     */
    public function createGuardian(int $userID, int $guardianID)
    {
        DB::table('guardianship')
            ->insert([
                'uid_protected'     => (string)$userID,
                'uid_guardian'      => (string)$guardianID,
                'signed_protected'  => 'ACCEPTED',
            ]);
    }



    /**
     * delete a guardian
     *
     * @param  int $userID
     * @param  mixed $guardianID
     * @return void
     */
    public function deleteGuardian(int $userID, $guardianID)
    {
        DB::table('guardianship')
            ->where('uid_protected', '=', $userID)
            ->where('uid_guardian', '=', $guardianID)
            ->delete();
    }


    /**
     * Return all protected clients of a user
     *
     * @param  int $userID
     * @param  int $size
     * @param  int $cursor
     * @return array
     */
    public function getProtecteds(int $userID, int $size, int $cursor)
    {
        $query = DB::table('guardianship')
            ->join('users', 'users.id', '=', 'guardianship.uid_protected')
            ->leftJoin('streams', 'streams.uid', '=', 'guardianship.uid_protected')
            ->where('uid_guardian', '=', $userID)
            ->where('signed_protected', '=', 'ACCEPTED')
            ->where('signed_guardian', '=', 'ACCEPTED')
            ->whereNotNull('username');

        if ($cursor) {
            $qeury = $query->where('guardianship.id', '>', $cursor);
        }

        if (intval($size)) {
            $query = $query->limit($size);
        }

        return $query->select(
            'users.id',
            'users.username',
            'users.image_url',
            'users.email',
            'users.cell',
            'users.status',
            'streams.id as streamID',
            'guardianship.id as guardianshipID'
        )->get();
    }


    /**
     * Invite a user to current user's guardianship
     *
     * @param   int $userID
     * @param   int $protectedID
     * @return  void
     */
    public function createProtected(int $userID, int $protectedID)
    {
        DB::table('guardianship')
            ->insert([
                'uid_protected' => $protectedID,
                'uid_guardian' => $userID,
                'signed_guardian' => 'ACCEPTED',
            ]);
    }


    /**
     * delete a protected user
     *
     * @param  int $userID
     * @param  mixed $protectedID
     * @return void
     */
    public function deleteProtected($userID, $protectedID)
    {
        DB::table('guardianship')
                ->where('uid_guardian', '=', $userID)
                ->where('uid_protected', '=', $protectedID)
                ->delete();
    }



    /**
     * Retrieve peer requests pending current user's approval.
     *
     * @param  mixed $userID
     * @return array
     */
    public function getPendingRequests($userID)
    {
        return DB::table('guardianship')
            ->join('users as A', 'A.id', '=', 'guardianship.uid_guardian')
            ->join('users as B', 'B.id', '=', 'guardianship.uid_protected')
            ->where(function ($query) use ($userID) {
                $query->where('guardianship.uid_guardian', '=', $userID)
                    ->orWhere('guardianship.uid_protected', '=', $userID);
            })
            ->where(function ($query) {
                $query->where('guardianship.signed_guardian', '=', 'WAITING')
                    ->orWhere('guardianship.signed_protected', '=', 'WAITING');
            })
            ->select(
                'guardianship.*',
                'A.id as uid_guardian',
                'A.image_url as image_url_guardian',
                'A.username as username_guardian',
                'B.id as uid_protected',
                'B.username as username_protected',
                'B.image_url as image_url_protected'
            )
            ->get();
    }


    public function getRequestByUsers($guardianID, $protectedID)
    {
        return DB::table('guardianship')
                    ->where('guardianship.uid_guardian', '=', $guardianID)
                    ->Where('guardianship.uid_protected', '=', $protectedID)
                    ->first();
    }
}
