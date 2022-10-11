<template>
  <article id="container" :class="{ post: post.title, comment: !post.title }">
    <section class="container-info">
      <section class="flex-grow-1">
        <div v-if="post.title" class="container-title">
          <h3 class="font-title">{{ post.title }}</h3>
        </div>

        <div id="authorInfoView">
          <img v-if="post.author" id="userImage" :src="post.imageUrl" />
          <div id="authorDateContainer">
            <p id="author">
              {{ post.author ? post.author : "deleted" }}
            </p>
            <p id="date" class="font-info">{{ post.date }}</p>
          </div>
        </div>
      </section>

      <section v-if="post.hasOwnProperty('title')" id="likeContainer">
        <img
          v-if="myLike"
          class="like-icon"
          @click="toggleLike()"
          src="/images/heart-fill.svg"
        />
        <img
          v-else
          class="like-icon"
          @click="toggleLike()"
          src="/images/heart.svg"
        />
        <p>{{ likes }}</p>
      </section>
    </section>

    <section id="contentSection">
      <div class="container-contents">
        <div class="style-contents" v-html="post.contents"></div>
      </div>
    </section>

    <section id="footnotes">
      <div id="crudBtns">
        <div id="editBtn" class="crud-link" @click="updatePost(post)">
          <div class="padding-6px">
            <img
              src="/images/edit-icon-white.svg"
              class="crud-icon icon-margin"
            />
            <span>edit</span>
          </div>
        </div>
        <div id="deleteBtn" class="crud-link" @click="deletePost(post)">
          <div class="padding-6px">
            <img
              src="/images/delete-icon-white.svg"
              class="crud-icon icon-margin"
            />
            <span>delete</span>
          </div>
        </div>
      </div>

      <div id="tagContainer">
        <post-tag v-for="tag in post.tags" :fitHeight="true" :key="tag" :name="tag"></post-tag>
      </div>
    </section>
  </article>
</template>




<script>
import PostTag from "./PostTag.vue";

export default {
  components: { PostTag },

  props: {
    post: Object,
    likes: Number,
    myLike: Boolean,
    toggleLike: Function,
    updatePost: Function,
    deletePost: Function,
  },
};
</script>




<style scoped>
#container {
  display: flex;
  flex-direction: column;
  justify-content: flex-start;
  background-color: white;
  width: 100%;
  height: auto;
  padding-bottom: 2rem;
  padding-right: 1.5rem;
  padding-left: 1.5rem;
  margin-bottom: 20px;
  border-radius: 0.5rem;
}

#authorDateContainer {
  display: inline-flex;
  flex-direction: column;
  justify-content: center;
  height: 100%;
  align-items: start;
  margin-left: 0.7rem;
}

#date {
  color: rgba(112, 112, 112, 0.76);
  vertical-align: center;
  margin: 0 0 0 0;
  margin-right: 10px;
}

#author {
  font-size: 1rem;
  margin-top: 0.1rem;
  margin-bottom: 0;
}

#contentSection {
  width: 100%;
  min-height: 100px;
  margin-top: 1.5rem;
  margin-bottom: 1.5rem;
}

#footnotes {
  display: flex;
  flex-direction: row;
  justify-content: space-between;
  align-items: center;
  height: 50px;
  width: 100%;
}

#crudBtns {
  display: flex;
  flex-direction: row;
  justify-content: flex-start;
  align-items: center;
  height: 100%;
}

#userImage {
  display: inline-block;
  width: 3rem;
  height: 3rem;
  border-radius: 0.4rem;
}

#authorInfoView {
  display: flex;
  align-items: center;
  height: 3rem;
  margin-top: 1.2rem;
}

#likeContainer {
  display: flex;
  flex-direction: column;
  justify-content: flex-start;
  align-items: center;
  width: 60px;
  height: 100%;
  padding-top: 1rem;
  padding-right: 15px;
  padding-left: 15px;
}

#tagContainer {
  display: flex;
  flex-direction: row;
  justify-content: flex-start;
  align-items: center;
  height: 35px;
}

#editBtn {
  background-color: rgba(112, 75, 245, 0.616);
}

#editBtn:hover {
  background-color: rgba(112, 75, 245, 0.8);
}

#deleteBtn {
  background-color: rgba(112, 75, 245, 0.616);
}

#deleteBtn:hover {
  background-color: rgba(112, 75, 245, 0.8);
}

.post {
  padding-top: 2rem;
}

.comment {
  padding-top: 0.4rem;
}

.flex-grow-1 {
  flex-grow: 1;
}

.container-info {
  width: 100%;
  display: flex;
  flex-direction: row;
  justify-content: flex-start;
  margin-bottom: 15px;
}

.container-title {
  width: 90%;
  height: auto;
  padding-top: 5px;
  display: flex;
  flex-direction: row;
  justify-content: flex-start;
}

.container-contents {
  width: 100%;
  height: auto;
  padding-top: 20px;
  padding-bottom: 20px;
  display: flex;
  flex-direction: row;
  justify-content: flex-start;
}

.font-title {
  display: inline-block;
  font-family: "Poppins", sans-serif;
  font-weight: 600;
  margin: 0 0 0 0;
  padding: 0 0 0 0;
  color: rgb(0, 3, 39);
  line-break: normal;
  word-break: break-all;
}

.style-contents {
  display: inline-block;
  text-overflow: ellipsis;
  width: 100%;
  word-break: break-all;
  white-space: pre-line;
  overflow: hidden;
}

.crud-icon {
  display: inline-flex;
  justify-content: center;
  align-items: center;
  object-fit: cover;
  width: 16px;
  height: 16px;
}

.crud-link {
  display: flex;
  justify-content: center;
  align-items: center;
  margin-right: 15px;
  height: 35px;
  cursor: pointer;
  border-radius: 3px;
  color: white;
}

.crud-link:hover {
  color: white;
}

.font-info {
  font-family: "Poppins", sans-serif;
  font-size: 14;
  margin-left: 10px;
  margin-bottom: 0;
}

.like-icon {
  width: 30px;
  height: 30px;
  margin: 0 0 0 0;
  margin-right: 1px;
  cursor: pointer;
}

.padding-6px {
  display: flex;
  justify-content: center;
  align-items: center;
  width: 100%;
  height: 100%;
  display: flex;
  padding: 10px 10px 10px 10px;
}

.icon-margin {
  margin-right: 0.3rem;
}
</style>

