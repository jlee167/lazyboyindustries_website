import UnknownException from '../../exceptions/generic/UnknownException';
import InvalidQuantity from '../../exceptions/http/commerce/InvalidQuantity';
import DuplicateItem from '../../exceptions/http/commerce/DuplicateItem';
import AuthFailure from '../../exceptions/http/auth/AuthFailure';
import InsufficientStock from './../../exceptions/http/commerce/InsufficientStock';



/**
 * Send purchase request on an item for sale.
 *
 * @param   {Number} productID
 * @param   {Number} quantity
 * @returns {Promise}
 */
async function purchase (productID, quantity) {
    return fetch('/product/order', {
        method: 'POST',
        body: JSON.stringify({
            productID : productID,
            quantity : quantity
        }),
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': window.env.CSRF_TOKEN
        }
    })
    .then(res => {
        switch (res.status) {
            case 200:
                return res.json();
            default:
                throw new UnknownException();
        }
    })
}


/**
 * Add a commercial item to user's cart
 *
 * @param   {Number} productID
 * @param   {Number} quantity
 * @returns {Promise}
 */
async function addToCart(productID, quantity) {

    /* Quantity integrity check */
    const isInteger = quantity.isInteger();
    const isPosNum = !(quantity < 1);
    if (!(isInteger && isPosNum))
        return new Promise(() => {
            throw new InvalidQuantity();
        });


    return fetch('/product/cart/item', {
        method: 'POST',
        body: JSON.stringify({
            productID : productID,
            quantity : quantity
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
            case 400:
                throw new DuplicateItem();
            case 404:
                throw new InsufficientStock();
            default:
                throw new UnknownException();
        }
    });
}


/**
 * Retrieve product information
 *
 * @param {Number} productID
 * @returns
 */
async function getProduct(productID) {
    return fetch('/product/info/' + productID, {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': window.env.CSRF_TOKEN
        }
    })
    .then(res => {
        switch (res.status) {
            case 200:
                return res.json();
            case 404:
                throw new Error(`${res.status}: Product not found!`);
            default:
                throw new Error(`${res.status}: Unknown server error. Please contact admin`);
        }
    })
}


/**
 * Creates a review on a product
 *
 * @param   {Object} review
 * @returns {Promise}
 */
async function postReview(review) {
    return fetch('/product/review', {
        method: 'post',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': window.env.CSRF_TOKEN
        },
        body: JSON.stringify({
            purchaseID: review.purchaseID,
            value: review.value,
            comment: review.comment,
        }),
    })
    .then(res => {
        switch (res.status) {
            case 200:
                return Promise.resolve();
            case 400:
                return res.json();
            default:
                throw new Error(`${res.status}: Unknown server error. Please contact admin`);
        }
    })
    .then((json) => {
        if (json) {
            throw new Error(json.error);
        } else {
            return Promise.resolve();
        }
    });
}


/**
 * Retrieve current user's purchase history
 *
 * @returns
 */
async function getRecords() {
    return fetch('/product/order/history', {
        method: 'GET',
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
                throw new Error(`${res.status}: Unknown server error. Please contact admin`);
        }
    });
}


export {purchase, addToCart, getProduct, postReview, getRecords};
