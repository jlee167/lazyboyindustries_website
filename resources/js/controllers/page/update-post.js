const urlParams = new URLSearchParams(window.location.search);
const postID = urlParams.get("post_id");
const forum = urlParams.get("forum");
const postType = urlParams.get("post_type");

const SUMMERNOTE_TABSIZE = 2;
const SUMMERNOTE_HEIGHT_PX = 400;

const URI_UPDATE_POST = `/forum/${forum}/post/${postID}`;
const URI_UPDATE_COMMENT = `/forum/comment/${postID}`;
const uri = isPost() ? URI_UPDATE_POST : URI_UPDATE_COMMENT;


function isPost() {
    return postType === "post";
}

if (!isPost()) {
    document.getElementById("titleLabel").style.display = "none";
    document.getElementById("title").style.display = "none";
}


/* ----------------------------- Update Post API ---------------------------- */
window.submitPost = () => {

    fetch(uri, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': window.env.CSRF_TOKEN
        },
        body: JSON.stringify({
            title: document.getElementById('title').value,
            forum: forum,
            content: $('#postContent').summernote('code')
        }),
    })
    .then((res) => {
        if (res.status == 200) {
            return res.json();
        }
    })
    .then((json) => {
        const result = json.result;
        if (Boolean(result)) {
            window.location.href = "http://www.lazyboyindustries.com/views/dashboard?page=1";
        }
        else {
            window.alert(result.message);
        }
    })
    .catch((err) => {
        console.error(err.message);
    });
}




function getPost() {
    fetch(uri, {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': window.env.CSRF_TOKEN
        },
    })
    .then((res) => {
        if (res.status == 200) {
            return res.json();
        }
    })
    .then((json) => {
        let post;

        if (postType === "post") {
            post = json.post;
        }
        else if (postType === "comment") {
            post = json;
        }
        else {
            throw new Error("Post Type Error1")
        }
        $("#postContent").summernote('code', post.contents);

        if (postType === "post")
            document.getElementById('title').value = post.title;
    })
    .catch((err) => {
        console.error(err.message);
    });
}


function initSummernote() {
    $('#postContent').summernote({
        placeholder: 'content',
        tabsize: SUMMERNOTE_TABSIZE,
        height: SUMMERNOTE_HEIGHT_PX,
        lineWrapping: true
    });
};



/* -------------------------------- Page Init ------------------------------- */
initSummernote();
getPost();
