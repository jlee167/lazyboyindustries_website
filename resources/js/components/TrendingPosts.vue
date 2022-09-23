<template>
  <div class="list-container">
    <div class="list-header bg-gradient-sunny">
      {{ title }}
    </div>
    <div class="list" v-for="content in contents" :key="content.id">
      <div
        class="post-item"
        @click="content.callback(content.id, content.forum)"
      >
        <p class="font-title">
          {{ content.title }}
        </p>

        <p class="text-default">
          {{ content.date }}
        </p>
      </div>
    </div>
  </div>
</template>


<script>
export default {
  props: {
    contents: Array,
    title: String,
  },

  data: function () {
    return {};
  },
};
</script>


<style scoped>
.bg-gradient-sunny {
  background: linear-gradient(to right, #ffff99 0%, #ff9900 100%);
}

.font-title {
  margin: auto;
  margin-left: 10px;
  max-width: 250px;
  overflow: hidden;
  white-space: nowrap;
  text-overflow: ellipsis;
}

.text-default {
  margin: auto;
  margin-left: auto;
  margin-right: 10px;
  min-width: 90px;
  overflow: hidden;
}

.list-container {
  display: none;
  flex-direction: column;
  width: 300px;
  height: auto;
  margin: 0 auto;
  margin-left: 0px;
  margin-top: 100px;
  text-align: center;
  border: 0.2px solid;
  border-color: rgb(133, 133, 133);
  background-color: white;
  box-shadow: 1px 1px 1px gray;
  overflow: hidden;
}

.list {
  width: 100%;
}

.post-item {
  display: flex;
  flex-direction: row;
  width: 100%;
  height: 40px;
  align-items: center;
  border-bottom: 0.5px solid;
  border-color: #d5d5d5;
}

.post-item:hover {
  background-color: rgb(225, 245, 138);
  cursor: pointer;
}

.list-header {
  display: flex;
  flex-direction: row;
  width: 100%;
  height: 80px;
  align-items: center;
  justify-content: center;
  border-color: #d5d5d5;
  font-family: "Nanum Pen Script", cursive;
  font-size: 30;
}

@media only screen and (min-width: 768px) {
  .list-container {
    display: none;
    width: 300px;
    margin-left: 0px;
  }
}

@media only screen and (min-width: 1200px) {
  .list-container {
    display: flex;
    width: 400px;
    margin-left: 80px;
  }
}
</style>
