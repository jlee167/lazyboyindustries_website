<!doctype html>

<html style="height:100%;">

<head>
  @include('includes.imports.env')
  @include('includes.imports.styles_common')
  <link rel="stylesheet" href="/css/login.css">
  <link rel="stylesheet" href="/css/register.css">
  <link rel="preload" href="/images/pexels-henri-mathieusaintlaurent-5898313.jpg" as="image" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />

  {{-- OAuth2 --}}
  <script src="//developers.kakao.com/sdk/js/kakao.min.js"></script>
  <script src="https://apis.google.com/js/api:client.js"></script>

  <script src="/js/register.js" type="text/javascript"></script>
</head>


<body>
  @include('includes.layouts.navbar')

  <main id="main">
    <article id="registerPrompt" class="card">
      <form id="registerForm">
        <div id="formFrame">
          <div><p class="login-label mt-1">ID</p></div>
          <input class="form-login login-form-input mb-4" id="input_account" type="text"
            placeholder="Enter username" aria-describedby="search-btn">

          <div><p class="login-label mt-1">Email</p></div>
          <input class="form-login login-form-input mb-4" id="input_email" type="text" placeholder="Enter Email"
            aria-describedby="search-btn">

          <div><p class="login-label mt-1">Password</p></div>
          <input class="form-login login-form-input mb-4" id="input_password" type="password"
            placeholder="Enter password" aria-describedby="search-btn">

          <div><p class="login-label mt-1">Confirm Password</p></div>
          <input class="form-login login-form-input mb-4" id="confirm_password" type="password"
            placeholder="Confirm password" aria-describedby="search-btn">

          <button id="registerBtn" type="button" class="btn rounded-0" onclick="javascript:register(null, null)">
            Register (No SNS Link)
          </button>

          <div class="d-flex flex-row justify-content-center align-items-center mt-4 mb-2">
            <hr class="label-wrapper-line">
            <small class="m-auto">or</small>
            <hr class="label-wrapper-line">
          </div>


          {{-- Kakao Signin Button --}}
          <a id="kakaoAuthBtn" class="hover-no-effect" href="javascript:loginWithKakao()">
            <div id="kakaoBtnBackground" class="btn-hover-shadow">
              <img class="icon-kakao" src="{{asset('/images/kakao_icon.png')}}">
              <span class="buttonText"> Register with Kakao Account Link</span>
            </div>
          </a>


          {{-- Google Signin Button --}}
          <div id="gSignInWrapper" class="mt-2">
            <div class="btn-hover-shadow" id="googleAuthBtn" class="customGPlusSignIn">
              <img class="icon-google" src="https://developers.google.com/identity/images/g-logo.png">
              <span class="buttonText"> Register with Google Account Link</span>
            </div>
          </div>
          <div id="name"></div>

          <div id="progressSpinner" class="d-flex justify-content-center align-items-center mt-4">
            <div class="spinner-border text-primary mr-3" role="status">
              <span class="sr-only"></span>
            </div>
            <h6>Registering...</h6>
          </div>
        </div>
      </form>
    </article>
  </main>

  @include('includes.layouts.footer')
</body>

<script>
  setupGoogleAuth(window.env.GOOGLE_APP_KEY, "googleAuthBtn", registerWithGoogle);
  setupKakaoAuth(window.env.KAKAO_APP_KEY, registerWithKakao);
  document.getElementById('progressSpinner').style.visibility = "hidden";
</script>


<html>
