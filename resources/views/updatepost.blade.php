<html>

<head>
  <meta charset="utf-8">
  @include('includes.imports.env')
  @include('includes.imports.styles_common')
  @include('includes.imports.headers.titles.default')

  <meta name="viewport" content="width=device-width, initial-scale=1">

  <link
    href="https://cdn.jsdelivr.net/npm/summernote@0.8.16/dist/summernote-bs4.min.css"
    rel="stylesheet">
  <script
    src="https://cdn.jsdelivr.net/npm/summernote@0.8.16/dist/summernote-bs4.min.js">
  </script>

  <link rel="stylesheet" href="/css/update-post.css">
  <script src="{{ mix('js/update-post.js') }}" defer></script>
</head>

<body id="background">
  @include('includes.layouts.navbar')
  <main class="w-100">
    <article id="container">
      <form id="updatePostForm" action="/forum/general/post"
        enctype="multipart/form-data" method="POST">
        @csrf
        <label id="titleLabel" class="update-post-label w-100" for="title">Title</label><br>
        <input type="text" id="title" name="title"><br><br>
        <label class="update-post-label w-100" for="content">Content</label><br>
        <textarea id="postContent" name="content"></textarea><br>
        <input id="submitBtn" class="btn btn-primary no-outline"
          onclick="submitPost();" value="submit">
      </form>
    </article>
  </main>
  @include('includes.layouts.footer')
</body>
<html>
