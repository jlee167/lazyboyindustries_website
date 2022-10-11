import UnknownException from '../../../../exceptions/generic/unknown';
import AuthFailure from '../../../../exceptions/http/auth/auth-failure';
import UserNotAuthor from '../../../../exceptions/http/forum/auth-failure';
import EmailNotVerified from '../../../../exceptions/http/auth/email-not-verified';



/**
 * Match current post's author with current username.
 * Throws exception if user is not the post's author.
 *
 * @param       {*} post
 * @returns     {Promise}
 * @throws      {UserNotAuthor, AuthFailure}
 */
async function verifyAuthor(post) {
    return fetch("/self", {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': window.env.CSRF_TOKEN
        },
    })
    .then((res) => {
        switch (res.status) {
            case 200:
                return res.json();
            case 401:
                throw new AuthFailure();
            default:
                throw new UnknownException();
        }
    })
    .then((json) => {
        let user = json;
        if (user.username !== post.author) {
            throw new UserNotAuthor();
        }
        else {
            return Promise.resolve();
        }
    });
}


/* @Todo  Documentation */
async function updatePost(post) {
    return fetch(uri, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': window.env.CSRF_TOKEN
        },
        body: JSON.stringify({
            title: post.title,
            forum: post.forum,
            content: post.content,
        }),
    })
    .then((res) => {
        switch (res.status) {
            case 200:
                return Promise.resolve();
            case 401:
                throw new AuthFailure();
            default:
                throw new UnknownException();
        }
    });
}


/* @Todo Separate comment and post func */
async function deletePost(post) {
    const url = post.hasOwnProperty("title") ?
                `/forum/${post.forum}/post/${post.id}` :
                `/forum/comment/${post.id}`;

    return fetch (url, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': window.env.CSRF_TOKEN
        },
    })
    .then((res) => {
        switch (res.status) {
            case 200:
                return Promise.resolve();
            case 401:
                throw new AuthFailure();
            default:
                throw new UnknownException();
        }
    });
}


/**
 * Creates a new forum post
 *
 * @url     /forum/{forum_name}/post
 * @param   {Object} post
 * @param   {String} forum
 * @returns
 *
 * @todo    Update post type to ForumPost model
 */
async function createPost(post, forum) {
    return fetch (`/forum/${forum}/post`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': window.env.CSRF_TOKEN
        },
        body: JSON.stringify(post),
    })
    .then((res) => {
        switch (res.status) {
            case 200:
                return Promise.resolve();
            case 401:
                throw new AuthFailure();
            default:
                throw new UnknownException();
        }
    });
}


/**
 * Gets and returns a forum post
 *
 * @param   {Number} postID
 * @param   {String} forum
 * @returns {Promise}
 */
async function getPost(postID, forum) {
    return fetch(`/forum/${forum}/post/${String(postID).trim()}`, {
        method: 'get',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': window.env.CSRF_TOKEN
        }
    })
    .then(res => {
        switch (res.status) {
            case 200:
                return res.json();
            default:
                throw new UnknownException();
        };
    });
}


/**
 * Creates a forum comment
 *
 * @param   {Object} comment
 * @returns {Promise}
 */
async function postComment(comment) {
    return fetch('/forum/comment', {
        method: 'post',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': window.env.CSRF_TOKEN
        },
        body: JSON.stringify(comment),
    })
    .then(res => {
        switch (res.status) {
            case 200:
                return Promise.resolve();
            case 403:
                throw new EmailNotVerified();
            default:
                throw new UnknownException();
        };
    });
}


/**
 * Retrieve a page of forum post list based on search parameters
 *
 * @param {String} forum
 * @param {Number} page
 * @param {String} keyword
 * @returns
 */
async function getPage(forum, page, keyword) {
    return fetch(`/forum/${forum}/page/${String(page)}/${keyword}`, {
        method: 'get',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': window.env.CSRF_TOKEN,
            //'keyword': String(keyword),
        },
    })
    .then(res => {
        switch (res.status) {
            case 200:
                return res.json();
            default:
                throw new UnknownException();
        };
    })
}


async function getPageWithTag(forum, page, tag) {
    return fetch(`/forum/${forum}/page/${String(page)}/tag/${tag}`, {
        method: 'get',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': window.env.CSRF_TOKEN,
        },
    })
    .then(res => {
        switch (res.status) {
            case 200:
                return res.json();
            default:
                throw new UnknownException();
        };
    })
}


/**
 * Retrieves the most viewed forum posts
 *
 * @returns {Promise}
 */
async function getTopPosts() {
    return fetch('/forum/all_forums/top_posts', {
        method: 'get',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': window.env.CSRF_TOKEN
        },
    })
    .then(res => {
        switch (res.status) {
            case 200:
                return res.json();
            default:
                throw new UnknownException();
        };
    });
}


/**
 * Retrieves the most viewed forum posts of the week
 *
 * @returns {Promise}
 */
async function getTrendingPosts() {
    return fetch('/forum/all_forums/trending_posts', {
        method: 'get',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': window.env.CSRF_TOKEN
        },
    })
    .then(res => {
        switch (res.status) {
            case 200:
                return res.json();
            default:
                throw new UnknownException();
        };
    })
}


/**
 * Set or unset the like flag on a forum post
 *
 * @param {String} forum
 * @param {Number} postID
 * @returns
 */
async function toggleLike(forum, postID) {
    return fetch(`/forum/${forum}/post/${postID}/like`, {
        method: 'post',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': window.env.CSRF_TOKEN
        },
    })
    .then(res => {
        switch (res.status) {
            case 200:
                return res.json();
            case 401:
                throw new AuthFailure();
            case 403:
                throw new EmailNotVerified();
            default:
                throw new UnknownException();
        };
    });
}

export {verifyAuthor, createPost, updatePost, deletePost, getPost,
    postComment, getPage, getPageWithTag, getTopPosts, getTrendingPosts, toggleLike};
