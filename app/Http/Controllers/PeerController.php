<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Repositories\GuardianshipRepository;
use App\Repositories\JwtRepository;
use App\Repositories\PeerRepository;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use PragmaRX\Google2FA\Google2FA;


class PeerController extends BaseController
{
    const DEFAULT_SIZE = 20;
    const DEFAULT_PAGE = 1;


    private $peerRepository;
    private $guardianshipRepository;
    private $jwtRepository;

    public function __construct(PeerRepository $peerRepository,
                                GuardianshipRepository $guardianshipRepository,
                                JwtRepository $jwtRepository)
    {
        $this->peerRepository = $peerRepository;
        $this->guardianshipRepository = $guardianshipRepository;
        $this->jwtRepository = $jwtRepository;
    }


    public function __isInt($input)
    {
        if ($input == null)
            return false;
        return (int)$input == $input;
    }


    /**
     * getGuardians
     *
     * @param  Illuminate\Http\Request $request
     * @param  string $size
     * @param  string $cursor
     * @return Request
     */
    public function getGuardians(Request $request)
    {
        Log::info("Getting Guardians");
        try{
            $requestedSize = $request->input('size');
            $requestedPage = $request->input('page');
            $size = $this->__isInt($requestedSize) ? (int)$requestedSize : self::DEFAULT_SIZE;
            $cursor = $this->__isInt($requestedPage) ? (int)$requestedPage : self::DEFAULT_PAGE;

            $result = $this->peerRepository->getGuardians(Auth::id(), (int)$size, (int)$cursor);
            return response(['guardians' => $result], 200);
        } catch (Exception $e) {
            Log::ERROR($e);
        }
    }



    /**
     * Send a guardianship request to the user specified by username
     *
     * @param  Illuminate\Http\Request  $request
     * @param  string $username
     * @return Illuminate\Http\Response
     */
    public function addGuardian(Request $request, $username)
    {
        $userID = Auth::id();

        try {
            $guardianID = User::where('username', '=', (string) $username)
                ->first()
                ->id;
        } catch (Exception $e) {
            return response([
                'error' => (string) "User does not exist",
            ], 404);
        }

        if ($guardianID == $userID) {
            return response(['error' => "You cannot add yourself"], 409);
        }

        $existingRequest = $this->peerRepository->getRequestByUsers(
            guardianID: $guardianID,
            protectedID: $userID
        );
        if ($existingRequest) {
            return response(['error' => "A request has already been sent"], 409);
        }

        try {
            $this->peerRepository->createGuardian($userID, $guardianID);
            return response(null, 200);
        } catch (\Illuminate\Database\QueryException $e) {
            return response([
                'error' => 'Unknown database error. Admin will look into it.',
            ], 500);
        }
    }




    /**
     * Return all protected clients of a user
     *
     * @param  Illuminate\Http\Request $request
     * @param  string $size
     * @param  string $cursor
     * @return Illuminate\Http\Response
     */
    public function getProtecteds(Request $request)
    {
        $requestedSize = $request->input('size');
        $requestedPage = $request->input('page');
        $size = $this->__isInt($requestedSize) ? (int)$requestedSize : self::DEFAULT_SIZE;
        $cursor = $this->__isInt($requestedPage) ? (int)$requestedPage : self::DEFAULT_PAGE;

        $result = $this->peerRepository->getProtecteds(Auth::id(), $size, $cursor);
        return response(['protecteds' => $result], 200);
    }




    /**
     * Invite a user to current user's guardianship
     *
     * @param  Illuminate\Http\Request $request
     * @param  string $username
     * @return Illuminate\Http\Response
     */
    public function addProtected(Request $request, $username)
    {
        $userID = Auth::id();
        try {
            $protectedID = User::where('username', '=', (string) $username)
                ->first()
                ->id;
        } catch (Exception $e) {
            return response(['error' => (string) "User does not exist"], 404);
        }

        if ($protectedID == $userID) {
            return response(['error' => "You cannot add yourself"], 409);
        }

        $existingRequest = $this->peerRepository->getRequestByUsers(
            guardianID: $userID,
            protectedID: $protectedID
        );
        if ($existingRequest) {
            return response(['error' => "A request has already been sent"], 409);
        }

        try {
            $this->peerRepository->createProtected($userID, $protectedID);
            return response(null, 200);
        } catch (\Illuminate\Database\QueryException $e) {
            Log::error($e->getMessage());
            return response([
                'error' => 'Unknown database error. Admin will look into it.',
            ], 500);
        }
    }



    /**
     * Remove a peer from current user's guardian list
     *
     * @param  Illuminate\Http\Request $request
     * @param  string $uid
     * @return Illuminate\Http\Response
     */
    public function deleteGuardian(Request $request, $uid)
    {
        try {
            $this->peerRepository->deleteGuardian(Auth::id(), $uid);
        } catch (Exception $e) {
            return response(null, 500);
        }

        return response(null, 200);
    }




    /**
     * Remove a peer from current user's protected list
     *
     * @param  Illuminate\Http\Request $request
     * @param  string $uid
     * @return Illuminate\Http\Response
     */
    public function deleteProtected(Request $request, $uid)
    {
        try {
            $this->peerRepository->deleteProtected(Auth::id(), $uid);
        } catch (Exception $e) {
            return response(null, 500);
        }

        return reponse(null, 200);
    }



    /**
     * Accept for reject a peer request sent to the current user.
     *
     * @param  Illuminate\Http\Request  $request
     * @return Illuminate\Http\Response
     */
    public function respondPeerRequest(Request $request)
    {
        DB::beginTransaction();

        $requestID = (int)$request->input("requestID");
        $uid = Auth::id();
        $response = (string) $request->input("response");

        /* Get ID of guardian and protected */
        $affiliatedUsers = $this->guardianshipRepository->getAffiliatedUsers($requestID);
        $guardianID = $affiliatedUsers["guardian"];
        $protectedID = $affiliatedUsers["protected"];

        if ($uid == $guardianID) {
            $this->guardianshipRepository->respondAsGuardian($requestID, $response);
        } else if ($uid == $protectedID) {
            $this->guardianshipRepository->respondAsProtected($requestID, $response);
        } else {
            DB::rollBack();
            return response("Request ID and User ID do not match", 404);
        }

        $privateKey = User::where('id', '=', $protectedID)
            ->first()
            ->stream_key;

        /* Request Streaming server to generate JWT token for this guardianship
            @Todo: Move logic to main server. */
        if ($response === "ACCEPTED") {
            $jwtGenUrl = env('STREAMING_SERVER', 'http://127.0.0.1:3001') . "/jwtgen/"
                . $guardianID . "/" . $privateKey;
            $response = Http::get($jwtGenUrl);

            if ($response->failed()) {
                DB::rollBack();
                return response("Streaming Server is down! Please retry!", 500);
            }
            $token = $response->json()['token'];
            $this->jwtRepository->registerToken($guardianID, $protectedID, $token);
        }

        DB::commit();
        return response($response, 200);
    }




    /**
     * Retrieve peer requests pending current user's approval.
     *
     * @param  Illuminate\Http\Request  $request
     * @return Illuminate\Http\Response
     */
    public function getPendingRequests(Request $request)
    {
        $result = $this->peerRepository->getPendingRequests(Auth::id());
        return response(['pendingRequests' => $result], 200);
    }
}
