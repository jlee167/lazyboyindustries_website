<template>
  <div id="topContainer" v-cloak>
    <div id="overview">
      <img id="image" :src="imgUrl" />
      <div id="product-header">
        <h1 id="overviewHeader1" :class="{fade1sec: active}" class="overview-header">USB-CAM</h1>
        <h4 id="overviewHeader2" :class="{fade2sec: active}" class="overview-header">
          1080p Camera with auxillary recorder <br />
          powered by Cortex-M7 DSP
        </h4>
        <button id="salesBtn" :class="{fade3sec: active}" class="btn" @click="moveToSalesPage()">
          Purchase
        </button>
        <a></a>
      </div>
    </div>
    <a href="https://pngtree.com/free-backgrounds"
      >free background photos from pngtree.com/</a
    >
  </div>
</template>


<script>
import ProductSales from "./ProductSales.vue";

export default {
  components: { ProductSales },
  props: {
    imgUrl: String,
    title: String,
    description: String,
    price: Number,
    stock: Number,
    salesImgUrl: String,
    purchase: Function,
    addToCart: Function,
    productId: Number,
    salesUrl: String,
    moveToSalesPage: Function,
    active: Boolean,
  },

  data: function () {
    return {
      deviceHeight: window.innerHeight,
      deviceWidth: window.innerWidth,
      animationToggleFlag: Boolean(true),
    };
  },

  mounted: function () {
  },
};
</script>


<style scoped>

[v-cloak] {
  display: none;
}

.fade1sec {
  animation-delay: 0.2s;
  animation-duration: 0.5s;
  animation-name: slidein;
  animation-fill-mode: forwards;
}

.fade2sec {
  animation-delay: 0.5s;
  animation-duration: 0.7s;
  animation-name: slidein;
  animation-fill-mode: forwards;
}

.fade3sec {
  animation-delay: 0.8s;
  animation-duration: 0.5s;
  animation-name: slidein;
  animation-fill-mode: forwards;
}

@keyframes fade {
  from {
    opacity: 0.0;
  }
  to {
    opacity: 1.0;
  }
}

#salesBtn {
  margin-left: -100%;
  /*
  animation: slidein 0.4s;
  animation-fill-mode: forwards;
  animation-delay: 0.8s;
  */
  width: 120px;
  height: 50px;
  margin-top: 20px;
  border-color: white;
  border-width: 2px;
  border-radius: 0px !important;
  background-color: transparent;
  color: white;
  vertical-align: middle;
  box-shadow: none !important;
  outline: 0px;
  float: left;
}

#salesBtn:hover {
  background-color: rgba(255,255,255,0.7);
  color: white;
}

@keyframes slidein {
  0% {
    margin-left: -100%;
  }
  100% {
    margin-left: 0;
  }
}

#salesBtn:focus {
  box-shadow: none !important;
  outline: 0px;
}

#sales-container {
  display: flex;
  flex-direction: row;
  justify-content: center;
  align-items: center;
  padding-top: 50px;
  padding-bottom: 50px;
  height: auto;
}

#overviewHeader1 {
  margin-left: -100%;
  /*
  animation: slidein 0.4s;
  animation-fill-mode: forwards;
  */
  font-size: 50px;
}

#overviewHeader2 {
  margin-left: -100%;
  /*
  animation: slidein 0.4s;
  animation-fill-mode: forwards;
  animation-delay: 0.4s;
  */
  padding-right: 30px;
}

#topContainer {
  display: block;
  width: 100%;
  overflow-x: hidden;
  overflow-y: hidden;
  background-image: url("/images/RedBackground.jpg");
}

#overview {
  display: flex;
  flex-direction: row;
  align-items: center;
  justify-content: center;
  background-size: cover;
  /*background: linear-gradient(165deg, #121212 55%, white 55.1%);*/
  /*background-color: rgb(187, 0, 0);*/
  opacity: 1;
  width: 100vw;
  height: 100vh;
  min-height: 600px;
  margin-left: 0px;
  margin-right: 0px;
  padding-top: 70px;
}

.overview-header {
  color: white;
  text-align: left;
}

#product-header {
  margin-top: 300px;
  height: 100%;
  overflow-x: hidden;
}

#image {
  width: 30vh;
  height: 70vh;
  /*height: 700px;*/
  margin-right: 20px !important;
}

#summary {
  display: flex;
  flex-direction: row;
  justify-content: center;
  align-items: center;
  background-color: rgb(145, 191, 252);
  margin-top: 150px;
  opacity: 0.7;
  width: 100%;
  height: 500px;
}

.btn-add-cart {
  background-color: #e6176de3 !important;
  border-color: #e6176de3 !important;
  border-radius: 20px;
  width: 200px;
  color: white;
}

.vertical-middle {
  margin-right: 10px;
  margin-top: auto;
  margin-bottom: auto;
}

#stock-level {
  margin-left: 20px;
}

#rating {
  margin-top: 0px;
}

#price {
  color: rgb(238, 52, 52);
  margin-top: 20px;
}

#product-details {
  font-family: "Anton", sans-serif !important;
  display: flex;
  margin-left: 80px;
  max-width: 300px;
  flex-direction: column;
  justify-content: flex-start;
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

.quantity {
  width: 40px;
}

@media (max-width: 1600px) {
  #image {
    margin-right: 10%;
  }
}

@media (max-width: 1400px) {
  #image {
    width: 300px;
    height: 600px;
    margin-right: 7%;
    padding-bottom: 8%;
  }

  #overviewHeader2 {
    max-width: 500px;
  }

  #overviewHeader1 {
    font-size: 40px;
  }
}

@media (max-width: 800px) {
  #image {
    width: 230px;
    height: 550px;
    margin-right: 7%;
    padding-bottom: 10%;
  }

  #overviewHeader2 {
    max-width: 400px;
  }
}
</style>
