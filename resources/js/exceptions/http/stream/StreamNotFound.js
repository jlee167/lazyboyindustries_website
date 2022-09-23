/* Exception thrown when requested stream does not exist */
class StreamNotFound extends Error {
    constructor() {
        super();
        this.name = "Stream Not Found";
        this.message = "Request stream does not exist";
    }
};

export default StreamNotFound;
