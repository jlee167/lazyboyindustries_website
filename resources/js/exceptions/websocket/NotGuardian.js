/* Exception thrown when current user is not a guardian of the streamer */
class NotGuardian extends Error {
    constructor() {
        super();
        this.name = "Not Guaridan";
        this.message = "You are not guardian of this user!";
    }
};

export default NotGuardian;
