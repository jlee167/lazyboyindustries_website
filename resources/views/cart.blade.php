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
  <script defer src="{{ mix('js/cart-defer.js') }}"></script>
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
              style="width: 50px; height: 50px; margin-right: 1rem;" loop autoplay>
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


</html>
