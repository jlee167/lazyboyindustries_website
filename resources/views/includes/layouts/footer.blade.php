<article id="footer-container">
  <div id="footer-contents">
    <div id="tech-stacks">
      <p class="footer-column-header">Built With</p>
      <section class="tech-stack-item">
        <a class="tech-stack-imgsize">
          <img class="tech-stack-imgsize"
            src="{{ asset('/images/php_logo.png') }}" />
        </a>
        <p class="tech-stack-label">PHP 8.0</p>
      </section>
      <section class="tech-stack-item">
        <a class="tech-stack-imgsize">
          <img class="tech-stack-imgsize"
            src="{{ asset('/images/laravel_logo.png') }}" />
        </a>
        <p class="tech-stack-label">Laravel 8</p>
      </section>
      <section class="tech-stack-item">
        <a class="tech-stack-imgsize">
          <img class="tech-stack-imgsize"
            src="{{ asset('/images/vue_logo.png') }}" />
        </a>
        <p class="tech-stack-label">Vue.js 2.6</p>
      </section>
    </div>

    <div id="footer-contact">
      <p class="footer-column-header">Contact</p>
      <section class="mb-10px">
        <img class="footer-contanct-icon" src="/images/icon-building.svg" />
        <p class="footer-contact-label">LazyBoy Co.Ltd</p>
      </section>
      <section class="mb-10px">
        <img class="footer-contanct-icon" src="/images/icon-mail.svg" />
        <p class="footer-contact-label">lazyboyindustries.main@gmail.com</p>
      </section>
      <section class="mb-10px">
        <img class="footer-contanct-icon" src="/images/icon-phone.svg" />
        <p class="footer-contact-label">010-xxxx-xxxx</p>
      </section>

      <div class="mt-20px">
        <a href="https://github.com/jlee167" class="icon-25px">
          <img class="footer-contanct-icon"
            src="{{ asset('/images/GitHub-Mark-Light-32px.png') }}" />
        </a>
        <a href="https://github.com/jlee167" class="icon-25px">
          <img class="footer-contanct-icon"
            src="{{ asset('/images/linkedin.svg') }}">
        </a>
        <a href="https://github.com/jlee167" class="icon-25px">
          <img class="footer-contanct-icon"
            src="{{ asset('/images/google.svg') }}">
        </a>

      </div>
    </div>

    <div id="footer-register">
      <p id="signupPrompt">Want to participate in my projects?</p>
      <a id="footer-signBtn" class="btn btn-outline-success"
        href="/views/register" role="button">
        Sign up!
      </a>
    </div>

  </div>
  <div id="copyrightContainer" class="container">
    <hr>
    <p id="copyright">
      Copyright 2023 Lazyboy Industries. All Rights Reserved
    </p>
  </div>
</article>
