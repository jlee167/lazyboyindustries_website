<template>
  <div id="sales">
    <div id="contents">
      <div id="image-container">
        <img id="image" :src="imgUrl" />
      </div>
      <div id="product-details">
        <div class="details-margin">
          <h1 id="title">{{ title }}</h1>
          <star-rating
            id="rating"
            :read-only="true"
            :rating="3.35"
            :increment="0.5"
            :star-size="15"
          />
          <h3 id="price">{{ price }} Credits</h3>

          <pre id="description">{{ description }}</pre>

          <span id="purchase">
            <h4 class="d-inline align-middle vertical-middle">Qty</h4>
            <input v-model="quantity" class="quantity-input" placeholder="0" />
            <p id="stock" class="vertical-middle">
              ({{ stock }} in stock)
            </p>
          </span>
          <button class="btn btn-purchase" @click="purchase(productId, quantity)">Buy now</button>
          <button class="btn btn-add-cart" @click="addToCart(productId, quantity)">Add to Card</button>

          <p v-if="transactionError" class="vertical-middle"> Transaction Failed: Insufficient stock </p>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import StarRating from "vue-star-rating";

export default {
  props: {
    title: String,
    description: String,
    price: Number,
    stock: Number,
    imgUrl: String,
    purchase: Function,
    addToCart: Function,
    productId: Number
  },

  data: function () {
    return {
        quantity: 0,
        transactionError: false
    }
  },

  components: {
    StarRating,
  },

  computed: {},

  mounted() {
    //this.$refs.overview.style.setProperty('--background-image', this.imgUrl);
  },
};
</script>

<style scoped>
#image {
  object-fit: contain;
  width: 200px;
  height: 400px;
  object-fit: contain;
  margin-left: auto;
  margin-right: 15%;
}

#summary {
  display: flex;
  flex-direction: row;
  justify-content: center;
  align-items: center;
  background-color: rgb(236, 236, 236);
  margin-top: 100px;
  opacity: 0.7;
  width: 100%;
  height: 350px;
}

.btn-purchase {
  background-color: #e6176de3 !important;
  border-color: #e6176de3 !important;
  border-radius: 20px;
  width: 120px;
  color: white;
}

.btn-add-cart {
  background-color: #ffd902e3 !important;
  border-color: #ffd902e3 !important;
  border-radius: 20px;
  width: 120px;
  color: black;
}

.vertical-middle {
  margin-right: 10px;
  margin-top: auto;
  margin-bottom: auto;
}

#stock {
  margin-left: 20px;
}

#rating {
  margin-top: 0px;
}

#contents {
  display: flex;
  flex-direction: row;
  height: auto;
  margin: 0 0 0 0;
}

#image-container {
  display: flex;
  flex-direction: row;
  justify-content: center;
  background-color: rgb(255, 223, 155);
  padding: 80px 80px 80px 80px;
}

#sales {
  display: flex;
  flex-direction: row;
  justify-content: center;
  height: auto;
  box-shadow: 2px 2px 2px gray;
}

#price {
  color: rgb(238, 52, 52);
  margin-top: 20px;
}

#product-details {
  font-family: "Anton", sans-serif !important;
  display: flex;
  flex-direction: row;
  flex-direction: column;
  justify-content: flex-start;
  align-items: center;
  height: 100%;
  padding-top: 80px;
  padding-bottom: 80px;
  padding-left: 40px;
  padding-right: 80px;
}

#title {
  font-family: "Anton", sans-serif !important;
  margin-bottom: 0px;
}

#description {
  font-family: "Anton", sans-serif !important;
  color: gray;
  text-overflow: ellipsis;
  width: 100%;
  word-break: break-all;
  white-space: pre-line;
  margin-top: 30px;
}

#purchase {
  display: flex;
  flex-direction: row;
  align-items: center;
  justify-content: flex-start;
  margin-top: 30px;
  margin-bottom: 15px;
}

.quantity-input {
  width: 40px;
}
</style>



