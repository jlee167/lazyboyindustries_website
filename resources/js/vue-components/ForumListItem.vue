<template>
  <div id="container">
    <div class="author-info">
      <img v-if="post.imageUrl" class="profile-image" :src="post.imageUrl" />
      <p id="author">{{ author }}</p>
    </div>

    <div class="post-contents">
      <div id="title-container">
        <p id="postID">#{{ post.id }}</p>
        <text-highlight id="title" :queries="[keyword]">
          {{ post.title }}
        </text-highlight>
        <div id="tagContainer" class="d-flex align-content-center mt-2">
          <post-tag v-for="tag in post.tags" :key="tag" :name="tag"></post-tag>
        </div>
      </div>

      <div id="metadata-container">
        <div>
          <img
            id="commentCountIcon"
            class="icon-small"
            src="/images/chat-right-text-fill.svg"
          />
          <p id="commentCount" class="post-metadata">
            {{ post.commentCount }}
          </p>
          <img
            id="viewCountIcon"
            class="icon-small"
            src="/images/icon-eye.svg"
          />
          <p id="viewCount" class="post-metadata">{{ post.viewCount }}</p>
          <img
            id="likeCountIcon"
            class="icon-small"
            src="/images/heart-gray-small.svg"
          />
          <p id="likeCount" class="post-metadata">{{ post.likes }}</p>
        </div>
      </div>
    </div>
  </div>
</template>


<script>
import PostTag from "./PostTag.vue";
import TextHighlight from "vue-text-highlight";

export default {
  components: { PostTag, TextHighlight },
  props: {
    post: Object,
    searchKeyword: String,
  },

  computed: {
    author: function () {
      return this.post.author ? this.post.author : "Deleted";
    },

    keyword: function () {
        return (this.searchKeyword == null) ? "" : this.searchKeyword;
    }
  },
};
</script>


<style scoped>
#author {
  display: inline-block;
  font-family: "Poppins", sans-serif;
  font-size: 15px;
  font-weight: 300;
  margin: 0 0 0 0;
  margin-top: 10px;
  text-overflow: ellipsis;
}

#title-container {
  height: 67px;
  margin-top: 10px;
  margin-bottom: 10px;
  padding-right: 15px;
  padding-right: 15px;
  font-family: "Poppins", sans-serif;
}

#title {
  width: 100%;
  height: 22px;
  margin: auto;
  margin-top: 4px;
  margin-bottom: 0;
  padding: 0 0 0 0;
  vertical-align: middle;
  font-family: "Noto Sans Display", sans-serif;
  font-size: 16px;
  font-weight: 550;
  text-overflow: ellipsis;
  word-break: break-all;
  display: -webkit-box;
  -webkit-line-clamp: 1;
  -webkit-box-orient: vertical;
  overflow: hidden;
}

#postID {
  color: grey;
  margin-bottom: 0px;
  font-size: 10px;
  height: 10px;
}

#metadata-container {
  display: flex;
  width: 100%;
  flex-direction: row;
  justify-content: space-between;
  align-items: center;
  margin: auto;
  margin-top: 0px;
  margin-bottom: 10px;
  padding-right: 15px;
}

#commentCount {
  font-family: "Noto Sans Display", sans-serif;
  font-weight: 400;
  vertical-align: middle;
}

#viewCount {
  font-family: "Noto Sans Display", sans-serif;
  font-weight: 400;
  vertical-align: middle;
}

#likeCount {
  font-family: "Noto Sans Display", sans-serif;
  font-weight: 400;
  vertical-align: middle;
}

#tagContainer {
    margin-bottom: 10px;
}

.post-metadata {
  display: inline;
  vertical-align: middle;
  margin: auto;
  margin-right: 30px;
  color: rgb(82, 82, 82);
}

.profile-image {
  width: 65px;
  height: 65px;
  margin-top: 10px;
  background-size: contain;
  margin-left: auto;
  margin-right: auto;
  border-radius: 50%;
}

.icon-small {
  width: 20px;
  height: 20px;
}

#container {
  width: 100%;
  max-width: 100%;
  height: 140px;
  display: flex;
  flex-direction: row;
  justify-content: flex-start;
  margin-left: 0px;
  margin-right: 0px;
  margin-bottom: 30px;
  margin-top: 30px;
  overflow: hidden;
  background-color: white;
  cursor: pointer;
  /*border: #b9b9b9bb 0.2px solid;*/
  border-radius: 0.5rem;
}

#container:hover {
  box-shadow: 0.5px 0.5px 0.5px 0.5px gray;
}

.author-info {
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
  margin-left: 20px;
  margin-right: 20px;
  flex-basis: 120px;
  flex-grow: 0;
  flex-shrink: 0;
  overflow: hidden;
}

.post-status {
  height: 100%;
  width: 7px;
  margin-right: 22px;
  overflow: hidden;
}

.post-contents {
  display: flex;
  flex-direction: column;
  justify-content: space-around;
  width: auto;
  height: 100%;
  padding-right: 15px;
  padding-left: 20px;
  overflow: hidden;
}

.post-info {
  display: flex;
  flex-direction: column;
  width: 50px;
  justify-content: center;
}

@media only screen and (max-width: 500px) {
  .author-info {
    display: none;
  }
  .profile-image-small {
    display: inline-block;
  }
}
</style>
