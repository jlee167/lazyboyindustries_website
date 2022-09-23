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




class PostRepository
{
    protected $forumPost;
    protected $forumComment;
    protected $postLikes;
    protected $postTags;
    protected $tags;

    public function __construct(ForumPost $forumPost, ForumComment $forumComment, PostLike $postLikes,
                                    PostTags $postTags, Tags $tags)
    {
        $this->forumPost = $forumPost;
        $this->forumComment = $forumComment;
        $this->postLikes = $postLikes;
        $this->postTags = $postTags;
        $this->tags = $tags;
    }


    /**
     * Create a new forum post
     *
     * @param  array $attributes
     * @return void
     */
    public function createPost(array $attributes)
    {
        $post = ForumPost::create($attributes);
        $post->save();
        return $post;
    }



    /**
     * Delete a forum post
     *
     * @param  int $id
     * @return void
     */
    public function deletePost(int $id)
    {
        ForumPost::where('id', '=', $id)
                ->delete();
    }


    /**
     * Get all posts matching search conditions.
     * Returns only the specified page.
     *
     * @param  mixed $forum
     * @param  mixed $perPage
     * @param  mixed $page
     * @param  mixed $keyword
     * @return array
     */
    public function getPostsInPage($forum, $perPage, $page, $keyword=null)
    {
        $query = $this->forumPost->where('forum', '=', $forum);

        if ($keyword) {
            $query = $query->where('title', 'like', '%' . $keyword . '%');
        }
        return $query->orderByDesc('id')
                    ->forPage($page, $perPage)
                    ->get();
    }


    /**
     * Get number of posts containing keyword in title
     *
     * @param  string $forum
     * @param  string $keyword
     * @return int
     */
    public function getPostCount(string $forum, ?string $keyword)
    {
        $query = ForumPost::where('forum', '=', $forum);
        if ($keyword) {
            $query = $query->where('title', 'like', '%' . $keyword . '%');
        }
        return $query->count();
    }


    /**
     * Get number of likes on a post
     *
     * @param  int $postID
     * @return int
     */
    public function getPostLikes(int $postID)
    {
        return $this->postLikes->where('post_id', '=', $postID)
                    ->count();
    }


    /**
     * Returns image url of the author of the post
     *
     * @param  string $author
     * @return array
     */
    public function getAuthorImageURL(?string $author)
    {
        return DB::table('users')
            ->where('username', '=', $author)
            ->get('image_url')->first()
            ->image_url;
    }


    /**
     * Get number of comments on a post
     *
     * @param  int $postID
     * @return int
     */
    public function getCommentCount(int $postID)
    {
        return DB::table('comments')
            ->where('post_id', '=', $postID)
            ->count();
    }



    /**
     * Get the list of tags of a forum post
     *
     * @param  int $postID
     * @return array
     */
    public function getPostTags(int $postID)
    {
        $tags = [];
        $tagIdList = $this->postTags->where('post_id', '=', $postID)
                            ->get('tag_id');
        foreach ($tagIdList as $tagID) {
            $tag = $this->tags->where('id', '=', $tagID->tag_id)
                ->first();
            Log::info($tag);
            array_push($tags, $tag->name);
        }

        Log::info($tags);

        return $tags;
    }


    /**
     * Get 10 most viewed forum posts
     *
     * @return array
     */
    public function getTopPosts()
    {
        return DB::select("CALL GetTopPosts()");
    }


    /**
     * Get 10 most viewed forum posts in the recent week
     *
     * @return array
     */
    public function getTrendingPosts()
    {
        return DB::select("CALL GetTrendingPosts()");
    }
}
