/* Exception thrown when unexpected exception occurs */
class UnknownException extends Error {
    constructor() {
        super();
        this.name = "Undefiend Exception";
        this.message = "Undefined server error occured!";
    }
};

export default UnknownException;
