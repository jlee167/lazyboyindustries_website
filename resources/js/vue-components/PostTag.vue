<template>
  <span id="tag" :class="{ fitHeight: this.fitHeight }">
    {{ outText }}
  </span>
</template>

<script>
export default {
  props: {
    name: String,
    fitHeight: {
      type: Boolean,
      default: false,
    },
    appendHashTag: {
      type: Boolean,
      default: true,
    },
  },

  computed: {
    outText: function() {
        if (this.appendHashTag) {
            return "#" + this.name;
        } else {
            return this.name;
        }
    }
  }
};
</script>

<style scoped>
.container-outline {
  width: auto;
  height: 25px;
}

#tag {
  display: flex;
  justify-content: center;
  align-items: center;
  color: white;
  font-family: "Noto Sans Display", sans-serif;
  font-size: 12px;
  background-color: rgb(85, 85, 85);
  padding-top: 2px;
  padding-bottom: 2px;
  padding-left: 5px;
  padding-right: 5px;
  margin-right: 10px;
  border-radius: 3px;
}

#tag:hover {
  background-color: white;
  color: rgb(85, 85, 85);
  border: 0.5px rgb(85, 85, 85) solid;
}

.fitHeight {
  height: 100% !important;
}
</style>
