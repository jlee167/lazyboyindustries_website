import NotFound from './../../../../exceptions/generic/not-found';
import StreamNotFound from './../../../../exceptions/http/stream/stream-not-found';
import TokenNotFound from './../../../../exceptions/http/auth/token-not-found';

/**
 * Get JWT token for specified stream
 *
 * @url         /{stream_id}/webtoken
 * @returns     {Promise}
 */
async function getStreamToken() {
    return fetch(`/${streamID}/webtoken`, {
            method: 'get',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': window.env.CSRF_TOKEN
            }
        })
        .then(response => {
            if (response.status == 200) {
                return response.json();
            } else {
                throw new Error();
            }
        })
}



async function getLocation(streamURL, streamPort, streamID) {
    return fetch(`${streamURL}:${streamPort}/stream/${streamID}/geo`, {
        method: 'get'
    })
        .then((res) => {
            if (res.status != 200) {
                let err = NotFound();
                err.setMessage("Location data request returned 404");
                throw err;
            }
            else
                return res.json();
        });
}



async function getStream(streamURL, streamPort, streamID, token){
    return fetch(`${streamURL}:${streamPort}/stream/${streamID}`, {
        method: 'get',
        headers: {
            'Content-Type': 'application/json',
            'webToken': token
        },
    })
        .then(response => {
            if (response.status == 200) {
                return response.json();
            } else {
                throw new StreamNotFound();
            }
        });
}


async function getToken(streamID){
    return fetch(`/${streamID}/webtoken`, {
        method: 'get',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': window.env.CSRF_TOKEN
        }
    })
        .then(response => {
            if (response.status == 200) {
                return response.json();
            } else {
                throw new TokenNotFound();
            }
        });
}

export {getStreamToken, getLocation, getStream, getToken};


