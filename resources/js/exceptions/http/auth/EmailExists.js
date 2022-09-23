/* Exception thrown during registration when the email is already being used */
class EmailExists extends Error {
    constructor() {
        super();
        this.name = "Email Exists";
        this.message = "Already registered email";
    }
};

export default EmailExists;
