/* Exception thrown when authentication fails */
class AuthFailure extends Error {
    constructor() {
        super();
        this.name = "Auth Failure";
        this.message = "Authentication Failed!";
    }
};

export default AuthFailure;
