/* Authentication failure on WebSocket channel */
class StreamAuthFailure extends Error {
    constructor() {
        super();
        this.name = "Stream Auth Failed";
        this.message = "Authentication failed with given JWT token";
    }
};

export default StreamAuthFailure;
