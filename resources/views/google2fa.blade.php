<html>

<head>
  @include('includes.imports.env')
  @include('includes.imports.styles_common')
  <link href="https://fonts.googleapis.com/css?family=Open+Sans"
    rel="stylesheet" type="text/css" />

  <style>
    #container {
      overflow: visible;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      width: 100vw;
      min-height: 100vh;
    }

    #OtpRequestMsg {
      width: 200px;
    }

  </style>
</head>


<body>
  @include('includes.layouts.navbar')

  <main id="container" class="section-contents">

    <article>
      <div class="form-group">
        <small id="hint" class="form-text text-muted">Enter
          your 6 digit OTP number.</small>
        <input id="otpSecret" class="form-control" />
      </div>
      <div class="form-group">
        <button class="btn btn-primary"
          onclick="verify2FaKey(getOtpSecret());">submit</button>
      </div>
    </article>
  </main>

  @include('includes.layouts.footer')
</body>


<script>
  const URI_VERIFY_2FA = '/auth/2fa';

  function getOtpSecret() {
    return String(document.getElementById("otpSecret")
      .value).trim();
  }



  function verify2FaKey(secret) {
    fetch(URI_VERIFY_2FA, {
        method: 'post',

        body: JSON.stringify({
          "secret": secret
        }),

        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': window.env.CSRF_TOKEN
        }
      })
      .then(response => {
        if (response.status == 200) {
          return response.json();
        } else {
          window.alert("Request returned with code " +
            String(response.status));
          return;
        }
      })
      .then(jsonData => {
        if (jsonData.result == true) {
          window.location.href = jsonData.redirectUrl;
        } else {
          window.alert(jsonData.error);
          return;
        }
      })
      .catch((error) => {
        console.error(error);
      });
  }
</script>


</html>
