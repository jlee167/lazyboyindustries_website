import DataTransferModel from "../base/DataTransferModel";

class ForumPostDTO extends DataTransferModel {

    id;
    forum;
    title;
    author;
    date;
    contents;
    likes;
    imageUrl;
    tags;
    view_count;
    comment_count;

    constructor(initData){
        super();
        super.verifyDTO(this, initData);
    }
}

export default ForumPostDTO;
