<html>

<head>
  @include('includes.imports.env')
  @include('includes.imports.styles_common')
  <link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet" type="text/css" />
  <script src="{{ mix('js/verify-email.js') }}"></script>

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
    <article>
      <h1>Verify Your Email</h1>
      <p>Email verification link has been sent to your email.
        <br> Please check your inbox.</p>
      <div class="form-group">
        <button class="btn btn-primary" onclick="requestNewEmail();">
          Resend Email</button>
      </div>
    </article>
  </main>
  @include('includes.layouts.footer')
</body>

</html>
