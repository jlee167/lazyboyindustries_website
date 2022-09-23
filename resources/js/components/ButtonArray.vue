<template>
  <div id="container">
    <div
      v-for="(forum, index) in forums"
      :key="index"
      class="button"
      :class="{
        selected: index == currentForumIndex,
        unselected: !(index == currentForumIndex),
      }"
      @click="onTabSelect(index)"
    >
      {{ forum }}
    </div>
  </div>
</template>

<script>
export default {
  props: {
    forums: Array,
    defaultForum: String,
  },

  data: function () {
    return {
      currentForumIndex: 0,
    };
  },

  methods: {
    onTabSelect: function (index) {
      this.currentForumIndex = index;
      this.$emit("forum-select", this.forums[index]);
    },
  },
};
</script>


<style scoped>
div.button {
  display: inline-flex;
  justify-content: center;
  align-items: center;
  width: 90px;
  height: 35px;
  padding-top: 8px;
  padding-bottom: 8px;
  padding-left: 8px;
  padding-right: 8px;
  border: 1px orange solid;
  vertical-align: middle;
}

div.button:hover {
  cursor: pointer;
}

div.unselected {
  color: orange;
  background-color: white;
}

div.selected {
  color: white;
  background-color: orange;
}
</style>
