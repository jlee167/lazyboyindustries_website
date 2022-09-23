<template>
  <div id="scrollContainer"
    :class="{ bounce: motionEnabled }">
    <div :class="direction"
    :style="style"></div>
  </div>
</template>

<script>
export default {
  props: {
    direction: String,
    motionEnabled: Boolean,
    pixelSize: {
        type: Number,
        default: 25
    },
    color: {
        type: String,
        default: "#fafafa",
    }
  },

  computed: {
    style() {
        return 'width:' + String(this.pixelSize) + 'px;'
            +  'height:' + String(this.pixelSize) + 'px;'
            +  'border-color:' + this.color + ';';
    }
  }
};
</script>

<style scoped>
#scrollContainer {
  display: flex;
  justify-content: center;
  align-items: center;
  height: auto;
}

div.up {
  /*width: 6vmin;
  height: 6vmin;*/
  box-sizing: border-box;
  transform: rotate(-45deg);
  border-width: 4px 4px 0 0;
  border-style: solid;
  border-color: #fafafa;
}

div.down {
  /*width: 6vmin;
  height: 6vmin;*/
  box-sizing: border-box;
  transform: rotate(-45deg);
  border-width: 0 0 4px 4px;
  border-style: solid;
  border-color: #fafafa;
}

.black {
    border-color:black;
}

.bounce {
  animation: bounce 0.8s infinite;
}

@keyframes bounce {
  0% {
    transform: translateY(0px);
  }
  20% {
    transform: translateY(3px);
  }
  40% {
    transform: translateY(6px);
  }
  60% {
    transform: translateY(9px);
  }
  80% {
    transform: translateY(12px);
  }
  100% {
    transform: translateY(15px);
  }
}
</style>
