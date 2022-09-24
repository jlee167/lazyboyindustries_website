/* Error thrown during registration when requested username is already used */
class UsernameExists extends Error {
    constructor() {
        super();
        this.name = "Username Exists";
        this.message = "Another user is using this username";
    }
};

export default UsernameExists;
