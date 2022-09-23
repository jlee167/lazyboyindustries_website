<template>
  <article id="container" :style="containerStyle">
    <div class="question-item" v-for="qna in qnaArr" :key="qna.question">
      <section class="question-box" @click="qna.active = !qna.active">
        <p class="question">{{ qna.question }}</p>
        <scroll-arrow
          :direction="qna.active ? 'up' : 'down'"
          :motion-enabled="false"
          :pixelSize="15"
          color="black"
        ></scroll-arrow>
      </section>
      <transition name="answer">
        <pre v-if="qna.active" class="answer">{{ qna.answer }}</pre>
      </transition>
    </div>
  </article>
</template>



<script>
import ScrollArrow from "./ScrollArrow.vue";

export default {
  components: { ScrollArrow },
  props: {
    qnaArr: Array,
    maxWidth: {
      type: Number | String,
      default: 1200,
    },
  },

  computed: {
    containerStyle: function () {
      return {
        maxWidth: `${this.maxWidth}px`,
      };
    },
  },

  data: function () {
    return {};
  },
};
</script>


<style scoped>
#container {
  display: flex;
  flex-direction: column;
  justify-content: flex-start;
  align-items: flex-start;
}

.question-item {
  margin-top: 15px;
  width: 100%;
  padding-bottom: 0;
}

.question-box {
  display: flex;
  flex-direction: row;
  justify-content: space-between;
  align-content: center;
  cursor: pointer;
  width: 100%;
  padding: 5px 5px 5px 5px;
}

.question-box:hover {
  background: #dddcdc;
  border-radius: 8px;
}

.question {
  color: #4e5968;
  font-family: "Nunito Sans", sans-serif !important;
  vertical-align: middle;
  overflow-wrap: break-word;
  font-size: 20px;
  margin-bottom: 0;
  padding: 0.3em 0.3em 0.3em 0.3em;
}

.question::before {
  font-weight: 700;
  margin-right: 15px;
  color: rgba(0, 29, 54, 0.31);
  content: "Q";
}

.answer {
  width: 100%;
  padding-bottom: 0.7rem;
  padding-left: 48px;
  color: #4e5968;
  font-family: "Nunito Sans", sans-serif !important;
  font-size: 18px;
  white-space: pre-wrap;
  word-wrap: break-word;
}

.answer-enter-active {
  -moz-transition-duration: 0.3s;
  -webkit-transition-duration: 0.3s;
  -o-transition-duration: 0.3s;
  transition-duration: 0.3s;
  -moz-transition-timing-function: ease-in;
  -webkit-transition-timing-function: ease-in;
  -o-transition-timing-function: ease-in;
  transition-timing-function: ease-in;
}

.answer-leave-active {
  -moz-transition-duration: 0.2s;
  -webkit-transition-duration: 0.2s;
  -o-transition-duration: 0.2s;
  transition-duration: 0.2s;
  -moz-transition-timing-function: ease-out;
  -webkit-transition-timing-function: ease-out;
  -o-transition-timing-function: ease-out;
  transition-timing-function: ease-out;
}

.answer-enter-to,
.answer-leave {
  max-height: 300px;
  overflow: hidden;
}

.answer-leave-to,
.answer-enter {
  max-height: 0;
  overflow: hidden;
}
</style>
