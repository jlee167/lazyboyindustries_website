import UnknownException from '../../../../exceptions/generic/unknown';


/**
 * Sends new customer support request
 *
 * @url         /support_request
 * @param       {Object} req
 * @returns     {Promise}
 */
async function submitRequest(req) {
    return fetch('/support_request', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': window.env.CSRF_TOKEN
        },
        body: JSON.stringify({
            "type": String(req.type),
            "contents": req.text,
            "contact": req.contact
        })
    })
    .then(res => {
        switch (res.status) {
            case 200:
                resolve();
            default:
                throw new UnknownException();
        }
    });
}

export {submitRequest};
