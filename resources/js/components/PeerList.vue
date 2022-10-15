<template>
  <div id="top-container">
    <small class="white mb-2"> *** Shows only up to 20 users now. To be updated later </small>

    <article class="list" v-for="(peer) in peers" :key="peer.uid">
      <div class="post-item">
        <img id="userImage" :src="peer.image_url" />
        <section id="userInfo">
          <div class="username white">{{ peer.username }}</div>
          <p
            v-if="peer.status"
            :class="getStatusElemStyle(peer)"
            class="text-left white"
          >
            Status: {{ peer.status }}
          </p>
          <p
            class="stream-link white"
            v-if="peer.streamID"
            @click="watchStream(peer.streamID)"
          >
            Watch Live Stream
          </p>
        </section>
        <section id="userStatus"></section>
      </div>
    </article>
  </div>
</template>


<script>
export default {
  props: {
    peers: Array,
    streamHref: Function,
  },

  methods: {
    watchStream: function (streamID) {
      window.location.href = `/views/emergency_broadcast/${streamID}`;
    },

    isLastElement(index) {
      return index != this.peers.length - 1;
    },

    getStatusElemStyle(peer) {
      return {
        bluetext: peer.status === "FINE",
        redtext: peer.status === "DANGER_URGENT",
      };
    },
  },
};
</script>


<style scoped>
#top-container {
  display: flex;
  flex-direction: row;
  flex-wrap: wrap;
  justify-content: flex-start;
  align-items: center;
  min-width: 320px;
  max-width: 1280px;
  width: 100%;
  margin-bottom: 50px;
}

#userImage {
  margin-top: auto;
  margin-bottom: auto;
  margin-left: 15px;
  height: 80px;
  width: 80px;
  border-radius: 50%;
}

.white {
  color: white;
}

.bluetext {
  color: rgb(53, 141, 255);
}

.redtext {
  color: red;
}

.item-divider {
  width: 90%;
  margin-top: 0;
  margin-bottom: 0;
}

.pointer {
  cursor: pointer;
}

.label-vertical-align {
  margin-top: auto;
  margin-bottom: auto;
}

.inline-text {
  display: inline;
}

.bootstrap-btn {
  color: white;
}

.user-btn-placement {
  vertical-align: middle;
  margin-left: 0;
  margin-right: 10px;
  margin-top: auto;
  padding: 2px 2px 2px 2px;
  width: auto;
  height: auto;
  border-radius: 3px;
  color: white;
  font-size: 10px;
}

.stream-link {
  height: 25px;
  text-align: center;
  color: white;
  background-color: rgb(224, 39, 70);
  border-radius: 5px;
  cursor: pointer;
  margin-bottom: 0;
}

.username {
  width: 150px;
  justify-content: left;
  text-align: left;
  font-size: 18px;
  font-weight: 400;
  font-family: "Roboto", sans-serif;
}

#userInfo {
  margin: auto;
  margin-right: auto;
  margin-left: 20px;
  margin-bottom: 15px;
  margin-top: 20px;
  display: flex;
  flex-direction: column;
  align-items: left;
  align-self: flex-start;
}

.response-ui {
  display: inline;
  margin: auto;
  margin-left: auto;
  margin-right: 10px;
  display: flex;
  align-items: center;
  justify-content: center;
}

.list {
  width: 320px;
  padding: 15px 15px 15px 15px;
}

.post-item {
  display: flex;
  flex-direction: row;
  width: 100%;
  min-height: 150px;
  align-items: center;
}

.list-header {
  display: flex;
  flex-direction: row;
  align-items: center;
  justify-content: center;
  width: 100%;
  height: 80px;
  font-family: "Nanum Pen Script", cursive;
  font-size: 30;
}

@media only screen and (min-width: 768px) {
}

@media only screen and (min-width: 1100px) {
}
</style>
