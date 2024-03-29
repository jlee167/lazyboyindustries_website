<html>

<head>
  @include('includes.imports.env')
  @include('includes.imports.styles_common')
  @include('includes.imports.headers.titles.default')


  <link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet" type="text/css" />

  <style>
    #container {
      overflow: visible;
      display: flex;
      flex-direction: row;
      align-items: center;
      justify-content: center;
      width: 100vw;
      min-height: 100vh;
    }
  </style>
</head>


<body>
  @include('includes.layouts.navbar')
  <main id="container" class="section-contents">
    <form action="/reset-password" method="POST">
      @csrf
      <div class="form-group">
        <input name="token" type="hidden" class="form-control" />
        <small class="form-text text-muted">New password</small>
        <input id="password" name="password" type="password" class="form-control" />
        <small class="form-text text-muted">Confirm new password</small>
        <input id="passwordConfirmation" type="password" class="form-control" />
      </div>
      <div class="form-group">
        <div class="btn btn-primary" onclick="resetPassword()">Send</div>
      </div>
    </form>
  </main>
  @include('includes.layouts.footer')
</body>

<script>
  const url = new URL(window.location.href);
  const email = url.searchParams.get('email');
  const token = window.location.href.split("/").pop().split("?").shift();

  window.resetPassword = () => {
    fetch('/reset-password', {
      method: "post",
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': window.env.CSRF_TOKEN
      },
      body: JSON.stringify({
        token: token,
        email: email,
        password: document.getElementById('password').value,
        password_confirmation: document.getElementById('passwordConfirmation').value,
      })
    })
    .then(response => {
      if (response.status === 200) {
        window.alert("Password Reset Complete");
        window.location.href = '/views/login';//window.location.href.split('/')[2];
      }
    })
    .catch((err) => {
      window.alert(err);
    });
  }
</script>

</html>
