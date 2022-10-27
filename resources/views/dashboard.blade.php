<html>

<head>
  @include('includes.imports.env')
  @include('includes.imports.styles_common')

  <link rel="stylesheet" href="/css/dashboard.css">

  <link
    href="https://cdn.jsdelivr.net/npm/summernote@0.8.16/dist/summernote-bs4.min.css"
    rel="stylesheet">
  <script
    src="https://cdn.jsdelivr.net/npm/summernote@0.8.16/dist/summernote-bs4.min.js">
  </script>
  <script defer src="{{ mix('js/forum.js') }}"></script>
  <script defer src="{{ mix('js/app.js') }}"></script>
</head>


<body>
  @include('includes.layouts.navbar')

  <div id="contents-area" class="w-100" v-if="loaded">
    <!--article class="searchbar">
    </article-->

    <article class="forum-background">
      {{-- Forum Overview Section --}}
      <div class="section-contents forum-container" v-cloak>
        <transition name="fade">
          {{-- Post List View --}}
          <div v-if="showForum" class="post-list">

            <section id="forumSelect">
              <button-array class="ml-2" :forums="forumList"
                :default-forum="defaultForum" @forum-select="changeForum">
              </button-array>
            </section>

            <hr class="forum-divider">

            <section class="d-flex flex-row align-items-center justify-content-between">
              <div id="searchForm">
                <input id="forumSearch" ref="searchInput" type="text"
                  placeholder="Search By Title" aria-describedby="search-btn"
                  @keyup.enter="handleSearchEvent">
                <img id="searchBtn" src="/images/icon-search.svg"
                  @click="handleSearchEvent" />
              </div>

              <a id="postBtn" class="btn" role="button"
                :href="'createpost?forum=' + forumName"> Create Post</a>
            </section>
            <div v-if="searchKeyword" class="mt-5">
                <div>
                    <h6>Keyword : </h6>
                </div>
                <div id="searchKeyword" class="mt-1"
                    @click="removeKeyword">
                    @{{ searchKeyword }}
                    <img src="/images/x-circle.svg" />
                </div>
            </div>

            <forum-post-list :posts="posts" :on-post-click="watchPost"
              :forum-name="forumName" :search-keyword="searchKeyword"
              :remove-keyword="removeKeyword">
            </forum-post-list>

            <nav id="pageNavigator">
              <ul class="pagination justify-content-center">
                <li class="page-item">
                  <a class="page-link pointer" onmouseover=""
                    @click="getNewestPage()">
                    << </a>
                </li>
                <template v-for="page_idx in pageIndexes">
                  <li
                    :class="[isCurrentPage(page_idx) ? 'page-item active' : 'page-item']">
                    <a class="page-link pointer" @click="getNewPage(page_idx)">
                      @{{ page_idx }}
                    </a>
                  </li>
                </template>

                <li class="page-item">
                  <a class="page-link pointer" onmouseover=""
                    @click="getOldestPage()">
                    >>
                  </a>
                </li>
              </ul>
            </nav>
          </div>


          {{-- Post Contents View --}}
          <div v-if="showPost" class="posts-and-comments">
            <span class="bounce content-label back-btn mb-4" @click="watchForum"
              onmouseover=""> &#x2190; BACK
            </span>

            <forum-post :post="currentPost" :likes="likes"
              :my-like="myLike" :update-post="updatePost"
              :delete-post="deletePost" :toggle-like="toggleLike">
            </forum-post>

            <form id="commentWriter" action="/forum/general/post"
              enctype="multipart/form-data"> @csrf
              <input type="hidden" id="post_root" name="post_root"
                value="0">
              <input type="hidden" id="post_parent" name="post_parent"
                value="0">

              @auth
                <label for="content" class="content-label">Post Comment</label>
                <br><br>
                <summer-note ref="summernote" :height="200">
                </summer-note>
                <input class="btn btn-primary create-post-btn mt-4 float-right"
                  @click="postComment();" value="submit">
              @endauth

              @guest
                <label class="content-label" for="content">
                  <a href="/views/login">Login</a> to post comment!
                  <br>
                </label>
              @endguest

            </form>

            <p v-if="hasComments" class="content-label mt-5">Comments</p>
            <div v-for="comment in comments" :key="comment.id">
              <forum-post :update-post="updatePost" :delete-post="deletePost"
                :post="comment"></forum-post>
            </div>
          </div>
        </transition>


        {{-- Side Items --}}
        <article id="forumSideItems">
          <trending-posts :title="topPostLabel" :contents="topPosts">
          </trending-posts>
          <trending-posts :title="trendingPostLabel"
            :contents="trendingPosts"></trending-posts>
        </article>
      </div>
    </article>

    @include('includes.layouts.footer')
  </div>

</body>

</html>
