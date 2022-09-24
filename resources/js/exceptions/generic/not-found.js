/* Generic exception thrown when a resource is not found */
class NotFound extends Error {
    constructor() {
        super();
        this.name = "Resource Not Found";
        this.message = "Requested Resource was not found!";
    }

    setMessage(message) {
        this.message = message;
    }
};

export default NotFound;
