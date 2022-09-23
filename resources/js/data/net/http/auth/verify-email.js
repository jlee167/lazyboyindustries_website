/**
 * Request server to send another email verification link
 *
 * @returns {Promise}
 */
export async function requestNewEmail() {
    return fetch('/email/resend', {
        method: 'GET',
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
                throw new AuthFailure();
            default:
                throw new UnknownException();
        }
    });
}
