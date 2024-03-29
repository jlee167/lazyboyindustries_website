import DataTransferModel from "../../libs/model/dto-base";

class ForumCommentDTO extends DataTransferModel {

    id;
    author;
    date;
    contents;
    post_id;
    parent_id;
    depth;
    imageUrl;
    likes;
    mylike;

    constructor(initData){
        super();
        super.verifyDTO(this, initData);
    }
}

export default ForumCommentDTO;
