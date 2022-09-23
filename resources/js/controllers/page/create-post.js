import {createPost} from "../../data/net/http/forum/forum";

/* Vue application for page */
window.app = new Vue({
    el: "#createPostApp",
    data: {
        tags: new Array(),
    },
    methods: {
        submitPost: submitPost,
    }
});


/* Creates a forum post */
function submitPost() {
    const forum = this.$refs.forumName.value;
    const post = {
        title: this.$refs.title.value,
        tags: this.tags,
        content: this.$refs.summernote.getContent(),
    };

    createPost(post, forum)
    .then(() => {
        window.location.href = "/views/dashboard?page=1";
    })
    .catch(err => {
        window.alert(`${err.name}: ${err.message}`);
        console.trace();
    });
}
