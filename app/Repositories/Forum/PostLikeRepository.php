<?php

namespace App\Repositories\Forum;

use App\Models\ForumComment;
use App\Models\ForumPost;
use App\Models\PostLike;
use App\Models\PostTags;
use App\Models\Tags;
use App\Repositories\Eloquent\BaseRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;




class PostLikeRepository
{
    public function __construct(){}


    public function create($userID, $postID)
    {
        DB::table('post_likes')
            ->insert([
                'post_id' => $postID,
                'uid' => $userID,
            ]);
    }


    public function get($userID, $postID)
    {
        return DB::table('post_likes')
                ->where('post_id', '=', intval($postID))
                ->where('uid', '=', $userID)
                ->get();
    }


    public function delete($userID, $postID)
    {
        DB::table('post_likes')
            ->where('post_id', '=', $postID)
            ->where('uid', '=', $userID)
            ->delete();
    }

    public function getCount($postID)
    {
        return DB::table('post_likes')
                ->where('post_id', '=', $postID)
                ->count();
    }
}
