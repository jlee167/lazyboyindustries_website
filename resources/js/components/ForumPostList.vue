<template>
  <div id="forum">
    <div id="topMenu">
      <h1 id="forumTitle">
        {{ forumName.charAt(0).toUpperCase() + forumName.slice(1) }}
      </h1>
      <span v-if="searchKeyword" id="searchKeyword" @click="removeKeyword">
        {{ searchKeyword }}
        <img src="/images/x-circle.svg" />
      </span>

      <a
        id="postBtn"
        class="btn"
        role="button"
        :href="'createpost?forum=' + forumName"
      >
        Create Post</a
      >
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

#postBtn {
  height: 40px;
  margin-left: auto;
  white-space: nowrap;
  background-color: purple;
  border: 1px purple solid;
  color: white;
}

#postBtn:hover {
  background-color: white;
  color: purple;
}

#topMenu {
  display: flex;
  align-items: center;
}

#forum {
  display: inline;
}

#searchKeyword {
  font-family: "Noto Sans", sans-serif;
  margin-left: 40px;
  color: white;
  background-color: rgb(0, 119, 79);
  padding: 10px 10px 10px 10px;
  border-radius: 5px;
  cursor: pointer;
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
