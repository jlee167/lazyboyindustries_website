<html>

<head>
  @include('includes.imports.env')
  @include('includes.imports.styles_common')
  @include('includes.imports.strings.qna_strings')

  <link
    href="https://cdn.jsdelivr.net/npm/summernote@0.8.16/dist/summernote-bs4.min.css"
    rel="stylesheet">
  <script
    src="https://cdn.jsdelivr.net/npm/summernote@0.8.16/dist/summernote-bs4.min.js">
  </script>

  <link rel="stylesheet" href="/css/support.css">
  <script src="/js/support.js"></script>
  <script src="/js/support-defer.js" defer></script>
</head>




<body>
  @include('includes.layouts.navbar')

  <main id="main">
    <article class="contents-support" v-cloak>
      <section id="FAQ">
        <div>
          <h4 class="support-headers" @click="showFAQ()"
            :class="{ 'bottom-indicator': FAQview }"> F.A.Q </h4>
          <h4 class="support-headers" style="margin-left: 1.5em;"
            @click="showRequest()" :class="{ 'bottom-indicator': reqView }">
            Make Requests </h4>

          <qna-dropdown v-show="FAQview" :qna-arr="qnaArr" max-width=600>
          </qna-dropdown>

          <section v-show="reqView">
            <div>
              <form class="w-100" action="/support_request"
                enctype="multipart/form-data" method="POST">
                @csrf
                <input type="hidden" id="postToken" name="postToken">
                <select class="form-control no-outline w-100 mb-3" id="type"
                  name="type" style="width: 150px;">
                  <option value="REPAIR"> Repair </option>
                  <option value="TECH_SUPPORT"> Tech Support </option>
                  <option value="REFUND"> Refund </option>
                  <option value="LEGAL"> Legal </option>
                </select>
                <input class="input-small form-control no-outline mb-3" type="text"
                  id="email" name="email" placeholder="Email">
                <summer-note ref="summernote" :height="200">
                </summer-note>
                <input class="btn btn-info btn-form-submit no-outline mt-4"
                  type="button" value="submit" onclick="submitRequest();">
              </form>
            </div>
          </section>
        </div>
      </section>
    </article>

    @include('includes.layouts.footer')
  </main>


</body>


</html>
