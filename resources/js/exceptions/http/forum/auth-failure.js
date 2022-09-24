/* Exception thrown when user is not author of current forum post */
class UserNotAuthor extends Error {
    constructor() {
        super();
        this.name = "User Not Author";
        this.message = "You are not the author of this post";
    }
};

export default UserNotAuthor;
