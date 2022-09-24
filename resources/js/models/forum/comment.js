class ForumComment {

    postID;
    contents;

    constructor(DTO){
        this.postID = DTO.post_id;
        this.title = DTO.title;
    }
}

export default ForumComment;
