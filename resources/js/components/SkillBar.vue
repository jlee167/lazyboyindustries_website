<template>
  <div id="container">
    <div :style="styleObject"></div>
  </div>
</template>




<script>
export default {
  props: {
    level: Number,
  },

  data() {
    if (this.level == 0)
      return {
        styleObject: {
          backgroundColor: "#373536",
          borderRadius: "5px",
          width: "25%",
          height: "100%",
        },
      };
    else if (this.level == 1)
      return {
        styleObject: {
          backgroundColor: "#373536",
          borderRadius: "5px",
          width: "60%",
          height: "100%",
        },
      };
    else if (this.level == 2)
      return {
        styleObject: {
          backgroundColor: "#373536",
          borderRadius: "5px",
          width: "60%",
          height: "100%",
        },
      };
    else
      return {
        styleObject: {
          backgroundColor: "#373536",
          borderRadius: "5px",
          width: "100%",
          height: "100%",
        },
      };

    return {
      //item_details: []
    };
  },

  mounted() {},
};
</script>

<style scoped>
#container {
  display: inline-block;
  width: 200px;
  height: 10px;
  border-radius: 20px;
  background-color: #e5e5e5;
  margin-top: 1px;
}
</style>
