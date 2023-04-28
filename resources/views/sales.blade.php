<!doctype html>

<html>


<head>
  @include('includes.imports.env')
  @include('includes.imports.styles_common')
  @include('includes.imports.headers.titles.default')

  <script src="/js/ecommerce.js"></script>
  <script src="{{ mix('js/sales.js') }}" defer></script>
  <link rel="stylesheet" href="/css/sales.css">
</head>



<body>
  @include('includes.layouts.navbar')

  <main id="sales">
    <article id="contents" v-cloak>
      <section id="image-container">
        <img id="image" :src="imgUrl" />
      </section>
      <section id="product-details">
        <div>
          <h1 id="title">@{{ title }}</h1>
          <hr>
          <div class="d-flex align-center">
            <div class="d-flex align-center pb-1">
              <star-rating id="ratingView" :read-only="true"
                :rating="averageRating" :increment="0.1"
                :show-rating="false" :star-size="15" />
            </div>
            <a class="ml-2">
              <p id="reviewLink"> @{{ reviewCount }} reviews </p>
            </a>
          </div>
          <h6 id="price">@{{ price }} Credits/Ea.</h6>

          <pre id="description">@{{ description }}</pre>

          <span id="purchase" class="mb-1">
            <h4 class="d-inline align-middle vertical-middle">Qty</h4>
            <input id="quantity" v-model="quantity"
              class="form-control no-outline" placeholder="0" type="number"
              min="0" />
          </span>

          <small id="stock" class="vertical-middle mt-0">@{{ stock }}
            in stock</small>

          <button class="btn btn-add-cart mt-5 no-outline"
            @click="addToCart(productID, quantity)">
            Add to Cart</button>
        </div>
      </section>
    </article>
  </main>

  @include('includes.layouts.footer')
</body>

</html>
