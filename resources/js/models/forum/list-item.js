class ListItem {

    id;
    title;
    author;
    contents;
    date;
    forum;
    imageUrl;
    tags;
    likes;
    viewCount;
    commentCount;

    constructor(DTO){
        this.id = DTO.id;
        this.title = DTO.title;
        this.author = DTO.author;
        this.contents = DTO.contents;
        this.date = DTO.date;
        this.forum = DTO.forum;
        this.imageUrl = DTO.image_url;
        this.tags = DTO.tags;
        this.likes = DTO.likes;
        this.viewCount = DTO.view_count;
        this.commentCount = DTO.comment_count;
    }
}

export default ListItem;
