<?php

namespace App\Repositories;

use App\Models\ForumComment;
use App\Models\ForumPost;
use App\Models\PostLike;
use App\Models\PostTags;
use App\Models\Tags;
use App\Repositories\Eloquent\BaseRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;




class CommentRepository
{
    public function __construct(){}



    /**
     * Creates a comment
     *
     * @param  string $author
     * @param  string $contents
     * @param  int $postID
     * @return void
     */
    public function create(string $author, string $contents, int $postID)
    {
        ForumComment::create([
            'author'    => $author,
            'contents'  => $contents,
            'post_id'   => $postID,
        ]);
    }



    /**
     * Gets a comment
     *
     * @param  int $commentID
     * @return ForumComment
     */
    public function get($commentID)
    {
        return ForumComment::where('id', '=', $commentID)
                ->first();
    }



    /**
     * Updates the contents of a comment
     *
     * @param  int $id
     * @param  string $author
     * @param  string $contents
     * @return void
     */
    public function update(int $id, string $author, string $contents)
    {
        ForumComment::where('id', '=', $id)
            ->update([
                'author'    => $author,
                'contents'  => $contents,
            ]);
    }



    /**
     * Deletes a comment
     *
     * @param  int $id
     * @return void
     */
    public function delete(int $id)
    {
        ForumComment::where('id', '=', $id)
                ->delete();
    }
}
