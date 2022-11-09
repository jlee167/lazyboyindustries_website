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

class TestDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        /* Initialize randomizer library */
        $faker = Faker::create();

        /* */
        define("NUM_USERS", 1000);

        DB::table('products')->insert([
            'title' => "USB Camera",
            'description' => "Test item.\n Testing E-Commerce Functionality.",
            'price_credits' => 30000,
            'active' => true,
        ]);

        DB::table('products')->insert([
            'title' => "LTE Camera",
            'description' => "Test item.\n Testing E-Commerce Functionality.",
            'price_credits' => 50000,
            'active' => true,
        ]);

        DB::table('products')->insert([
            'title' => "Wifi Camera",
            'description' => "Test item.\n Testing E-Commerce Functionality.",
            'price_credits' => 30000,
            'active' => true,
        ]);

        DB::table('products')->insert([
            'title' => "FPGA Camera",
            'description' => "Test item.\n Testing E-Commerce Functionality.",
            'price_credits' => 70000,
            'active' => true,
        ]);

        DB::table('warehouses')->insert([
            'distributor' => "Lazyboy",
            'location' => "Korea",
        ]);

        DB::table('product_stocks')->insert([
            'warehouse_id' => 1,
            'product_id' => 1,
            'quantity_available' => 100000,
        ]);

        DB::table('product_stocks')->insert([
            'warehouse_id' => 1,
            'product_id' => 2,
            'quantity_available' => 100000,
        ]);

        DB::table('product_stocks')->insert([
            'warehouse_id' => 1,
            'product_id' => 3,
            'quantity_available' => 100000,
        ]);

        DB::table('product_stocks')->insert([
            'warehouse_id' => 1,
            'product_id' => 4,
            'quantity_available' => 100000,
        ]);


        $passwordHashed = Hash::make("password");
        $sampleDate = Carbon::now()->toDateTimeString();

        for ($j = 0; $j < 100; $j++) {

            $users = [];
            $credits = [];
            $posts = [];
            $guardianships = [];
            $webTokens = [];

            for ($i = 1; $i <= 1000; $i++) {
                /* Username: user1, user2, user3... */
                $uid = $j * 1000 + $i;
                $username = 'testuser' . strval($uid);
                $image_url = null;
                switch ($uid % 6) {
                    case 0:
                        $image_url = "/images/users/profile/placeholders/pexel_dachshund_2023384.png";
                        break;

                    case 1:
                        $image_url = "/images/users/profile/placeholders/pexel_fox_2295744.png";
                        break;

                    case 2:
                        $image_url = "/images/users/profile/placeholders/pexel_kitty_416160.png";
                        break;

                    case 3:
                        $image_url = "/images/users/profile/placeholders/pexel_pug_1851164.png";
                        break;

                    case 4:
                        $image_url = "/images/users/profile/placeholders/pexel_shiba_1805164.png";
                        break;

                    case 5:
                        $image_url = "/images/users/profile/placeholders/pexel_tiger_792381.png";
                        break;
                }

                $streamKey = str_random(32);
                if ($uid == 1) {
                    $hlsTestKey = $streamKey;
                }
                if ($uid == 2) {
                    $mjpegTestKey = $streamKey;
                }

                /* User Profiles */
                array_push($users, [
                    'firstname' => "testuser",
                    'lastname' => "test",
                    'username' => $username,
                    'password' => $passwordHashed,
                    'auth_provider' => 'Google',
                    'uid_oauth' => str_random(10),
                    'image_url' => env('APP_URL', '') . $image_url,
                    'email' => 'user' . (string) $uid . '@lazyboy.com', //$faker->unique()->email,
                    'cell' => null,
                    'stream_key' => $streamKey,
                    'status' => 'FINE',
                    'password_hint' => 'my hint',
                    'hint_answer' => 'my answer',
                    'email_verified_at' => $sampleDate,
                ]);

                array_push($credits, [
                    'uid' => $uid,
                    'credits' => '1000000'
                ]);

                if ((intval($uid) % 5) == 4) {
                    for ($iter = 0; $iter < 4; $iter++) {
                        $protectedID = $uid;
                        $guardianID =  ($uid - ($uid % 5)) + $iter;

                        array_push($guardianships, [
                            'uid_protected' => $protectedID,
                            'uid_guardian'  => $guardianID,
                            'signed_protected' => 'ACCEPTED',
                            'signed_guardian' => 'ACCEPTED'
                        ]);
                        $resp = Http::get(env('STREAMING_SERVER', 'http://127.0.0.1:3001') . "/jwtgen/" . $guardianID . "/" . $streamKey);
                        $token = $resp->json()['token'];

                        array_push($webTokens, [
                            "uid_protected" => $protectedID,
                            "uid_guardian" => $guardianID,
                            "token" =>$token
                        ]);
                    }
                }

                if (intval($uid) > 2) {

                    array_push($guardianships,[
                        'uid_protected' => 1,
                        'uid_guardian'  => $uid,
                        'signed_protected' => 'ACCEPTED',
                        'signed_guardian' => 'ACCEPTED'
                    ]);

                    $resp = Http::get(env('STREAMING_SERVER', 'http://127.0.0.1:3001') . "/jwtgen/" . $uid . "/" . $hlsTestKey);
                    $token = $resp->json()['token'];

                    array_push($webTokens, [
                        "uid_protected" => 1,
                        "uid_guardian" => $uid,
                        "token" =>$token
                    ]);


                    array_push($guardianships,[
                        'uid_protected' => 2,
                        'uid_guardian'  => $uid,
                        'signed_protected' => 'ACCEPTED',
                        'signed_guardian' => 'ACCEPTED'
                    ]);

                    $resp = Http::get(env('STREAMING_SERVER', 'http://127.0.0.1:3001') . "/jwtgen/" . $uid . "/" . $mjpegTestKey);
                    $token = $resp->json()['token'];

                    array_push($webTokens, [
                        "uid_protected" => 2,
                        "uid_guardian" => $uid,
                        "token" =>$token
                    ]);
                }

                array_push($posts, [
                    'forum' => 'general',
                    'title' => 'TEST POST #' . strval($uid*2),
                    'author' => $username,
                    'contents' => 'Testing'
                ]);
                array_push($posts, [
                    'forum' => 'tech',
                    'title' => 'TEST POST #' . strval($uid*2+1),
                    'author' => $username,
                    'contents' => 'Testing'
                ]);
            }
            try {
                DB::table('users')->insert($users);
                DB::table('credits')->insert($credits);
                DB::table('posts')->insert($posts);
                DB::table("stream_webtokens")->insert($webTokens);
                DB::table('guardianship')->insert($guardianships);
            } catch (QueryException $e) {
                fwrite(STDOUT, $e->getMessage());
                throw $e;
            }
        }
    }
}
