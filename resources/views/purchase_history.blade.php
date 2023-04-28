<!doctype html>

<html>


<head>
  @include('includes.imports.env')
  @include('includes.imports.styles_common')
  @include('includes.imports.headers.titles.default')

  <link
    href="https://cdn.jsdelivr.net/npm/summernote@0.8.16/dist/summernote-bs4.min.css"
    rel="stylesheet">
  <script
    src="https://cdn.jsdelivr.net/npm/summernote@0.8.16/dist/summernote-bs4.min.js">
  </script>

  <link href="/css/purchase-history.css" rel="stylesheet">
  <script src="{{ mix('js/purchase_history.js') }}" defer></script>

  <script
    src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js">
  </script>
</head>


<body>
  @include('includes.layouts.navbar')

  <main id="cart" class="vw-100 mw-100 navbar-offset">
    <div class="d-flex flex-column" v-show="loaded" v-cloak>
      <article id="cartItems">
        <section id="itemsTopBar">
          <div class="d-flex align-items-center">
            <lottie-player
              src="https://assets7.lottiefiles.com/packages/lf20_cr5nnvek.json"
              background="transparent" speed="1"
              style="width: 60px; height: 60px;" loop autoplay>
            </lottie-player>
            <h2 class="topBarText">@{{ title }}</h2>
          </div>
        </section>

        <section v-if="items.length == 0">
          <h3 class="mt-3"> You have not purchased any item yet. </h3>
        </section>

        <section v-else v-for="(item, index) in items">
          <article class="purchased-item" :class="{ divider: (index != 0) }">
            <h6>Transaction ID: @{{ item.transactionID }}</h6>

            <section
              class="d-flex flex-row justify-content-between align-items-center">
              <img :src="item.imgUrl" class="item-image" />
              <section class="item-container">
                <h6 class="item-title">@{{ item.title }}</h6>
                <h6 class="price">@{{ item.unitPrice }} Credits/EA
                </h6>
                <small v-if="!item.reviewed && !item.reviewActive"
                  @click="item.reviewActive=true;" class="activate-review">Write
                  a review</small>
              </section>
              <section
                class="d-flex flex-column justify-content-center align-items-center quantity-container">
                <div class="d-flex align-items-center">
                  <h6 class="quantity"> @{{ item.quantity }} pcs</h6>
                </div>
              </section>

              <section
                class="d-flex flex-column align-items-center credits-container">
                <h6 class="credits">@{{ item.expense }} Credits</h6>
              </section>
              <section class="d-flex flex-column align-items-center">
                <p class="date">@{{ item.date }}</p>
              </section>
            </section>

            <section>
              <div v-if="!item.reviewed && item.reviewActive"
                class="d-flex flex-column justify-content-between align-items-end">
                <div>
                  <star-rating :read-only="item.reviewed"
                    v-model="item.review.value" :increment="1"
                    :star-size="25" />
                </div>
                <textarea v-model="item.review.comment" class="comment-write form-control mt-2"></textarea>
                <button @click="postReview(item.review)"
                  class="btn btn-success mt-3">Submit</button>
              </div>
              <div v-if="item.reviewed" class="mt-4 mb-4">
                <h6> Your Review </h6>
                <div>
                  <star-rating v-model="item.review.value"
                    :read-only="item.reviewed" :increment="1"
                    :star-size="12" />
                </div>
                <p class="comment">@{{ item.review.comment }}</p>
              </div>
            </section>
          </article>
        </section>

      </article>
    </div>
  </main>

  @include('includes.layouts.footer')
</body>

</html>
