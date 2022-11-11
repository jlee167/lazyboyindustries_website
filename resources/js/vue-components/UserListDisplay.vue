<template>
  <div>
    <div
      v-for="user in users"
      :key="user.userID"
      class="user-list-item"
      style="listItemStyle"
    >
      <div class="container-user">
        <img :src="user.imageUrl" class="profile-img" />
        <div class="container-metadata">
          <p class="label-username">
            {{ user.username }}
          </p>
          <div>
            <span
              :class="{ away: isAway(user), online: isOnline(user) }"
              class="status-light"
            ></span>
            <p :class="{ away: isAway(user), online: isOnline(user) }">
              {{ status(user) }}
            </p>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>




<script>
export default {
  props: {
    dev_height: Number,
    users: Array,
  },

  computed: {
    listItemStyle() {
      let str_display = "display:block;";
      let str_width = "width:100%;";
      let str_height;

      if (dev_height > 1080) str_height = "height:70px;";
      else if (dev_height > 480) str_height = "height:60px;";
      else str_height = "height:50px;";

      return str_display + str_width + str_height;
    },
  },
  data: function () {
    return {};
  },

  mounted() {},

  methods: {
    getDimension: function () {
      return device;
    },
    isAway: function (user) {
      return user.status == "Away";
    },
    isOnline: function (user) {
      return user.status == "Online";
    },
    status: function (user) {
      return user.status;
    },
  },

  computed: {},
};
</script>




<style scoped>
.profile-img {
  width: 45px;
  height: 45px;
  border-radius: 50%;
}

.status-light {
  height: 10px;
  width: 10px;
  border-radius: 50%;
  display: inline-block;
  margin-top: auto;
  margin-bottom: auto;
  margin-right: 1px;
}

span.online {
  background-color: rgb(14, 175, 14);
}

span.away {
  background-color: orange;
}

p.online {
  display: inline-block;
  color: rgb(14, 175, 14);
  font-weight: 500;
}

p.away {
  display: inline-block;
  color: orange;
}

.container-metadata {
  display: flex;
  flex-direction: column;
  align-items: start;
  justify-content: center;
  margin: auto;
  margin-left: 15px;
}

.container-user {
  display: flex;
  flex-direction: row;
  align-items: space-between;
  justify-content: center;
  margin-left: 15px;
  margin-bottom: 20px;
}

.label-username {
  color: white;
  margin: 0 0 0 0;
  height: 100%;
  padding-bottom: 2px;
}
</style>
