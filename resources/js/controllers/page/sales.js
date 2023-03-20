import { getProduct as getProductAPI} from "../../data/net/http/commerce/commerce";
import AuthFailure from "../../exceptions/http/auth/auth-failure";

/* URL parts */
const uriArr = window.location.href.split('/');

/* Current product's ID */
const productID = Number(uriArr[uriArr.length - 1]);

/* API Handles */
const commerceAPI = {
    getProduct: getProductAPI,
}


/* -------------------------------------------------------------------------- */
/*                                   Vue App                                  */
/* -------------------------------------------------------------------------- */

/* Vue app instance */
window.salesApp = new Vue({
    el: "#sales",
    data: {
        title: String('title'),
        description: String('desc'),
        price: Number(0),
        stock: Number(0),
        averageRating: Number(0),
        reviewCount: Number(0),
        imgUrl: String(),
        purchase: Function(),
        addToCart: addToCart,
        productID: productID,
        quantity: Number(0),
    },
    methods: {
        getProduct: getProduct,
    },
    mounted() {
        this.getProduct();
        switch(this.productID) {
            case 1:
                this.imgUrl = "/images/product_usb.jpg"
                break;
            case 2:
                this.imgUrl = "/images/product_wifi.jpg"
                break;
            case 3:
                this.imgUrl = "/images/product_lte.jpg"
                break;
            case 4:
                this.imgUrl = "/images/product_fpga.jpg"
                break;
            default:
                break;
        }
    }
});


/* Populate app with product info from server */
function getProduct() {
    commerceAPI.getProduct(this.productID)
    .then(jsonData => {
        this.title = jsonData.title;
        this.stock = jsonData.stock;
        this.price = jsonData.price_credits;
        this.description = jsonData.description;
        this.averageRating = jsonData.averageRating;
        this.reviewCount = jsonData.reviewCount;
    })
    .catch(err => {
        console.error(err);
        window.alert(err);
    })
}


/**
 * Adds specified product to cart
 *
 * @param   {Number} productID
 * @param   {Number} quantity
 * @returns {null}
 */
function addToCart(productID, quantity) {
    // Handle invalid quantity
    if (Number(quantity) === NaN) {
        window.alert(`Invalid quantity: ${quantity}`);
        return;
    }

    if (quantity < 1) {
        window.alert(`Invalid quantity: ${quantity}`);
        return;
    }

    fetch('/product/cart/item', {
        method: 'post',
        body: JSON.stringify({
            productID : productID,
            quantity : quantity
        }),
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': window.env.CSRF_TOKEN,
            'Accept': 'application/json',
        }
    })
    .then(res => {
        switch (res.status) {
            case 200:
                window.location.href = '/views/cart';
                break;
            case 400:
                return res.json();
            case 401:
                throw new AuthFailure();
            default:
                throw new Error(`${res.status}: Unknown server error. Please contact admin`);
        }
    })
    .then(json => {
        if (json) {
            console.error(json.error);
            window.alert(json.error);
        }
    })
    .catch(err => {
        if (err instanceof AuthFailure) {
            window.alert("Please Login First");
        } else {
            window.alert(err.message);
            console.error(err.message);
        }
    })
}
