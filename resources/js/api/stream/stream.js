
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


export {getStreamToken};


