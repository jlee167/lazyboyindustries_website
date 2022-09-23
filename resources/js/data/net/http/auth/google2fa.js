import AuthFailure from '../../../../exceptions/http/auth/AuthFailure';


/**
 * Authenticate user through Google 2FA (Google OTP)
 *
 * @param {String} secret
 * @returns
 */
async function verify2FaKey(secret) {
    return fetch('/auth/2fa', {
        method: 'post',

        body: JSON.stringify({
            "secret": secret
        }),

        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': window.env.CSRF_TOKEN
        }
    })
    .then(response => {
        if (response.status == 200) {
            return response.json();
        } else {
            throw new AuthFailure();
        }
    });
}


/**
 * Activate Google 2FA feature for current user.
 *
 * @param {String} secret
 * @returns
 */
async function activate2FaKey(secret) {
    return fetch('/auth/2fa/activate', {
        method: 'post',

        body: JSON.stringify({
            "secret": secret
        }),

        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': window.env.CSRF_TOKEN
        }
    })
    .then(response => {
        if (response.status == 200) {
            return Promise.resolve();
        } else {
            throw new AuthFailure();
        }
    });
}

export { verify2FaKey, activate2FaKey };
