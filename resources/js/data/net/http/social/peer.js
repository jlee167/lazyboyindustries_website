import AuthFailure from '../../../../exceptions/http/auth/AuthFailure';
import UnknownException from '../../../../exceptions/generic/UnknownException';
import { PAGENATION_CAPACITY } from "../../../../configs/peers/settings";

/**
 * Get the list of current user's guardians
 *
 * @url         /members/guardian/all
 * @return      {Promise}
 */
async function getGuardians() {
    return fetch(`/members/guardian/all`, {
        method: 'get',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': window.env.CSRF_TOKEN
        }
    })
        .then(res => {
            switch (res.status) {
                case 200:
                    return res.json();
                case 401:
                    throw new AuthFailure();
                default:
                    throw new UnknownException();
            }
        });
}


/**
 * Get the list of current user's protecteds
 *
 * @url         /members/protected/all
 * @return      {Promise}
 */
async function getProtecteds(page) {
    return fetch(`/members/protected/all`, {
        method: 'get',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': window.env.CSRF_TOKEN
        }
    })
        .then(res => {
            switch (res.status) {
                case 200:
                    return res.json();
                case 401:
                    throw new AuthFailure();
                default:
                    throw new UnknownException();
            }
        });
}


/**
 * Send a pledge to be another user's guardian
 *
 * @url         /members/protected/{username}
 * @param       {String} username
 * @return      {Promise}
 */
async function addProtected(username) {
    return fetch(`/members/protected/${username}`, {
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
                case 401:
                    throw new AuthFailure();
                default:
                    return res.json();
            }
        })
        .then(json => {
            if (json) {
                throw new Error(json.error); //@Todo: make exception
            } else {
                return Promise.resolve();
            }
        });
}


/**
 * Send a request to another user to be current user's guardian
 *
 * @url         /members/guardian/{username}
 * @param       {String} username
 * @return      {Promise}
 */
async function addGuardian(username) {
    return fetch(`/members/guardian/${username}`, {
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
                case 401:
                    throw new AuthFailure();
                default:
                    return res.json();
            }
        })
        .then(json => {
            if (json) {
                throw new Error(json.error); //@Todo: make exception
            } else {
                return Promise.resolve();
            }
        });
}


/* Get protecteds list and pass to Vue app */
async function respondPeerRequest(requestID, response) {
    return fetch('/peer_request', {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': window.env.CSRF_TOKEN
        },
        body: JSON.stringify({
            requestID: requestID,
            response: response
        })
    })
        .then(res => {
            switch (res.status) {
                case 200:
                    return Promise.resolve();
                case 401:
                    throw new AuthFailure();
                default:
                /* @Todo */;
            }
        });
}


/* Get protecteds list and pass to Vue app */
async function getPendingRequests() {
    return fetch('/pending_requests', {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': window.env.CSRF_TOKEN
        },
    })
        .then(res => {
            switch (res.status) {
                case 200:
                    return res.json();
                case 401:
                    throw new AuthFailure();
                default:
                /* @Todo */;
            }
        });
}


export { getGuardians, getProtecteds, addGuardian, addProtected, respondPeerRequest, getPendingRequests };



