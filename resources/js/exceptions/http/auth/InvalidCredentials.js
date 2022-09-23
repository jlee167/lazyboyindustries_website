/* Exception for invalid autnentication credentials (username, password, or token) */
class InvalidCredentials extends Error {
    constructor() {
        super();
        this.name = "Invalid Credentials";
        this.message = "Login Failed!";
    }
};

export default InvalidCredentials;
