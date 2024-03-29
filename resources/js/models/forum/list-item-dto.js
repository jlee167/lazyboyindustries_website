import DataTransferModel from "../../libs/model/dto-base";

class ListItemDTO extends DataTransferModel {

    id;
    forum;
    title;
    author;
    date;
    contents;
    likes;
    image_url;
    tags;
    view_count;
    comment_count;

    constructor(initData){
        super();
        super.verifyDTO(this, initData);
    }
}

export default ListItemDTO;
