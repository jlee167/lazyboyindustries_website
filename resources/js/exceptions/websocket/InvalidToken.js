/* Exception thrown when JWT token could not be verified by server */
class InvalidToken extends Error {
    constructor() {
        super();
        this.name = "Invalid Token";
        this.message = "Your token could not be validated";
    }
};

export default InvalidToken;
