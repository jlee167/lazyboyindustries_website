/* Exception thrown when specified user does not exist */
class UserSearchException extends Error {
    constructor() {
        super();
        this.name = "User search exception";
        this.message = "User not found";
    }
};

export default UserSearchException;
