<html>

<head>
  @include('includes.imports.env')
  @include('includes.imports.styles_common')
  <link
    href="https://cdn.jsdelivr.net/npm/summernote@0.8.16/dist/summernote-bs4.min.css"
    rel="stylesheet">
  <script
    src="https://cdn.jsdelivr.net/npm/summernote@0.8.16/dist/summernote-bs4.min.js">
  </script>
  <link rel="stylesheet" href="/css/peers.css">
  <script>
    <?php
    echo 'let uid = ' . Auth::id() . ';';
    ?>
  </script>
  <script src="/js/peers.js" defer></script>
</head>



<body>
  @include('includes.layouts.navbar')

  <main id="peer-page-content" class="section-contents">
    <article id="peer-list-section" v-cloak>
      <div id="menu" class="d-flex flex-row align-items-center mb-5">
        <h4 :class="{ activetab: tab == 0 }" class="margin-right-2rem pointer white mb-0px"
          onclick="peerApp.tab = 0; peerApp.activeGroup = peerApp.protecteds;">
          Protected </h4>

        <h4 :class="{ activetab: tab == 1 }" class="margin-right-2rem pointer white mb-0px"
          onclick="peerApp.tab = 1; peerApp.activeGroup = peerApp.guardians;">
          Guardian </h4>

        <h4 :class="{ activetab: tab == 2 }" class="margin-right-2rem pointer white mb-0px"
          onclick="peerApp.tab = 2"> Incoming </h4>

        <h4 :class="{ activetab: tab == 3 }" class="pointer white mb-0px"
          onclick="peerApp.tab = 3">
          Sent </h4>

        <p class="btn btn-primary ml-5 align-middle mb-0px" onclick="peerApp.tab = 4"> Add</p>
      </div>

      <peer-list v-if="tab==0" :peers="protecteds"></peer-list>

      <section v-if="tab==1" class="request-container">
        <div v-for="guardian in guardians" :key="guardian.id"
          class="request">
          <div
            class="d-flex flex-row justify-content-center align-items-center ml-4">
            <img :src="guardian.image_url" class="pending-request-img mr-4"/>
            <p class="username mt-auto mb-auto mr-4 align-middle">
              @{{ guardian.username }}</p>
          </div>
        </div>
      </section>

      <section v-if="incomingTab" class="request-container">
        <div v-for="request in incomingRequests" :key="request.uid"
          class="request">
          <div
            class="d-flex flex-row justify-content-center align-items-center ml-4">
            <img :src="request.imageUrl" class="pending-request-img mr-4"/>
            <p class="username mt-auto mb-auto mr-4 align-middle">
              @{{ request.username }}</p>
            <p v-if="request.protected"
              class="btn btn-outline-danger mt-auto mb-auto align-middle rounded-0">
              Protected</p>
            <p v-if="request.guardian"
              class="btn btn-outline-primary mt-auto mb-auto align-middle rounded-0">
              Guardian</p>
          </div>
          <div
            class="d-flex flex-row justify-content-center align-items-center mr-4">
            <a @click="respond(request.id, 'ACCEPTED')"
              class="btn btn-success mr-4">accept</a>
            <a @click="respond(request.id, 'DENIED')"
              class="btn btn-danger">decline</a>
          </div>
        </div>
      </section>

      <section v-if="sentTab" class="request-container">
        <div v-for="request in sentRequests" :key="request.uid"
          class="request">
          <div
            class="d-flex flex-row justify-content-center align-items-center ml-4">
            <img :src="request.imageUrl" class="pending-request-img mr-4"/>
            <p class="username mt-auto mb-auto mr-4 align-middle">
              @{{ request.username }}</p>
            <p v-if="request.protected"
              class="btn btn-outline-danger mt-auto mb-auto align-middle rounded-0">
              Protected</p>
            <p v-if="request.guardian"
              class="btn btn-outline-primary mt-auto mb-auto align-middle rounded-0">
              Guardian</p>
          </div>
        </div>
      </section>

      <section v-if="tab == 4" class="form-container">
        <p style="color:white;">Request another user to be my guardian</p>
        <div class="d-flex align-items-center mb-5">
          <input v-model="guardianUsername"
            class="form-control d-inline-block mr-2"
            style="width: 200px !important;" placeholder="Enter Username" />
          <button class="btn btn-success d-inline-block"
            @click="addGuardian(guardianUsername)">
            Request </button>
        </div>

        <p style="color:white;">Pledge to protect anther user</p>
        <div class="d-flex align-items-center">
          <input v-model="protectedUsername"
            class="form-control d-inline-block mr-2"
            style="width: 200px !important;" placeholder="Enter Username" />
          <button class="btn btn-success d-inline-block"
            @click="addProtected(protectedUsername)">
            Request </button>
        </div>
      </section>

    </article>
  </main>
  @include('includes.layouts.footer')

</body>


</html>
