window.purchase = (productID, quantity) => {
    fetch('/product/order', {
        method: 'post',

        body: JSON.stringify({
            productID: productID,
            quantity: quantity
        }),

        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': window.env.CSRF_TOKEN
        }
    })
        .then(res => {
            if (res.status === 200) {
                return res.json();
            }
        })
        .then(json => {
            if (json.result) {
                window.location.href = window.location.href;
            } else {
                window.alert(json.error);
            }
        })
        .catch(err => {
            window.alert(err);
        })
}

class CartItem {
    /* public */
    productID = Number();
    title = String();
    imgUrl = String();
    price = Number();
    unit = String();
    quantity = Number();

    constructor() { };
}


window.cartApp = new Vue({
    el: "#cart",
    data: {
        title: String("My Cart"),
        items: [],
        discount: Number(0),
        credits: Number(0),

        isInProgress: false,
        purchase: purchaseAll,
        removeItem: removeItem
    },


    computed: {
        netPrice: function () {
            return this.totalPrice - this.discount;
        },
        totalPrice: function () {
            let result = 0;
            this.items.forEach((item) => {
                result += item.price * item.quantity;
            });
            return result;
        },
        cartEmpty: function () {
            return this.items.length == 0;
        }
    }
});


function purchaseSelected() {
    /* @Todo */
}


function getCart() {

    fetch('/product/cart', {
        method: 'get',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': window.env.CSRF_TOKEN
        }
    })
        .then(response => {
            switch (response.status) {
                case 200:
                    return response.json();
                case 404:
                    throw new Error(
                        `${response.status}: Failed to load cart! Please try again!`);
                default:
                    throw new Error(
                        `${response.status}: Unknown server error. Please contact admin`
                    );
            }
        })
        .then(jsonData => {
            if (jsonData.result) {
                jsonData.cart.forEach(function (value, index, array) {
                    let newItem = new CartItem();
                    newItem.productID = value.product_id;
                    newItem.title = value.title;
                    newItem.imgUrl = value.img_url;
                    newItem.price = value.price_credits;
                    newItem.quantity = value.quantity;
                    cartApp.items.push(newItem);
                })
            } else {
                window.alert(jsonData.error);
            }
        })
        .catch(err => {
            window.alert(err.message);
            console.error(err.message);
        })
}


async function purchaseAll(items) {
    /*
      @Todo:
        Send currently received price information.
        Backend rejects request when price in backend DB
        does not match price currently stored in client.
    */
    if (cartApp.isInProgress)
        return;
    else
        cartApp.isInProgress = true;

    fetch('/product/order/cart/all', {
        method: 'post',

        body: JSON.stringify({
            items: items
        }),

        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': window.env.CSRF_TOKEN
        }
    })
        .then(response => {
            switch (response.status) {
                case 200:
                    window.location.href = '/views/purchase_history';
                    break;
                case 400:
                    return response.json();
                case 500:
                    throw new Error(`${response.status}: Database error occured!`);
                default:
                    throw new Error(
                        `${response.status}: Unknown server error. Please contact admin`
                    );
            }
        })
        .then(jsonData => {
            if (jsonData) {
                cartApp.isInProgress = false;
                window.alert(jsonData.error);
                console.error(jsonData.error);
            }
        })
        .catch(err => {
            cartApp.isInProgress = false;
            window.alert(err.message);
            console.error(err.message);
        })

}

async function removeItem(productID) {
    if (cartApp.isInProgress)
        return;
    else
        cartApp.isInProgress = true;

    fetch('/product/cart/item/' + productID, {
        method: 'delete',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': window.env.CSRF_TOKEN
        }
    })
        .then(response => {
            switch (response.status) {
                case 200:
                    return response.json();
                case 500:
                    throw new Error(`${response.status}: Database error occured!`);
                default:
                    throw new Error(
                        `${response.status}: Unknown server error. Please contact admin`
                    );
            }
        })
        .then(jsonData => {
            window.location.href = window.location.href;
        })
        .catch(err => {
            cartApp.isInProgress = false;
            window.alert(err.message);
            console.error(err.message);
        })
}

function getCredits() {
    fetch('/credits', {
        method: 'get',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': window.env.CSRF_TOKEN
        }
    })
        .then(response => {
            switch (response.status) {
                case 200:
                    return response.json();
                case 500:
                    throw new Error(`${response.status}: Database error occured!`);
                default:
                    throw new Error(
                        `${response.status}: Unknown server error. Please contact admin`
                    );
            }
        })
        .then(jsonData => {
            cartApp.credits = jsonData.credits;
        })
        .catch(err => {
            window.alert(err.message);
            console.error(err.message);
        })
}


/* Page Initialization */
getCart();
getCredits();
