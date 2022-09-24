/* Exception thrown when specified user does not exist */
class UserNotFound extends Error {
    constructor() {
        super();
        this.name = "User search exception";
        this.message = "User not found";
    }
};

export default UserNotFound;
