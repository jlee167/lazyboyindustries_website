<html>

<head>
  <meta charset="utf-8">
  @include('includes.imports.env')
  @include('includes.imports.styles_common')
  @include('includes.imports.headers.cdn.summernote')
  @include('includes.imports.headers.titles.default')

  <meta name="viewport" content="width=device-width, initial-scale=1">

  <link rel="stylesheet" type="text/css" href="/css/create-post.css" />
  <script src="{{ mix('js/create-post.js') }}" defer></script>
</head>

<body class="background-createpost">
  @include('includes.layouts.navbar')

  <main id="createPostApp" class="form-container">
    <form class="create-post-form" action="/forum/general/post"
      enctype="multipart/form-data" method="POST">
      @csrf
      <select ref="forumName" name="type"
        class="vue-input-tag-wrapper no-outline d-flex flex-row align-middle mb-2 pl-2 w-100 h-40px">
        <option value="general">General Forum</option>
        <option value="tech">Tech Forum</option>
      </select>
      <input id="title" ref="title"
        class="vue-input-tag-wrapper no-outline mb-2 pl-2 w-100 h-40px"
        type="text" name="title" placeholder="Title">
      <input-tag v-model="tags" :limit="3" class="mb-2 pl-2 h-40px"
        id="tags" placeholder="Write tag and press TAB (up to 3 tags)">
      </input-tag>
      <summer-note ref="summernote" :height="400"></summer-note>
      <input class="btn btn-primary create-post-btn" @click="submitPost()"
        value="submit">
    </form>
  </main>

  @include('includes.layouts.footer')
</body>
<html>
