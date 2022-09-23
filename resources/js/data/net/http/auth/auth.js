/* ----------------------------- Login / Logout ----------------------------- */
import UnknownException from '../../../../exceptions/generic/UnknownException';
import InvalidCredentials from '../../../../exceptions/http/auth/InvalidCredentials';
import LogoutFailure from '../../../../exceptions/http/auth/LogoutFailure';
import UserSearchException from '../../../../exceptions/http/user/UserSearchException';
import AuthFailure from '../../../../exceptions/http/auth/AuthFailure';


/**
 * Get authorized with OAuth2
 * Providers are OAuth2 providers like Google and Kakao
 *
 * @param {String} accessToken
 * @param {String} provider
 */
async function authWithOauth (accessToken, provider) {
    let authUri = '/auth/' + provider;

    return fetch(authUri, {
        method: 'post',

        body: JSON.stringify({
            "accessToken": accessToken
        }),

        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': window.env.CSRF_TOKEN
        }
    })

    .then(response => {
        switch (response.status) {
            case 200:
                return Promise.resolve();
            case 401:
                throw new InvalidCredentials();
            case 404:
                throw new UserSearchException();
            default:
                throw new UnknownException();
        };
    });
}



/**
 * Login with username and password
 *
 * @param {String} username
 * @param {String} password
 */
async function authWithUsername (credentials) {

    return fetch('/auth', {
        method: 'post',

        body: JSON.stringify({
            "username": credentials.username,
            "password": credentials.password
        }),

        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': window.env.CSRF_TOKEN
        }
    })
    .then(res => {
        switch (res.status) {
            case 200:
                return Promise.resolve();
            case 401:
                throw new InvalidCredentials();
            default:
                throw new UnknownException();
        }
    });
}


/**
 * Logout current user
 *
 * @returns
 */
async function logout() {
    return fetch('/logout', {
        method: 'post',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': window.env.CSRF_TOKEN
        }
    })
    .then(res => {
        switch (res.status) {
            case 200:
                return Promise.resolve();
            default:
                throw new LogoutFailure();
        }
    });
}



/* ------------------------------ Registration ------------------------------ */

/**
 * Send user registration request to the server.
 *
 * @param {String} user
 * @param {String} redirectUrl
 */
async function sendRegisterRequest (user) {

    return fetch(`/members/${user.username}`, {
        method: 'POST',
        body: JSON.stringify(user),
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': window.env.CSRF_TOKEN,
            'Accept'        : 'application/json'
        }
    })
    .then(res => {
        switch (res.status) {
            case 200:
                return Promise.resolve();
            case 401:
                throw new AuthFailure();
            case 409:
                return res.json();
            default:
                throw new UnknownException();
        }
    })
    .then((json) => {
        if (json)   {throw new Error(json.error);}
        else        {return Promise.resolve();}
    });
}






export {authWithOauth, authWithUsername, logout, sendRegisterRequest};
