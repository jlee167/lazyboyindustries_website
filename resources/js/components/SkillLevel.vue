<template>
  <article id="container">
    <p id="name" class="text-font">{{ name }}</p>
    <div id="level" class="content" v-if="levelInNum">
      <div
        class="circle"
        v-for="index in 5"
        :key="index"
        :class="{ fill: index <= levelInNum, empty: index > levelInNum }"
      ></div>
    </div>

    <div id="level" class="content" v-if="!levelInNum">
      <p class="text-font">{{ desc }}</p>
    </div>
  </article>
</template>

<script>
export default {
  props: {
    name: String,
    level: [Number, String],
    desc: String,
  },

  computed: {
      levelInNum: function() {
          return Number(this.level);
      }
  },

  mounted() {},
};
</script>


<style scoped>
#container {
  display: flex;
  flex-direction: row;
  align-items: center;
  margin-bottom: 5px;
}

#name {
  vertical-align: middle;
  width: 150px;


}

.text-font {
  font-size: 16px;
  font-family: "Roboto", sans-serif;
  font-weight: bold;
  margin-top: 0;
  margin-bottom: 0;
}

.content {
  display: flex;
  flex-direction: row;
  align-items: center;
}

.circle {
  display: inline-block;
  width: 15px !important;
  height: 15px !important;
  margin-right: 5px;
  border-radius: 50%;
}

.fill {
  background-color: rgb(226, 75, 55);
}

.empty {
  background-color: rgb(44, 44, 44);
}
</style>
