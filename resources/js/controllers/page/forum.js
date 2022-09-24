import * as forumAPI from "../../data/net/http/forum/forum";
import ForumPost from "../../models/forum/post";
import ForumPostDTO from '../../models/forum/post-dto';
import EmailNotVerified from '../../exceptions/http/auth/email-not-verified';
import AuthFailure from '../../exceptions/http/auth/auth-failure';
import ListItem from '../../models/forum/list-item';
import ListItemDTO from '../../models/forum/list-item-dto';


/*------ Page Settings -------*/
const PAGINATION_CAPACITY = 10;
const HEADER_TOP_POSTS = "MOST VIEWED";
const HEADER_TRENDING_POSTS = "TRENDING POSTS";




/* -------------------------------------------------------------------------- */
/*                           Forum Apllication (Vue)                          */
/* -------------------------------------------------------------------------- */


window.forumApp = new Vue({
    el: '#contents-area',

    data: {
        /* App States */
        loaded: false,

        /* UI Strings */
        forumName: "General",
        topPostLabel: HEADER_TOP_POSTS,
        trendingPostLabel: HEADER_TRENDING_POSTS,

        /* Main UI (Posts) rendering data */
        posts: [],
        postID: null,
        currentPost: {},
        likes: null,
        myLike: null,
        comments: [],
        imageUrl: null,

        /* Pagnation data */
        searchKeyword: "",
        serachTag: "",
        pageCount: 0,
        currentPage: 0,
        pageIndexes: [],

        /* Rendering Section Selector */
        showForum: true,
        showPost: false,

        /* Side UI rendering data */
        trendingPosts: [],
        topPosts: [],

        forumList: ['General', 'Tech'],
        defaultForum: "General"

    },

    mounted: async function () {
        forumAPI.getTopPosts()
            .then(json => {
                for (let post of json) {
                    this.topPosts.push({
                        title: post.title,
                        date: post.date,
                        callback: this.watchPost,
                        id: post.id,
                        forum: post.forum
                    });
                }
            })
            .catch(err => {
                window.alert(`${err.name}: ${err.message}`);
            });

        forumAPI.getTrendingPosts()
            .then(json => {
                for (let post of json) {
                    this.trendingPosts.push({
                        title: post.title,
                        date: post.date,
                        callback: this.watchPost,
                        id: post.id,
                        forum: post.forum
                    });
                }
            })
            .catch(err => {
                window.alert(`${err.name}: ${err.message}`);
            });

        /* Get current page contents from server and render */
        this.getCurrentPage();
        //this.forumName = document.getElementById('forumName').value.trim();
        this.searchKeyword = '';
        await this.getPage();
        this.loaded = true;
    },

    computed: {
        hasComments: function () {
            return this.comments.length > 0;
        },
    },

    methods: {
        getPage: getPage,
        getNewPage: getNewPage,
        getPageWithTag: getPageWithTag,
        getOldestPage: getOldestPage,
        getNewestPage: getNewestPage,
        getCurrentPage: getCurrentPage,
        isCurrentPage: isCurrentPage,
        watchForum: watchForum,
        watchPost: watchPost,
        toggleLike: toggleLike,
        changeForum: changeForum,
        pagenate: pagenate,
        handleSearchEvent: handleSearchEvent,
        searchPosts: searchPosts,
        postComment: postComment,
        removeKeyword: removeKeyword,
    }
});




/* --------------------------- Method Definitions --------------------------- */

function isCurrentPage(pageIdx) {
    return pageIdx == this.currentPage;
}

function getNewPage(idx) {
    this.currentPage = idx;
    this.getPage();
}


function toggleLike() {
    forumAPI.toggleLike(this.currentPost.forum, this.currentPost.id)
        .then((json) => {
            this.myLike = json.myLike;
            this.likes = json.likes;
        })
        .catch(err => {
            if (err instanceof EmailNotVerified) {
                window.location.href = "/email/verify";
            } else if (err instanceof AuthFailure) {
                window.alert(`Please login first!`);
            } else {
                window.alert(`${err.name}: ${err.message}`);
            }
        });
}


/**
 * Transition from post view to page view
 */
function watchForum() {
    this.showPost = false;
    setTimeout(() => {
        this.showForum = true;
    }, 500);
}




/**
 * Transition from page view to post view
 *
 * @param {*} postID
 * @param {*} forum
 */
function watchPost(postID, forum = null) {

    if (!forum) {
        forum = this.forumName.trim();
    }

    forumAPI.getPost(postID, forum)
        .then(json => {
            this.currentPost = new ForumPost(new ForumPostDTO(json.post));
            this.comments = json.comments;
            this.likes = json.likes;
            this.myLike = json.myLike;
            this.showForum = false;
            this.postID = postID;
            setTimeout(() => {
                this.showPost = true;
            }, 500);
        })
        .catch(err => {
            window.alert(`${err.name}: ${err.message}`);
            console.trace();
        });
}


async function getPage() {
    let keyword;
    if (!this.searchKeyword) {
        keyword = "all";
    } else {
        keyword = this.searchKeyword;
    }

    await forumAPI.getPage(this.forumName, this.currentPage, keyword)
        .then(json => {
            // Remove old posts
            this.posts.splice(0, this.posts.length);

            for (let post of json.posts) {
                let entry = new ListItem(new ListItemDTO(post));
                this.posts.push(entry);
            }
            this.pagenate(json.itemCount);
        })
        .catch(err => {
            window.alert(`${err.name}: ${err.message}`);
            console.trace();
        });
}


async function getPageWithTag(page, tag) {
    await forumAPI.getPageWithTag(this.forumName, page, tag)
        .then(json => {
            // Remove old posts
            this.posts.splice(0, this.posts.length);

            for (let post of json.posts) {
                let entry = new ListItem(new ListItemDTO(post));
                this.posts.push(entry);
            }
            this.pagenate(json.itemCount);
        })
        .catch(err => {
            window.alert(`${err.name}: ${err.message}`);
            console.trace();
        });
}


function pagenate(itemCount) {
    /* Fix current page in center of 10 items in page list */
    this.pageCount = Math.ceil(itemCount / PAGINATION_CAPACITY);
    this.pageIndexes = [];

    if (this.pageCount === 0)
        return;

    let pagesLeft;
    let head, tail;

    if (this.pageCount < PAGINATION_CAPACITY)
        pagesLeft = this.pageCount - 1;
    else
        pagesLeft = PAGINATION_CAPACITY - 1;

    head = this.currentPage - Math.ceil(PAGINATION_CAPACITY / 2 - 1);
    tail = this.currentPage + Math.floor(PAGINATION_CAPACITY / 2);

    /* Check upper & lower bounds */
    if (head < 1) {
        let overflow = (1 - head);
        head = 1;
        tail = head + pagesLeft;
    }
    if (tail > this.pageCount) {
        let overflow = tail - this.pageCount;
        tail = this.pageCount;
        head = tail - pagesLeft;
    }

    for (let i = head; i <= tail; i++) {
        this.pageIndexes.push(i);
    }
}


function changeForum(forumName) {
    this.forumName = forumName;
    this.currentPage = 1;
    this.searchKeyword = 'all';
    this.getPage();
}


function postComment() {
    let comment = {
        content: this.$refs.summernote.getContent(),
        post_id: this.postID
    };

    forumAPI.postComment(comment)
        .then(() => {
            this.$refs.summernote.resetEditor();
            this.watchPost(this.postID);
        })
        .catch(err => {
            if (err instanceof EmailNotVerified) {
                window.location.href = "/email/verify";
            } else {
                window.alert(`${err.name}: ${err.message}`);
            }
        });
}


function handleSearchEvent() {
    this.searchPosts(this.$refs.searchInput.value);
}


function searchPosts(searchKeyword) {
    this.searchKeyword = searchKeyword;
    this.currentPage = 1;
    this.getPage();
}


function removeKeyword() {
    this.searchPosts("");
}


function getCurrentPage() {
    const urlParams = new URLSearchParams(window.location.search);
    this.currentPage = parseInt(urlParams.get('page'));
}

function getOldestPage() {
    this.currentPage = this.pageCount;
    this.getPage();
}


function getNewestPage() {
    this.currentPage = 1;
    this.getPage();
}

