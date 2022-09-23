/******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};
/******/
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/
/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId]) {
/******/ 			return installedModules[moduleId].exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = installedModules[moduleId] = {
/******/ 			i: moduleId,
/******/ 			l: false,
/******/ 			exports: {}
/******/ 		};
/******/
/******/ 		// Execute the module function
/******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);
/******/
/******/ 		// Flag the module as loaded
/******/ 		module.l = true;
/******/
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/
/******/
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = modules;
/******/
/******/ 	// expose the module cache
/******/ 	__webpack_require__.c = installedModules;
/******/
/******/ 	// define getter function for harmony exports
/******/ 	__webpack_require__.d = function(exports, name, getter) {
/******/ 		if(!__webpack_require__.o(exports, name)) {
/******/ 			Object.defineProperty(exports, name, { enumerable: true, get: getter });
/******/ 		}
/******/ 	};
/******/
/******/ 	// define __esModule on exports
/******/ 	__webpack_require__.r = function(exports) {
/******/ 		if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 			Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 		}
/******/ 		Object.defineProperty(exports, '__esModule', { value: true });
/******/ 	};
/******/
/******/ 	// create a fake namespace object
/******/ 	// mode & 1: value is a module id, require it
/******/ 	// mode & 2: merge all properties of value into the ns
/******/ 	// mode & 4: return value when already ns object
/******/ 	// mode & 8|1: behave like require
/******/ 	__webpack_require__.t = function(value, mode) {
/******/ 		if(mode & 1) value = __webpack_require__(value);
/******/ 		if(mode & 8) return value;
/******/ 		if((mode & 4) && typeof value === 'object' && value && value.__esModule) return value;
/******/ 		var ns = Object.create(null);
/******/ 		__webpack_require__.r(ns);
/******/ 		Object.defineProperty(ns, 'default', { enumerable: true, value: value });
/******/ 		if(mode & 2 && typeof value != 'string') for(var key in value) __webpack_require__.d(ns, key, function(key) { return value[key]; }.bind(null, key));
/******/ 		return ns;
/******/ 	};
/******/
/******/ 	// getDefaultExport function for compatibility with non-harmony modules
/******/ 	__webpack_require__.n = function(module) {
/******/ 		var getter = module && module.__esModule ?
/******/ 			function getDefault() { return module['default']; } :
/******/ 			function getModuleExports() { return module; };
/******/ 		__webpack_require__.d(getter, 'a', getter);
/******/ 		return getter;
/******/ 	};
/******/
/******/ 	// Object.prototype.hasOwnProperty.call
/******/ 	__webpack_require__.o = function(object, property) { return Object.prototype.hasOwnProperty.call(object, property); };
/******/
/******/ 	// __webpack_public_path__
/******/ 	__webpack_require__.p = "/";
/******/
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = 22);
/******/ })
/************************************************************************/
/******/ ({

/***/ "./resources/js/page-specific/forum.js":
/*!*********************************************!*\
  !*** ./resources/js/page-specific/forum.js ***!
  \*********************************************/
/*! no static exports found */
/***/ (function(module, exports) {

/* ------------------------------ Page Settings ----------------------------- */
var PAGINATION_CAPACITY = 10;
/* ---------------------------- Helper Functions ---------------------------- */

function firstCharToUpper(input) {
  return input.charAt(0).toUpperCase() + input.slice(1);
}

function xhttpRequest(reqType, uri, data, callback) {
  var req = new XMLHttpRequest();
  req.open(reqType, uri);
  req.setRequestHeader('Content-Type', 'application/json');
  req.setRequestHeader('X-CSRF-TOKEN', csrf);

  req.onload = function () {
    callback(req);
  };

  req.send(data);
}
/* -------------------------------------------------------------------------- */

/*                           Forum Apllication (Vue)                          */

/* -------------------------------------------------------------------------- */


forumApp = new Vue({
  el: '#contents-area',
  data: {
    /* UI Strings */
    forum_name: document.getElementById('forum_name').value.trim(),
    title_top_posts: "MOST VIEWED",
    title_trending_posts: "TRENDING POSTS",

    /* Main UI (Posts) rendering data */
    posts: [],
    post_id: null,
    original_post: {},
    likes: null,
    myLike: null,
    comments: [],
    imageUrl: null,
    toggleLike: function toggleLike() {
      var likeRequest = new XMLHttpRequest();
      likeRequest.open("POST", "/forum/" + forumApp.original_post.forum + "/post/" + forumApp.original_post.id + "/like", true);
      likeRequest.setRequestHeader("Content-Type", "application/json");
      likeRequest.setRequestHeader("X-CSRF-TOKEN", csrf);

      likeRequest.onload = function () {
        var res = JSON.parse(likeRequest.responseText);

        if (res.result == true) {
          forumApp.myLike = res.myLike;
          forumApp.likes = res.likes;
        } else {
          window.alert("Please login first");
        }
      };

      likeRequest.send();
    },

    /* Pagnation data */
    search_keyword: 'all',
    post_count: 0,
    page_count: 0,
    current_page: 0,
    page_index: [],

    /* Rendering Section Selector */
    show_forum: true,
    show_post: false,
    transToForum: transToForum,
    changeForum: changeForum,
    searchPosts: searchPosts,
    firstCharToUpper: firstCharToUpper,
    getPage: getPage,

    /* Callbacks */
    post_click_callback: transToPost,
    post_action: postComment,
    redirect_auth: redirectAuth,

    /* Side UI rendering data */
    trending_posts: [],
    top_posts: []
  },
  mounted: function mounted() {},
  computed: {
    url_gen: function url_gen() {
      var url_arr = [];

      for (elem in page_index) {
        url_arr.push('dashboard?page=' + elem);
      }

      return url_arr;
    }
  },
  methods: {
    getNewPage: function getNewPage(idx) {
      forumApp.forum_name = document.getElementById('forum_name').value.trim();
      forumApp.current_page = idx;
      getPage(forumApp.forum_name, forumApp.current_page, this.search_keyword);
    }
  }
});
/**
 * Transition from post view to page view
 */

function transToForum() {
  forumApp.show_post = false;
  setTimeout(function () {
    forumApp.show_forum = true;
  }, 500);
}

function redirectAuth() {
  /* @Todo: remove hard-coded url */
  window.location.href = "http://www.lazyboyindustries.com/views/login";
}
/**
 * Transition from page view to post view
 *
 * @param {*} post_id
 * @param {*} forum
 */


function transToPost(post_id) {
  var forum = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : null;

  if (!forum) {
    forum = document.getElementById('forum_name').value.trim();
  }

  xhttpRequest('GET', '/forum/' + forum + '/post/' + String(post_id).trim(), null, function (req) {
    var resp = JSON.parse(req.responseText);
    console.log(resp);
    forumApp.original_post = resp.post;
    forumApp.comments = resp.comments;
    forumApp.likes = resp.likes;
    forumApp.myLike = resp.myLike;
    forumApp.show_forum = false;
    forumApp.post_id = post_id; //forumApp.imageUrl = resp.imageUrl;
    //console.log(forumApp.imageUrl);

    setTimeout(function () {
      forumApp.show_post = true;
    }, 500);
  });
}

function getPage(forum, page, keyword) {
  if (keyword === "") {
    keyword = "all";
  }

  xhttpRequest('GET', '/forum/' + forum + '/page/' + String(page) + '/' + String(keyword), JSON.stringify({
    response: true
  }), function (req) {
    //console.log(req.responseText)
    var res = JSON.parse(req.responseText);
    console.log(res);
    forumApp.posts = [];

    for (post in res.posts) {
      forumApp.posts.push(res.posts[post]);
    }

    pagenate(res.itemCount);
  });
}

function pagenate(itemCount) {
  /* Fix current page in center of 10 items in page list */
  forumApp.page_count = Math.ceil(itemCount / PAGINATION_CAPACITY);
  if (forumApp.page_count === 0) return;
  var pagesLeft;
  var head, tail;
  if (forumApp.page_count < PAGINATION_CAPACITY) pagesLeft = forumApp.page_count - 1;else pagesLeft = PAGINATION_CAPACITY - 1;
  head = forumApp.current_page - Math.ceil(PAGINATION_CAPACITY / 2 - 1);
  tail = forumApp.current_page + Math.floor(PAGINATION_CAPACITY / 2);
  /* Check upper & lower bounds */

  if (head < 1) {
    var overflow = 1 - head;
    head = 1;
    tail = head + pagesLeft;
  }

  if (tail > forumApp.page_count) {
    var _overflow = tail - forumApp.page_count;

    tail = forumApp.page_count;
    head = tail - pagesLeft;
  }

  forumApp.page_index = [];

  for (var i = head; i <= tail; i++) {
    forumApp.page_index.push(i);
  }
}

function changeForum() {
  forumApp.forum_name = document.getElementById('forum_name').value.trim();
  getPage(forumApp.forum_name, 1, 'all');
  forumApp.current_page = 1;
}

function postComment(post_id, content) {
  var comment = {
    content: content,
    post_id: post_id
  };
  xhttpRequest('POST', '/forum/comment', JSON.stringify(comment), function (req) {
    transToPost(post_id);
  });
}

function searchPosts(search_keyword) {
  forumApp.search_keyword = search_keyword;
  getPage(forumApp.forum_name, 1, search_keyword);
}
/* -------------------------------------------------------------------------- */

/*                          /Forum Apllication (Vue)                          */

/* -------------------------------------------------------------------------- */

/* -------------------------------------------------------------------------- */

/*                             Page Initialization                            */

/* -------------------------------------------------------------------------- */


var urlParams = new URLSearchParams(window.location.search);
xhttpRequest('GET', '/forum/all_forums/top_posts', null, function (req) {
  var res = JSON.parse(req.responseText);

  for (var iter = 0; iter < res.length; iter++) {
    forumApp.top_posts.push({
      title: res[iter].title,
      date: res[iter].date,
      callback: transToPost,
      id: res[iter].id,
      forum: res[iter].forum
    });
  }
});
xhttpRequest('GET', '/forum/all_forums/trending_posts', null, function (req) {
  var res = JSON.parse(req.responseText);

  for (var iter = 0; iter < res.length; iter++) {
    forumApp.trending_posts.push({
      title: res[iter].title,
      date: res[iter].date,
      callback: transToPost,
      id: res[iter].id,
      forum: res[iter].forum
    });
  }
});
forumApp.current_page = parseInt(urlParams.get('page'));
getPage(document.getElementById('forum_name').value.trim(), forumApp.current_page, 'all');
/* -------------------------------------------------------------------------- */

/*                            /Page Initialization                            */

/* -------------------------------------------------------------------------- */

/***/ }),

/***/ 22:
/*!***************************************************!*\
  !*** multi ./resources/js/page-specific/forum.js ***!
  \***************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(/*! /home/admin/LazyWeb/lazyweblaravel/resources/js/page-specific/forum.js */"./resources/js/page-specific/forum.js");


/***/ })

/******/ });