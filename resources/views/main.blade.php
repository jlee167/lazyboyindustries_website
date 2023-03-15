<!doctype html>

<html>

<head>
  @include('includes.imports.env')
  @include('includes.imports.styles_common')
  <link rel="stylesheet" type="text/css" href="/css/main.css" />

  <script src="/js/full-page-scroll.js"></script>
  <script src="/js/main.js" defer></script>
  <script type="text/javascript"
    src="https://cdnjs.cloudflare.com/ajax/libs/fullPage.js/4.0.6/fullpage.min.js">
  </script>
  <link rel="stylesheet" type="text/css"
    href="https://cdnjs.cloudflare.com/ajax/libs/fullPage.js/4.0.6/fullpage.css" />
</head>


<body>
  @include('includes.layouts.navbar')

  <main id="main" class="scroll-container">
    <article v-show="!loaded" id="loadingScreen" v-cloak>
      <pulse-loader v-show="!loaded" loading="loading" color="green"
        size="20px"></pulse-loader>
      <h2>Loading...</h2>
    </article>
    <article v-show="loaded" id="mainView" class="w-100 h-100" v-cloak>
      <section id="section1" ref="section1"
        class="scroll-section1 section-center">
        <video id="bgVideo" autoplay muted loop preload="metadata">
          <source src="{{ asset('/images/background-pcb.mp4') }}"
            type="video/mp4">
        </video>
        <div id="logoView" class="scrollable-page">
          <img class="img-logo"
            src="{{ asset('/images/logo_safetyhat.png') }}">
            <scroll-arrow direction="down" :motion-enabled="true" class="mt-5">
            </scroll-arrow>
        </div>
      </section>

      <section id="section2" ref="section2"
        class="scroll-section2 section-center">
        <div class="scrollable-page">
          <div class="contents">
            <div class="preface">
              <h1 class="header-preface"> My expertise </h1>
              <product-card :company="product1.company" :name="product1.name"
                :description="product1.description" :bg-color="product1.bgColor"
                :price="product1.price" :availability="product1.availability"
                :click-event-handler="triggerResumeView">
              </product-card>
            </div>

            <div id="skillContainer" ref="skillContainer"
              class="container-skills">
              <div id="skill1" ref="skill1" class="skill-item ">
                <img class="img-skills" src="{{ asset('/images/RTL.png') }}">
                <div class="skill-desc-container">
                  <h1 class="skill-desc-header"> Digital Logics</h1>
                  <p class="skill-desc-details">
                    3 Years of experience in FPGA engineering.
                    I have experience with some time-critical modules and
                    various protocols.
                  </p>
                </div>
              </div>

              <div id="skill2" ref="skill2" class="skill-item ">
                <img class="img-skills"
                  src="{{ asset('/images/HARDWARE.png') }}">
                <div class="skill-desc-container">
                  <h1 class="skill-desc-header"> Hardware</h1>
                  <p class="skill-desc-details">
                    I'm from an Electrical Engineering background (BSEE).
                    I can design simple digital/analog circuits below 1Ghz.<br>
                  </p>
                </div>
              </div>

              <div id="skill3" ref="skill3" class="skill-item ">
                <img class="img-skills"
                  src="{{ asset('/images/SOFTWARE.png') }}">
                <div class="skill-desc-container">
                  <h1 class="skill-desc-header"> Software</h1>
                  <p class="skill-desc-details">
                    3 Years of experience in FPGA engineering.
                    I have experience with some time-critical modules and
                    various protocols.
                  </p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>

      <section id="section3" ref="section3"
        class="scroll-section3 section-center">
        <canvas class="bg-particle"></canvas>
        <div class="scrollable-page">
          <div class="flex-column flex-center-vh vh-100 vw-100">
            <h1 id="section3Header"> References </h1>
            <div class="flex-column flex-center-vh curr-project-items">
              <div class="flex flex-row justify-content-center">
                <img class="curr-project-image" src="{{ asset('/images/HARDWARE.png') }}" />
                <img class="curr-project-image" src="{{ asset('/images/HARDWARE.png') }}" />
              </div>
              <div class="flex flex-row justify-content-center">
                <img class="curr-project-image" src="{{ asset('/images/HARDWARE.png') }}" />
                <img class="curr-project-image" src="{{ asset('/images/HARDWARE.png') }}" />
              </div>
            </div>
          </div>
        </div>
      </section>
    </article>
  </main>
</body>

</html>
