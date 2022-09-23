/* Exception thrown when JWT token has not been issued for requested stream  */
class TokenNotFound extends Error {
    constructor() {
        super();
        this.name = "Token Not Found";
        this.message = "Could not obtain security token for this channel.";
    }
};

export default TokenNotFound;
