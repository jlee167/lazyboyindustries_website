<template>
  <div id="forum">
    <div id="topMenu">
      <!--h1 id="forumTitle">
        {{ forumName.charAt(0).toUpperCase() + forumName.slice(1) }}
      </h1-->
    </div>
    <div v-for="post in posts" :key="post.id">
      <forum-list-item
        @click.native="onPostClick(post.id)"
        :post="post"
        :searchKeyword="searchKeyword"
      >
      </forum-list-item>
    </div>
  </div>
</template>




<script>
import ForumListItem from "./ForumListItem.vue";

export default {
  components: { ForumListItem },

  props: {
    forumName: String,
    posts: Array,
    onPostClick: Function,
    searchKeyword: String,
    removeKeyword: Function,
  },

  data() {
    return {};
  },
};
</script>




<style scoped>
#forumTitle {
  text-align: left;
}

#topMenu {
  display: flex;
  align-items: center;
}

#forum {
  display: inline;
}

@media only screen and (min-width: 1400px) {
  #forum {
    width: 800px;
  }
}

@media only screen and (max-width: 1400px) {
  #forum {
    width: 680px;
  }
}

@media only screen and (max-width: 768px) {
  #forum {
    width: 500px;
  }
}

@media only screen and (max-width: 500px) {
  #forum {
    width: 400px;
    margin-right: 0;
    margin-left: 0;
    padding-right: 0;
    padding-left: 0;
  }
}

@media only screen and (max-width: 400px) {
  #forum {
    width: 320px;
  }
}
</style>
