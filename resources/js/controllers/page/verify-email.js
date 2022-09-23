import * as VerifyEmailAPI from "../../data/net/http/auth/verify-email";


/* Request server to send a new email verification link */
window.requestNewEmail = () => {
    VerifyEmailAPI.requestNewEmail()
    .then(() => {
        window.alert("New verification link sent to your email!");
    })
    .catch(err => {
        window.alert(`${err.name}: ${err.message}`);
    });
}
