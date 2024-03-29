@include('includes.imports.strings.resume_strings')

<article id="resumeApp">
  <transition id="resume-template" name="modal" v-if="showModal" v-cloak>
    <div class="background">
      <div class="resume-wrapper">
        <div class="resume-container">
          <div class="resume-header">
            <div
              onclick="setTimeout(
                                function(){
                                    /* Enable scroll when modal is closed */
                                    if (!window.hasOwnProperty('fullScroll'))
                                        document.body.style.overflowY = 'scroll';
                                    else
                                        scrollApp.addEvents();
                                },
                                200
                            )"
              class="btn btn-danger mb-20px" @click="showModal=false"> Close
            </div>
          </div>

          <div class="resume-body">
            <!-- Personal Info -->
            <div class="overflow-hidden h-80vh">
              <div class="overflow-hidden d-flex w-100 h-100">
                <article class="resume-sidebar">
                  <div
                    class="d-flex flex-row justify-content-center overflow-hidden">
                    <!--img id="resumeFaceshot"
                      src="{{ asset('/images/logo_sitting_small.png') }}"-->
                    <img id="profilePicture"
                      src="{{ asset('/images/logo_sitting_small.png') }}">
                  </div>
                  <h2 class="title-font"> LazyBoy </h2>
                  <hr class="divider-sidebar">

                  <a class="section-link" href="#profile">
                    <h5 class="mb-3"><b>Overview</b> </h5>
                  </a>
                  <a class="section-link" href="#rtl">
                    <h5 class="mb-3"><b>RTL / Digital</b></h5>
                  </a>
                  <a class="section-link" href="#hardware">
                    <h5 class="mb-3"> <b>Hardware</b></h5>
                  </a>
                  <a class="section-link" href="#software">
                    <h5 class="mb-3"><b>Software</b></h5>
                  </a>
                </article>

                <article class="resume-contents">
                  <div class="contents-padding">
                    <section id="profile" class="no-overflow-x">
                      <h2 class="mb-6 header-resume-paragraph"> <b> Profile </b>
                      </h2>
                      <p>
                        {!! ResumeStrings::$intro !!}
                      </p>
                    </section>


                    <section id="rtl"
                      class="section-margin no-overflow-x">
                      <header>
                        <h2 class="mb-6 header-resume-paragraph"> <b> RTL /
                            Digital Logics </b></h2>
                      </header>
                      <div class="resume-section-contents">
                        <div class="resume-section-text pr-5">
                          {!! ResumeStrings::$rtlSkills !!}
                        </div>
                        <div class="resume-gadget-section">
                          <h5 class="mb-2">Hardware Languages</h5>
                          <skill-level name="Systemverilog" level=4>
                          </skill-level>
                          <skill-level name="Verilog" level=4></skill-level>
                          <skill-level name="VHDL" level=1 class="mb-4">
                          </skill-level>

                          <h5 class="mt-5 mb-2">Protocols</h5>
                          <div class="d-flex flex-row flex-wrap">
                            <post-tag :append-hash-tag="false" name="UART"
                              class="mb-2"></post-tag>
                            <post-tag :append-hash-tag="false" name="I2C"
                              class="mb-2"></post-tag>
                            <post-tag :append-hash-tag="false" name="SPI"
                              class="mb-2"></post-tag>
                            <post-tag :append-hash-tag="false" name="USB"
                              class="mb-2"></post-tag>
                            <post-tag :append-hash-tag="false" name="Ethernet"
                              class="mb-2"></post-tag>
                            <post-tag :append-hash-tag="false" name="MIPI"
                              class="mb-2"></post-tag>
                            <post-tag :append-hash-tag="false"
                              name="Parallel Camera" class="mb-2"></post-tag>
                          </div>

                          <h5 class="mt-5 mb-2">IC</h5>
                          <div class="d-flex flex-row flex-wrap">
                            <post-tag :append-hash-tag="false" name="7-Series"
                              class="mb-2"></post-tag>
                            <post-tag :append-hash-tag="false" name="Zynq 7000"
                              class="mb-2"></post-tag>
                            <post-tag :append-hash-tag="false" name="Ultrascale"
                              class="mb-2"></post-tag>
                            <post-tag :append-hash-tag="false" name="Lattice"
                              class="mb-2"></post-tag>
                            <post-tag :append-hash-tag="false" name="Cortex-A"
                              class="mb-2"></post-tag>
                            <post-tag :append-hash-tag="false" name="Cortex-M"
                              class="mb-2"></post-tag>
                          </div>
                        </div>
                      </div>
                    </section>


                    <section id="hardware"
                      class="section-margin no-overflow-x">
                      <h2 class="mb-6 header-resume-paragraph"> <b> Hardware
                        </b></h2>
                      <div class="resume-section-contents">
                        <div class="resume-section-text pr-5">
                          {!! ResumeStrings::$hwSkills !!}
                        </div>
                        <div class="resume-gadget-section">
                          <h5 class="mb-2">Hardware Skills</h5>
                          <skill-level name="Digital Electronics" level=4>
                          </skill-level>
                          <skill-level name="Analog Electronics" level=2>
                          </skill-level>
                          <skill-level name="PCB Artwork" level=3></skill-level>
                          <skill-level name="Signal Processing" level=2>
                          </skill-level>
                          <skill-level name="High Speed Design" level=2
                            class="mb-5"></skill-level>

                          <h5 class="mb-2">Design Tools</h5>
                          <div class="d-flex flex-row flex-wrap">
                            <post-tag :append-hash-tag="false" name="OrCad"
                              class="mb-2"></post-tag>
                            <post-tag :append-hash-tag="false" name="Eagle CAD"
                              class="mb-2"></post-tag>
                            <post-tag :append-hash-tag="false" name="KiCAD"
                              class="mb-2"></post-tag>
                          </div>
                        </div>
                      </div>
                    </section>

                    <section id="software"
                      class="section-margin no-overflow-x mb-md-10">
                      <h2 class="mb-6 header-resume-paragraph"> <b> Software
                        </b></h2>
                      <div class="resume-section-contents">
                        <div class="resume-section-text pr-5">
                          {!! ResumeStrings::$swSkills !!}
                        </div>
                        <div class="resume-gadget-section">
                          <h5 class="mb-2">General</h5>
                          <skill-level name="C" level=4></skill-level>
                          <skill-level name="C++" level=2></skill-level>
                          <skill-level name="Python" level=3></skill-level>
                          <skill-level name="JAVA" level=3></skill-level>
                          <skill-level name="C#" level=1></skill-level>
                          <skill-level name="ARM Assemlby" level=1
                            class="mb-5"></skill-level>

                          <h5 class="mb-2">Web</h5>
                          <skill-level name="HTML" level=2></skill-level>
                          <skill-level name="CSS" level=1></skill-level>
                          <skill-level name="Javascript" level=3></skill-level>
                          <skill-level name="PHP" level=3 class="mb-5">
                          </skill-level>

                          <h5 class="mb-2">Operating System</h5>
                          <div class="d-flex flex-row flex-wrap">
                            <post-tag :append-hash-tag="false" name="Linux"
                              class="mb-2"></post-tag>
                            <post-tag :append-hash-tag="false" name="Android"
                              class="mb-2"></post-tag>
                            <post-tag :append-hash-tag="false" name="RTOS"
                              class="mb-2"></post-tag>
                            <post-tag :append-hash-tag="false" name="Windows"
                              class="mb-2"></post-tag>
                          </div>

                          <h5 class="mt-5 mb-2">Databases</h5>
                          <div class="d-flex flex-row flex-wrap">
                            <post-tag :append-hash-tag="false" name="MySQL"
                              class="mb-2"></post-tag>
                            <post-tag :append-hash-tag="false" name="MongoDB"
                              class="mb-2"></post-tag>
                            <post-tag :append-hash-tag="false" name="Redis"
                              class="mb-2"></post-tag>
                          </div>
                        </div>
                      </div>
                    </section>
                  </div>
                </article>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </transition>
</article>

<script defer>
  // register modal component
  Vue.component('modal', {
    template: '#resume-template'
  })

  // start app
  window.modalApp = new Vue({
    el: '#resumeApp',
    data: {
      showModal: false,
      hdlCategoryStr: "Hardware Languages",
      digitalPlatformStr: "Platforms",
      digitalProtocolStr: "Protocols",
      hardwareSkillStr: "Hardware Skills",
      hardwareToolStr: "Hardware Design Tools",
      softwareLanguageStr: "Software Languages",
      operatingSystemStr: "Operating Systems",
      databaseStr: "Databases",
      webProgrammingStr: "Web Programming",


      hdlSkills: [{
        name: "SytemVerilog",
        description: "Professional experience"
      }, {
        name: "Verilog",
        description: "Professional experience"
      }, {
        name: "VHDL",
        description: "Basic usages"
      }],

      digitalPlatforms: [{
        name: "Xilinx",
        description: "7-Series, Zynq Ultrascale, Zynq 7000"
      }, {
        name: "Arm",
        description: "Cortex-A, Cortex M"
      }, {
        name: "Lattice",
        description: "ICE40"
      }],

      digitalProtocols: [{
        name: "Low Speed",
        description: "UART, I2C, SPI"
      }, {
        name: "High-Speed",
        description: "USB, Ethernet"
      }, {
        name: "Imaging",
        description: "MIPI, Parallel Camera"
      }],

      softwareLanguages: [{
        name: "C",
        description: "Professional experience in embedded systems"
      }, {
        name: "C++",
        description: "Academic experience"
      }, {
        name: "Python",
        description: "Academic experience and personal project"
      }, {
        name: "Java",
        description: "Personal project"
      }, {
        name: "C#",
        description: "Beginner level experience in professional environment"
      }, {
        name: "ARM Assembly",
        description: "Basic usage"
      }],

      hardwareSkills: [{
        name: "Digital Electronics",
        description: ""
      }, {
        name: "Analog Electronics",
        description: ""
      }, {
        name: "PCB Artwork",
        description: ""
      }, {
        name: "Signal Processing",
        description: ""
      }, {
        name: "High Speed Design",
        description: ""
      }],

      hardwareStandards: [{
        name: "LVDS",
        description: ""
      }, {
        name: "SSTL",
        description: ""
      }, {
        name: "DDR3",
        description: ""
      }],

      hardwareTools: [{
        name: "OrCAD",
        description: ""
      }, {
        name: "KiCAD",
        description: ""
      }, {
        name: "EagleCAD",
        description: ""
      }],

      operatingSystems: [{
        name: "Linux",
        description: ""
      }, {
        name: "Android",
        description: ""
      }, {
        name: "Windows",
        description: ""
      }, {
        name: "RTOS",
        description: ""
      }],

      webProgramming: [{
        name: "HTML",
        description: ""
      }, {
        name: "CSS",
        description: ""
      }, {
        name: "Javascript",
        description: ""
      }, {
        name: "PHP",
        description: ""
      }],

      webPlatforms: [
        "Apache",
        "NodeJS",
        "Laravel Framework",
        "Bootstrap"
      ],

      databases: [{
        name: "MySQL / MariaDB",
        description: ""
      }, {
        name: "MongoDB",
        description: ""
      }, {
        name: "Redis",
        description: ""
      }, {
        name: "TigerGraph",
        description: ""
      }]
    }
  })
</script>
