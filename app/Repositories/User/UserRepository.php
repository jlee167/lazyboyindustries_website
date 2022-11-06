<?php

namespace App\Repositories\User;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;


class UserRepository
{

    public function __construct() {}


    /**
     * Get user model by its ID
     *
     * @param  int $id
     * @return User
     */
    public function getByID(int $id): User {
        return User::where('id', '=', (string)$id)
            ->first();
    }


    /**
     * Get user model by username
     *
     * @param  mixed $username
     * @return void
     */
    public function getByUsername(string $username): User {
        return User::where('username', '=', $username)
            ->first();
    }


    /**
     * Delete a user from database
     *
     * @param  int $id
     * @return void
     */
    public function delete(int $id): void
    {
        $user = User::where('id', '=', (string) $id)
                ->first();
        $user->email = null;
        $user->password = null;
        $user->google2fa_secret = null;
        $user->firstname = null;
        $user->lastname = null;
        $user->username = null;
        $user->auth_provider = null;
        $user->uid_oauth = null;
        $user->image_url = null;
        $user->save();
    }



    /**
     * Remove 2FA enable flag and delete 2FA credentials from database.
     *
     * @param  int $userID
     * @return void
     */
    public function disable2FA(int $userID): void
    {
        $user = $this->getByID($userID);
        $user->google2fa_secret = null;
        $user->google2fa_active = 0;
        $user->save();
    }

    public function enable2FA(int $userID, string $secret): void
    {
        $user = $this->getByID($userID);
        $user->google2fa_secret = $secret;
        $user->save();
    }



    public function changePassword(int $userID, string $newPassword): void
    {
        $user = $this->getByID($userID);
        $user->password = Hash::make($newPassword);
        $user->save();
    }


    public function updateImage(int $userID)
    {
        $user = $this->getByID($userID);

        try {
            $filename = 'userimg_' . $userID;
            $filePath = "./images/users/profile/" . $filename;
            move_uploaded_file($_FILES['imgFile']['tmp_name'], $filePath);
        } catch (Exception $e) {
            throw $e;
        }

        $user->image_url = "/images/users/profile/" . $filename;
        $user->save();
    }


    /**
     * Retrieve user info from OAuth token
     *
     * @param  string $accessToken   // Google Oauth2 Token
     * @return array  $user          // User Information
     */
    public function getGoogleUser($accessToken){

        try {
            $user = [];
            $client = new \Google_Client();
            $client->setClientId(env('GOOGLE_APP_KEY', ""));
            $client->setClientSecret(env('GOOGLE_SECRET', ""));

            if ($accessToken) {
                $payload = $client->verifyIdToken($accessToken);
            }

            if ($payload) {
                $user['uid']                = $payload['sub'];
                $user['email']              = $payload['email'];
                $user['verified']           = $payload['email_verified'];
                $user['name']               = $payload['name'];
                $user['profile_picture']    = $payload['picture'];
                return $user;
            } else {
                // Invalid ID token
                return null;
            }
        } catch (\Exception $e) {
            return "error@!";
        }
    }



    /**
     * Retrieve user info from OAuth token
     *
     * @param  string $accessToken   // Kakao Oauth2 Token
     * @return array  $user          // User Information
     */

    public function getKakaoUser($accessToken){

        /* Use Kakao's Oauth REST API */
        $authorization = 'Authorization: Bearer ' . $accessToken;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Bearer ' . $accessToken));
        curl_setopt($ch, CURLOPT_URL, 'https://kapi.kakao.com/v2/user/me');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($ch);

        /* Get http response */
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        $result = json_decode($result, true);

        if (!$result) {
            echo 'No response from Kakao Auth Server \n';
            exit;
        }

        $user = [];
        $user['uid'] = $result["id"];
        $user['name'] = $result["properties"]["nickname"];
        $user['email'] = $result["kakao_account"]["email"];
        $user['profile_picture'] = $result["kakao_account"]["profile"]["thumbnail_image_url"];
        return $user;
    }
}
