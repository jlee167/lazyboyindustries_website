<!doctype html>

<html>


<head>
  @include('includes.imports.env')
  @include('includes.imports.styles_common')
  @include('includes.imports.headers.cdn.summernote')

  <link rel="stylesheet" href="/css/cart.css">
  <script
    src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js">
  </script>
</head>



<body>
  @include('includes.layouts.navbar')

  <main id="cart" class="vw-100 mw-100 navbar-offset">
    <div class="d-flex flex-column" v-cloak>
      <article id="cartItems">
        <section id="itemsTopBar">
          <div class="d-flex align-items-center">
            <lottie-player
              src="https://assets7.lottiefiles.com/packages/lf20_47pyyfcf.json"
              background="transparent" speed="1"
              style="width: 50px; height: 50px;" loop autoplay>
            </lottie-player>
            <h2 class="topBarText">@{{ title }}</h2>
          </div>
          <h6> Your Credits: @{{ credits }} </h6>
        </section>

        <section v-if="cartEmpty">
          <h3 class="mt-3"> No items in your cart</h3>
        </section>

        <section v-else v-for="item in items">
          <article
            class="cart-item d-flex flex-row justify-content-between align-items-center">
            <img :src="item.imgUrl" class="item-image" />
            <section class="product-info">
              <h6 class="mb-0">@{{ item.title }}</h6>
              <h6 class="font-credits">@{{ item.price }} Credits</h6>
            </section>
            <section
              class="d-flex flex-column justify-content-center align-items-center">
              <div class="d-flex justify-content-center align-items-center">
                <input v-model="item.quantity"
                  class="form-control item-quantity" type="number">
              </div>
              <small> Quantity </small>
            </section>

            <section class="d-flex">
              <h6 class="font-credits">@{{ item.price * item.quantity }} Credits</h6>
            </section>
            <section id="removeBtn" @click="removeItem(item.productID)"
              class="d-flex flex-column align-items-center">
              <img id="removeIcon" src="/images/delete-icon.svg" />
              <small>Remove</small>
            </section>
          </article>

        </section>

        <section v-if="items.length"
          class="d-flex flex-column align-items-end w-100 mt-3">
          <div id="priceSection"
            class="d-flex flex-column align-items-end mt-3">
            <div class="w-100 d-flex">
              <p class="h6 w-50 text-right">Total Price:</p>
              <p class="h6 w-50 text-right"><s
                  class="text-danger">@{{ totalPrice }}</s></p>
            </div>
            <div class="w-100 d-flex">
              <p class="h6 w-50 text-right">Discount:</p>
              <p class="h6 w-50 text-right">@{{ discount }}</p>
            </div>
            <div class="w-100 d-flex">
              <p class="h6 w-50 text-right">Total:</p>
              <p class="h6 w-50 text-right">@{{ netPrice }}</p>
            </div>
            <button @click="purchase(items);" id="purchaseBtn" class="btn mt-4">
              Purchase
            </button>
          </div>
        </section>
      </article>
    </div>
  </main>

  @include('includes.layouts.footer')
</body>


<script>
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

    constructor() {};
  }


  cartApp = new Vue({
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
      netPrice: function() {
        return this.totalPrice - this.discount;
      },
      totalPrice: function() {
        let result = 0;
        this.items.forEach((item) => {
          result += item.price * item.quantity;
        });
        return result;
      },
      cartEmpty: function() {
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
          jsonData.cart.forEach(function(value, index, array) {
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
</script>

</html>
