<!doctype html>

<html>

<head>
  @include('includes.imports.env')
  @include('includes.imports.styles_common')
  @include('includes.imports.headers.titles.default')
  <link rel="stylesheet" href="/css/login.css">

  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <script src="//developers.kakao.com/sdk/js/kakao.min.js"></script>
  <script src="https://apis.google.com/js/api:client.js"></script>

  <script>
    let redirectUrl = "{{ $redirectUrl }}";
  </script>
  <script src="{{ mix('js/login-defer.js') }}" defer></script>
</head>


<body>
  <main id="view-login">
    <article v-show="!resourcesLoaded" id="loadingScreen">
      <pulse-loader loading="loading" color="green" size="20px"></pulse-loader>
      <h2 style="color:white;">Loading...</h2>
    </article>
    <article v-show="resourcesLoaded" :class="{ flex: resourcesLoaded }"
      id="mainView" v-cloak>
      <div class="login-prompt-container">
        <article id="login-manual" class="login-prompt card">
          <form class="login-form">
            <div style="margin:0 auto; float:center;">
              <input class="form-login login-form-input mb-2"
                id="input_account" type="text" placeholder="Username"
                aria-describedby="search-btn" @keyup.enter="nonSocialLogin()">
              <input class="form-login login-form-input mb-2"
                id="input_password" type="password" placeholder="Password"
                aria-describedby="search-btn" @keyup.enter="nonSocialLogin()">
              <button id="loginBtn" type="button"
                class="btn no-outline mb-3" @click="nonSocialLogin()"> Login
              </button>
            </div>
          </form>

          <div
            class="d-flex flex-row justify-content-center align-items-center mt-3 mb-1">
            <hr class="label-wrapper-line">
            <small class="m-auto">or</small>
            <hr class="label-wrapper-line">
          </div>

          <table id="oAuthSection">
            <tr class="d-flex justify-content-center h-50px">
              <td class="m-auto w-100">
                <a id="kakaoAuthBtn" class="hover-no-effect"
                  href="javascript:loginWithKakao()">
                  <div id="kakaoBtnBackground" class="btn-hover-shadow border-08rem-forced">
                    <img class="icon"
                      src="{{ asset('/images/kakao_icon.png') }}"
                      style="display:inline-block; width:22px; height:20px;">
                    <span class="buttonText"> Login with Kakao</span>
                  </div>
                </a>
                <a href="http://alpha-developers.kakao.com/logout"></a>
              </td>
            </tr>

            <tr class="d-flex justify-content-center h-50px mt-2">
              <td class="m-auto w-100">
                <div id="gSignInWrapper">
                  <div class="btn-hover-shadow border-08rem-forced" id="googleAuthBtn"
                    class="customGPlusSignIn">
                    <img id="googleLogo" class="icon"
                      src="https://developers.google.com/identity/images/g-logo.png">
                    <span class="buttonText"> Login with Google</span>
                  </div>
                </div>
                <div id="name"></div>
              </td>
            </tr>
          </table>

          <hr class="mt-90px">
          <section class="d-flex flex-row justify-content-center">
            <p class="bottom-menu-item pointer"
              onclick="window.location.href='/forgot-password'">Recover Password
            </p>
            <p class="bottom-menu-item ml-3 mr-3">|</p>
            <p class="bottom-menu-item pointer"
              onclick="window.location.href='/views/register'"> Create Account
            </p>
          </section>
        </article>
      </div>
    </article>
  </main>


</body>


</html>
