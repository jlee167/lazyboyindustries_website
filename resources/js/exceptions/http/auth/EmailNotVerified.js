/*  Exception thrown during authentication when user's email
    has not been verified */
class EmailNotVerified extends Error {
    constructor() {
        super();
        this.name = "Email not verified";
        this.message = "Please verify your email";
    }
};

export default EmailNotVerified;
