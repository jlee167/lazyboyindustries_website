<html>

<head>
  @include('includes.imports.env')
  @include('includes.imports.styles_common')
  @include('includes.imports.headers.titles.default')

  <link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet"
    type="text/css" />
  <link rel="stylesheet" href="/css/user-info.css">

  <script src="/js/user-info.js" defer></script>
  <script
    src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js">
  </script>
</head>




<body class="overflow-hidden">
  @include('includes.layouts.navbar')

  <article id="menuBar">
    <a class="menubar-item pointer text-decoration-none"
      onclick="window.userInfoApp.watchPersonalInfo()">
      <img class="menubar-image" src="{{ asset('/images/person.svg') }}">
      <p class="menubar-text">Personal<br>Info</p>
    </a>

    <a class="menubar-item pointer text-decoration-none"
      onclick="window.userInfoApp.watchChangePassword()">
      <img class="menubar-image" src="{{ asset('/images/lock.svg') }}">
      <p class="menubar-text"> Change<br>Password </p>
    </a>

    <a class="menubar-item pointer text-decoration-none"
      onclick="window.userInfoApp.watchGoogle2FA()">
      <img class="menubar-image" src="{{ asset('/images/mobile.svg') }}">
      <p class="menubar-text"> Google<br>OTP </p>
    </a>

    <a class="menubar-item pointer text-decoration-none"
      onclick="window.userInfoApp.watchDeleteAccount()">
      <img class="menubar-image" src="{{ asset('/images/door-open.svg') }}">
      <p class="menubar-text"> Delete<br>Account </p>
    </a>
  </article>

  <main id="main" class="section-contents">
    <article v-if="PersonalInfoView"
      class="d-flex flex-column align-items-center">
      <img id="profilePicture" :src="user.imageUrl" />

      <label class="mt-3"><small> Change profile image </small></label>
      <input type="file" id="newUserImage" />

      <div id="accountInfo" class="d-flex flex-column mt-3">
        <div class="w-100">
          <section class="form-group">
            <small class="form-text text-muted">ID#</small>
            <input id="userID" v-model="user.id"
              class="form-control  no-outline" disabled />
          </section>

          <section class="form-group">
            <small class="form-text text-muted">Username</small>
            <input id="username" v-model="user.username"
              class="form-control  no-outline" disabled  />
          </section>

          <section class="form-group">
            <small class="form-text text-muted">Email</small>
            <input id="email" v-model="user.email"
              class="form-control  no-outline" disabled />
          </section>

          <section class="form-group">
            <small class="form-text text-muted">OAuth Provider</small>
            <input id="oauthProvider" v-model="user.authProvider"
              class="form-control  no-outline" disabled />
          </section>
        </div>
      </div>
      <button class="btn btn-password-submit mt-3" type="submit"
        @click="changeUserInfo()">Update</button>
    </article>

    <article v-if="ChangePasswordView" id="passwordChange"
      class="mb-5 d-flex flex-column align-items-center">
      <h1> Change Password</h1>
      <div class="d-flex flex-column mt-3 w-100 align-items-center">
        <div class="form-group mt-3 w-100">
          <small class="form-text text-muted">Current Password</small>
          <input id="currentPassword" class="form-control no-outline w-100"
            type="password" />
        </div>
        <div class="form-group w-100">
          <small class="form-text text-muted">New Password</small>
          <input id="newPassword" class="form-control no-outline w-100"
            type="password" />
        </div>
        <div class="form-group w-100">
          <small class="form-text text-muted">Confirm New Password</small>
          <input id="confirmPassword" class="form-control no-outline w-100"
            type="password" />
        </div>
      </div>

      <button class="btn btn-password-submit mt-3" type="submit"
        @click="changePassword()">Submit</button>
    </article>

    <article v-if="Google2FAview" id="passwordChange"
      class="card mb-5 d-flex flex-column align-items-center">
      <div class="d-flex align-items-center">
        <lottie-player
          src="https://assets7.lottiefiles.com/packages/lf20_wpf1kujc.json"
          background="transparent" speed="1"
          style="width: 60px; height: 60px;" loop autoplay>
        </lottie-player>
        <h1> Google OTP</h1>
      </div>
      <div v-if="user.twoFactorAuth" id="otpDesc" class="mt-2 text-center">
        <p>
          You currently have an active Google OTP. <br>
          If you've lost it, there is no way to recover it in beta version.
          <u><b>Please delete your account.</b></u>
          <br>
        </p>
        <button class="btn btn-password-submit" type="submit"
          @click="disable2FA()">
          Disable OTP
        </button>
      </div>

      <div id="QRcodeContainer" class="d-flex flex-column align-items-center"
        v-if="!user.twoFactorAuth">
        <img ref="QRimage" v-show="showQR" />
        <div v-if="qrCodeRecv" class="mt-2 text-center">
          <br>
          <p>Scan QR Code with Google OTP Application. <br>
            <a href="https://play.google.com/store/apps/details?id=com.google.android.apps.authenticator2&hl=ko&gl=US"
              class="d-block;">
              Download Google OTP App Here
            </a>
          </p>

          <article>
            <div class="form-group">
              <small id="hint" class="form-text text-muted mb-2">Enter
                your 6 digit OTP number.</small>
              <input id="otpSecret" class="form-control" />
            </div>
            <div class="form-group">
              <button class="btn btn-primary"
                @click="activate2FA()">submit</button>
            </div>
          </article>
        </div>


        <div v-if="!qrCodeRecv" class="mt-3 text-center">
          <p>
            You are not using Google OTP Authentication. <br>
            Click below button to generate QR Code. <br><br>
            Please have your Google OTP App ready. <br>
            <a
              href="https://play.google.com/store/apps/details?id=com.google.android.apps.authenticator2&hl=ko&gl=US">
              Download Here</a>
          </p>
          <button v-if="!qrCodeRecv" class="btn btn-password-submit"
            type="submit" @click="enable2FA()">
            Request QR Code
          </button>
        </div>
      </div>
    </article>


    <article v-if="DeleteAccountView" id="passwordChange"
      class="card mb-5 d-flex flex-column align-items-center">
      <h1> Delete my account</h1>
      <p class="mt-3 text-center">
        Records are immediately deleted from database in Beta version.
        Your record will not be kept. You can even make another account with
        same username.
      </p>
      <button id="deleteBtn" class="btn btn-password-submit mt-2"
        type="submit" @click="deleteUser()">Goodbye!!!</button>
    </article>
  </main>
</body>


</html>
