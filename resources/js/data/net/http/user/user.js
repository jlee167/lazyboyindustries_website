import AuthFailure from '../../../../exceptions/http/auth/auth-failure';
import UnknownException from '../../../../exceptions/generic/unknown';


/**
 * Get profile image url of current user.
 *
 * @url         /self/profile_image
 * @returns     {Promise}
 */
async function getSelfImage() {
    return fetch('/self/profile_image', {
        method: 'get',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': window.env.CSRF_TOKEN
        },
    })
        .then(res => {
            switch (res.status) {
                case 200:
                    return res.json();
                case 204:
                    throw new AuthFailure();
                default:
                    throw new UnknownException();
            };
        });
}


/**
 * Send new user registration request
 *
 * @url         /members/{username}
 * @param       {String} user
 * @returns     {Promise}
 */
async function registerUser(user) {

    return fetch(`/members/${user.username}`, {
        method: 'post',
        body: JSON.stringify(user),
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': window.env.CSRF_TOKEN,
            'Accept': 'application/json'
        }
    })
        .then(res => {
            if (res.status === 200) {
                return Promise.resolve({error: null});
            }
            else if ([409].includes(res.status)){
                return res.json();
            }
            else if ([422, 500].includes(res.status)) {
                throw new Error(`Error: Status Code ${res.status}`);
                //res.json().then(jsonData => {
                //    throw new Error(`Error: ${jsonData.error}`);
                //});
            }
            else {
                throw new Error(`Registration Failed! Response Code: ${String(res.status)}`);
            }
        })
        .then(json => {
            if (json.error)
                throw new Error(`Error: ${json.error}`);
            else
                return Promise.resolve();
        });
}

export { getSelfImage, registerUser };
