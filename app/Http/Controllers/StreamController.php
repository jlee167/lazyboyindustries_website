<?php

namespace App\Http\Controllers;

use APP\Models\Stream;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\WebToken;
use App\Repositories\JwtRepository;

class StreamController extends Controller
{

    protected $tokenRepository;


    public function __construct(JwtRepository $tokenRepository)
    {
        $this->tokenRepository = $tokenRepository;
    }


    /**
     * Retrieve JWT of a protected user's stream if current user is
     * a guardian of that user.
     *
     * @param string uid_protected
     */
    public function getWebToken(string $uid_protected)
    {
        try {
            $jwt = WebToken::where('uid_protected', '=', $uid_protected)
                    ->where('uid_guardian', '=', Auth::id())
                    ->first();

            if ($jwt) {
                return response(["token"  => $jwt->token], 200);
            }
            else
                return response (['error' => 'You are not a guardian of this user'], 401);
        } catch (QueryException $e) {
            return response (["error"  => $e], 401);
        }
    }


    /**
     * Get current user's JWT token for his/her own stream.
     *
     * @return void
     */
    public function getMyWebToken()
    {
        return StreamController::getWebToken(Auth::id(), Auth::id());
    }


    /**
     * Get current user's JWT for broadcast stream specified by stream ID.
     *
     * @param string stream_id
     */
    public function getStreamWebToken(string $stream_id)
    {
        $streamID = $stream_id;
        $resp = DB::table('streams')
            ->join('stream_webtokens', 'stream_webtokens.uid_protected', '=', 'streams.uid')
            ->where('streams.id', '=', $streamID)
            ->where('stream_webtokens.uid_guardian', '=', Auth::id())
            ->select('token')
            ->first();
        if ($resp)
            return response(json_encode(['token' => $resp->token]), 200);
        else
            return response([],404);
    }



    /**
     * Get Stream of a protected user
     *
     * @param string $uid_protected
     */
    public function findStreamByUid(string $uid_protected)
    {
        try {
            $stream = Stream::where('uid', '=', $uid_protected)
                            -> get();
            if (empty($stream))
                return response(['error' => 'Stream is offline'], 404);
            return response(['stream' => $stream], 200);
        } catch (QueryException $e) {
            return response(['error' => $e], 500);
        }
    }
}
