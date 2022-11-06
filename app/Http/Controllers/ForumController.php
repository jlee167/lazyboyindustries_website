<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\ForumComment;
use App\Models\ForumPost;
use App\Models\PostTags;
use App\Models\Tags;
use App\Models\User;
use App\Repositories\CommentRepository;
use App\Repositories\PostLikeRepository;
use App\Repositories\PostRepository;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ForumController extends Controller
{
    const MAX_POSTS_PER_PAGE = 10;
    const DEFAULT_PAGE = 1;


    private $postRepository;
    private $commentRepository;
    private $postLikeRepository;


    public function __construct(PostRepository $postRepository,
                                CommentRepository $commentRepository,
                                PostLikeRepository $postLikeRepository)
    {
        $this->postRepository = $postRepository;
        $this->commentRepository = $commentRepository;
        $this->postLikeRepository = $postLikeRepository;
    }


    /**
     *
     * Searches posts from forum with search keyword and return
     *  1. posts of specified page of selected forum.
     *  2. Number of items in entire search result.
     *
     *  Page Size = 10 (Fixed)
     *
     * @Todo
     *  - Parametrize Page Size
     *  - Move keyword to body from url
     *
     * @param  string   $forum_name             // forum name
     * @param  int      $page                   // page index
     * @param  string   $keyword                // search keyword (search all posts if 'all' or empty string)
     * @return JSON     {$posts, $itemcount}    // post contents and number of posts in tis page
     *
     */
    public function getPage(Request $request, string $forum_name, int $page, string $keyword = null)
    {
        try {
            DB::beginTransaction();

            /* Get posts */
            if (empty($keyword)) {
                $itemCount = $this->postRepository->getPostCount($forum_name, null);

                $posts = $this->postRepository->getPostsInPage(
                    $forum = $forum_name,
                    $perPage = self::MAX_POSTS_PER_PAGE,
                    $page = $page
                );
            } else {
                $itemCount = $this->postRepository->getPostCount($forum_name, $keyword);

                $posts = $this->postRepository->getPostsInPage(
                    $forum = $forum_name,
                    $perPage = self::MAX_POSTS_PER_PAGE,
                    $page = $page,
                    $keyword = $keyword
                );
            }

            /* Get post likes and user images */
            foreach ($posts as $post) {
                $post->likes = $this->postRepository->getPostLikes($post->id);
                $post->image_url = $this->postRepository->getAuthorImageURL($post->author);
                $post->comment_count = $this->postRepository->getCommentCount($post->id);
                $post->tags = $this->postRepository->getPostTags($post->id);
            }

            DB::commit();

            return response([
                'itemCount' => $itemCount,
                'posts' => $posts,
            ], 200);
        } catch (QueryException | Exception $e) {
            DB::rollBack();
            return response([], 500);
        }
    }


    public function getPageByTag(Request $request, string $forum_name, int $page, string $tag)
    {
        DB::beginTransaction();

        $posts = DB::table('posts')
            ->where('forum', '=', $forum_name)
            ->join('post_tags', 'post_tags.post_id', '=', 'posts.id')
            ->join('tags', 'post_tags.tag_id', '=', 'tags.id')
            ->where('tags.name', '=', $tag)
            ->orderByDesc('posts.id')
            ->forPage($page, self::MAX_POSTS_PER_PAGE)
            ->get();

        $itemCount =  DB::table('posts')
                        ->where('forum', '=', $forum_name)
                        ->join('post_tags', 'post_tags.post_id', '=', 'posts.id')
                        ->join('tags', 'post_tags.tag_id', '=', 'tags.id')
                        ->where('tags.name', '=', $tag)
                        ->count();

        foreach ($posts as $post) {
            $post->likes = $this->postRepository->getPostLikes($post->id);
            $post->image_url = $this->postRepository->getAuthorImageURL($post->author);
            $post->comment_count = $this->postRepository->getCommentCount($post->id);
            $post->tags = $this->postRepository->getPostTags($post->id);
        }

        return response([
            'itemCount' => $itemCount,
            'posts' => $posts,
        ], 200);

        DB::commit();
    }



    /**
     * Get 10 most viewed posts of all time.
     *
     * @param  Illuminate\Http\Request $request
     * @return Illuminate\Http\Response
     */
    public function getTopPosts(Request $request)
    {
        try {
            return response($this->postRepository->getTopPosts(), 200);
        } catch (Exception $e) {
            return response(null, 500);
        }


    }


    /**
     * Get 10 most viewed posts of most recent 7 days.
     *
     * @param  Illuminate\Http\Request $request
     * @return Illuminate\Http\Response
     */
    public function getTrendingPosts(Request $request)
    {
        try {
            return response($this->postRepository->getTrendingPosts(), 200);
        } catch (Exception $e) {
            return response(null, 500);
        }
    }



    /**
     * Register a new post
     *
     * @param  Illuminate\Http\Request $request
     * @param  string $forum_name
     * @return Illuminate\Http\Response
     */
    public function createPost(Request $request, string $forum_name)
    {
        try {
            DB::beginTransaction();

            $post = $this->postRepository->createPost([
                'title' => $request->input('title'),
                'forum' => $forum_name,
                'author' => Auth::user()['username'],
                'view_count' => strval(0),
                'contents' => $request->input('content'),
            ]);

            $newTags = $request->input('tags');
            if (!is_array($newTags)) {
                $newTags = [];
            }

            foreach ($newTags as $newTag) {
                $tag = Tags::where('name', '=', (string) $newTag)->first();
                if ($tag == null) {
                    $tag = Tags::create([
                        'name' => $newTag,
                    ]);
                    $tag->save();
                }
                PostTags::create([
                    'post_id' => $post->id,
                    'tag_id' => $tag->id,
                ]);
            }
            DB::commit();
            return response([],200);
        } catch (Exception $e) {
            DB::rollback();
            Log::error($e->getMessage());
            return response([],500);
        }
    }

    /**
     * Get and return specified post.
     *
     * @param  string   $forum_name     // Forum where the post belongs to
     * @param  string   $post_id        // Post's unique reference ID
     * @return Illuminate\Http\Response(JSON)     {$post, $comments}  // Contents of the post and its comments
     */
    public function getPost(string $forum_name, string $post_id)
    {
        DB::beginTransaction();
        try {
            $post = ForumPost::where('id', '=', intval($post_id))
                ->where('forum', '=', $forum_name)
                ->first();

            $tags = [];
            $tagIdList = PostTags::where('post_id', '=', intval($post_id))
                ->get('tag_id')->pluck('tag_id');

            foreach ($tagIdList as $tagID) {
                $tag = Tags::where('id', '=', $tagID)->first()->name;
                array_push($tags, $tag);
            }

            $post->tags = $tags;

            $comments = ForumComment::where('post_id', '=', intval($post_id))
                ->get();
            foreach ($comments as $comment) {
                $comment->imageUrl = DB::table('users')
                    ->where('username', '=', $comment->author)
                    ->get('image_url')
                    ->first()
                    ->image_url;
            }

            $likes = DB::table('post_likes')
                ->where('post_id', '=', intval($post_id))
                ->count();

            $post->imageUrl = DB::table('users')
                ->where('username', '=', $post->author)
                ->get('image_url')
                ->first()
                ->image_url;

            if (Auth::check()) {
                $myLike = (boolean) DB::table('post_likes')
                    ->where('post_id', '=', intval($post_id))
                    ->where('uid', '=', Auth::id())
                    ->count();
            } else {
                $myLike = false;
            }

            ForumPost::where('id', '=', intval($post_id))
                ->increment('view_count');
        } catch (Exception $e) {
            DB::rollback();
            return $e;
        }

        DB::commit();

        return response(
            [
                'post' => $post,
                'tags' => $tags,
                'comments' => $comments,
                'likes' => $likes,
                'myLike' => $myLike,
            ]
        );

    }


    /**
     * Update forum post specified by post ID with new contents
     *
     * @param  Illuminate\Http\Request $request
     * @param  string $forum_name
     * @param  string $post_id
     * @return Illuminate\Http\Response
     */
    public function updatePost(Request $request, string $forum_name, string $post_id)
    {
        /* Null Check on title. Content is not null (some newline tags included by default). */
        if (empty($request->input('title'))) {
            return array(
                "result" => false,
                "message" => "Title is empty",
            );
        }

        try {
            $postAuthor = DB::table('posts')
                ->where('id', '=', intval($post_id))
                ->first();
            if ($postAuthor->author !== Auth::user()->username) {
                return json_encode(
                    array(
                        "result" => false,
                        "message" => "Only author can modify post",
                    )
                );
            }

            $result = DB::table('posts')
                ->where('id', '=', intval($post_id))
                ->update([
                    'title' => $request->input('title'),
                    'contents' => $request->input('content'),
                ]);
            return json_encode(array("result" => true));
        } catch (Exception $e) {
            return json_encode(
                array(
                    "result" => false,
                    "message" => $e,
                )
            );
        }
    }



    /**
     * Delete a forum post from database
     *
     * @param  string $forum_name
     * @param  string $post_id
     * @return Illuminate\Http\Response
     */
    public function deletePost(string $forum_name, string $post_id)
    {

        $postAuthor = ForumPost::where('id', '=', intval($post_id))
            ->first();

        if ($postAuthor->author !== Auth::user()->username) {
            return json_encode(
                array(
                    "result" => false,
                    "message" => "Only author can delete post",
                )
            );
        }

        try {
            $this->postRepository->deletePost((int)$post_id);
            return json_encode(["result" => true]);
        } catch (Exception $e) {
            return json_encode($e);
        }
    }


    /**
     * Get a comment forum specified by ID number
     *
     * @param  Illuminate\Http\Request  $request
     * @param  string   $comment_id
     * @return Illuminate\Http\Response
     */
    public function getComment(Request $request, string $comment_id)
    {
        try {
            $comment = $this->commentRepository->get($comment_id);
            return json_encode($comment);
        } catch (Exception $e) {
        }
    }



    /**
     * Create a comment for a forum post
     *
     * @param  Illuminate\Http\Request $request
     * @return Illuminate\Http\Response
     */
    public function postComment(Request $request)
    {
        try {
            $result = $this->commentRepository->create(
                author: (string) Auth::user()['username'],
                contents: (string) $request->input('content'),
                postID: (int) $request->input('post_id'),
            );
        } catch (Exception $e) {
            return $e;
        }
    }



    /**
     * Updates forum comment
     * Return error if current user is not the author of comment.
     *
     * @param  mixed $request
     * @param  mixed $comment_id
     * @return void
     */
    public function updateComment(Request $request, string $comment_id)
    {
        try {
            /* Check if the user is the author of the comment */
            $username = User::where('id', '=', Auth::id())
                            ->first()
                            ->username;
            $author = $this->commentRepository->getAuthor($comment_id);
            if ($username != $author)
                return response([], 401);

            $result = $this->commentRepository->update(
                id: (int)$comment_id,
                author: (string) Auth::user()['username'],
                contents: (string) $request->input('content'),
            );
            return json_encode(["result" => true]);
        } catch (Exception $e) {
            return $e;
        }
    }



    /**
     * Deletes forum comment.
     * Return error if current user is not the author of comment.
     *
     * @param  mixed $request
     * @param  mixed $comment_id
     * @return void
     */
    public function deleteComment(Request $request, string $comment_id)
    {
        try {
             /* Check if the user is the author of the comment */
            $username = User::where('id', '=', Auth::id())
                            ->first()
                            ->username;
            $author = $this->commentRepository->getAuthor($comment_id);
            if ($username != $author)
                return response([], 401);

            $result = $this->commentRepository->delete((int)$comment_id);
            return json_encode(["result" => true]);
        } catch (Exception $e) {
            return $e;

        }
    }




    /**
     * If user has not set like flag on a post, set like flag.
     * If user has set like flag, unset the like flag.
     *
     * @param  string $forum_name
     * @param  string $post_id
     * @return Illuminate\Http\Response
     */
    public function togglePostLike(string $forum_name, string $post_id)
    {
        /* Need to login to like a post */
        if (!Auth::check()) {
            return json_encode(['result' => false]);
        }

        /* True if current user already liked the post */
        $duplicateLike = (boolean) count($this->postLikeRepository->get(Auth::id(), $post_id));

        if ($duplicateLike) {
            $this->postLikeRepository->delete(Auth::id(), $post_id);
            $newCount = $this->postLikeRepository->getCount($post_id);
            return json_encode(['result' => true, 'myLike' => false, 'likes' => $newCount]);
        } else {
            $this->postLikeRepository->create(Auth::id(), $post_id);
            $newCount = $this->postLikeRepository->getCount($post_id);
            return json_encode(['result' => true, 'myLike' => true, 'likes' => $newCount]);
        }
    }
}
