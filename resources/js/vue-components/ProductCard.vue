<template>
  <div id="container" @click="clickEventHandler()">
    <div id="punchhole"></div>
    <h5 id="company">{{ company }}</h5>
    <img
      id="image"
      src="/images/logo_sitting_small.png"
      />
    <h4 id="name">{{ name }}</h4>
    <div id="jobTitle"> <p id="jobTitleText"> FPGA Engineer</p></div>
    <img id="barcode" src="https://randomqr.com/assets/images/randomqr-256.png" />
  </div>
</template>

<script>
export default {
  props: {
    company: String,
    name: String,
    description: String,
    bgColor: String,
    price: Number,
    availability: Boolean,
    clickEventHandler: Function
  },
};
</script>

<style scoped>
#container {
  width: 270px;
  height: 470px;
  border-radius: 17px;
  display: flex;
  flex-direction: column;
  align-items: center;
  background: linear-gradient(180deg, rgb(255, 66, 97) 0 45%, white 30% 100%);
  cursor: pointer;
}

#punchhole {
  height: 15px;
  width: 80px;
  border-radius: 5px;
  background-color: #07080f;
  margin-top: 25px;
}

#container:hover {
  box-shadow: 2px 2px 2px gray;
}

#company {
  margin-top: 20px;
  color:white;
}

#name {
  margin-top: 20px;
}

#jobTitle {
    display:flex;
    justify-content: center;
    align-items: center;
    height:25px;
    width:auto;
    background: rgb(255, 66, 97);
}

#jobTitleText {
    margin:auto;
    color:white;
    vertical-align: middle;
    padding-left: 10px;
    padding-right: 10px;
}

#price {
  margin-top: 15px;
}

#description {
  margin-top: 15px;
}

#image {
  width: 170px;
  height: 170px;
  margin-top: 15px;
  border: 2px rgb(255, 66, 97) solid;
}

#barcode {
  margin-top: 35px;
  width: 50px;
  height: 50px;
}

.stripe {
  width: 100%;
  height: 30px;
}
</style>
