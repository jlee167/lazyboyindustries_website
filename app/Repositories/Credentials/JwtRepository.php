<?php

namespace App\Repositories\Credentials;

use App\Models\WebToken;
use Illuminate\Support\Facades\DB;

class JwtRepository
{
    public function __construct()
    {

    }


    public function getTokenByStream(int $streamID, int $userID)
    {
        return DB::table('streams')
            ->join('stream_webtokens', 'stream_webtokens.uid_protected', '=', 'streams.uid')
            ->where('streams.id', '=', $streamID)
            ->where('stream_webtokens.uid_guardian', '=', $userID)
            ->select('token')
            ->first();
    }


    public function registerToken(int $guardianID, int $protectedID, string $token)
    {
        DB::table("stream_webtokens")
                ->insert([
                    "uid_protected" => $protectedID,
                    "uid_guardian" => $guardianID,
                    "token" => $token,
                ]);
    }


    public function getTokenByUID(int $protectedID, int $guardianID)
    {
        return $jwt = WebToken::where('uid_protected', '=', $protectedID)
                    ->where('uid_guardian', '=', $guardianID)
                    ->first();
    }
}
