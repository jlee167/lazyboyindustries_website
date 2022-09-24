/* Exception thrown when logout request returns http error code */
class LogoutFailure extends Error {
    constructor() {
        super();
        this.name = "Logout Exception";
        this.message = "Logout Failed!";
    }
};

export default LogoutFailure;
