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
                    Years of working experience in FPGA engineering.
                    First-hand experience with various serial and bus protocols.
                    Proficient in Xilinx platform, but can adapt to different environments quickly.
                  </p>
                </div>
              </div>

              <div id="skill2" ref="skill2" class="skill-item ">
                <img class="img-skills"
                  src="{{ asset('/images/HARDWARE.jpg') }}">
                <div class="skill-desc-container">
                  <h1 class="skill-desc-header"> Hardware</h1>
                  <p class="skill-desc-details">
                    I can design Analog/Digital circuits with high speed requirements.
                    PCB design and production service are provided along with schematics.<br>
                  </p>
                </div>
              </div>

              <div id="skill3" ref="skill3" class="skill-item ">
                <img class="img-skills"
                  src="{{ asset('/images/SOFTWARE.png') }}">
                <div class="skill-desc-container">
                  <h1 class="skill-desc-header"> Software</h1>
                  <p class="skill-desc-details">
                    Able to program in various platforms from low-level MCU environment to
                    user applications on mobile or desktop platform. Also proficient in web programming (front-end and back-end).
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
            <h2 id="section3Header"> Links </h2>
            <div class="flex-column flex-center-vh">

              <div id="linkedinRef" class="ref-link flex-row flex-center-vh"
                onclick="window.location.href='https://www.linkedin.com/in/jihoon-lee-25467a157/'">
                <img class="footer-contanct-icon" src="{{ asset('/images/linkedin.svg') }}">
                <a class="ref-hyperlink ml-2">LinkedIn</a>
              </div>
              <div id="githubRef" class="ref-link mt-3 flex-row flex-center-vh"
                onclick="window.location.href='https://github.com/jlee167'">
                <img class="footer-contanct-icon" src="{{ asset('/images/GitHub-Mark-Light-32px.png') }}" />
                <a class="ref-hyperlink ml-2">Github</a>
              </div>
            </div>
          </div>
        </div>
      </section>
    </article>
  </main>
</body>

</html>
