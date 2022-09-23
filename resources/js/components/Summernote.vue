<template>
  <div id="container" class="editor">
    <div id="summernote"></div>
  </div>
</template>


<script>
export default {
  props: {
    height: Number,
  },

  data() {
    return {};
  },

  mounted() {
    $("#summernote").summernote({
      placeholder: "",
      tabsize: 2,
      height: this.height,
      lineNumbers: true,
      lineWrapping: true,
    });
  },
  methods: {
    getContent: function () {
      return $("#summernote").summernote("code");
    },
    resetEditor: function () {
      $("#summernote").summernote("reset");
    },
  },
};
</script>


<style scoped>
#container {
  width: 100%;
  height: auto;
  margin: 0 0 0 0;
}
</style>
