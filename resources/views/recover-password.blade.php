<html>

<head>
    @include('includes.imports.env')
    @include('includes.imports.styles_common')
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
        <form action="/forgot-password" method="POST">
            @csrf
            <div class="form-group">
                <small id="hint" class="form-text text-muted">Enter your email.</small>
                <input id="email" name="email" type="text" class="form-control" />
            </div>
            <div class="form-group">
                <div class="btn btn-primary" onclick="window.requestPasswordReset()"></div> <!--type="submit" value="submit"-->
            </div>
        </form>
    </main>
    @include('includes.layouts.footer')
</body>


<script>
    window.requestPasswordReset = () => {
      fetch('/forgot-password', {
        method: "post",
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': window.env.CSRF_TOKEN
        },
        body: JSON.stringify({
          email: document.getElementById("email")
        })
      })
      .then(response => {
        if (response.status === 200) {
          window.alert("Request sent. Please check your email inbox!");
          window.location.href = window.location.href.split('/')[2];
        }
      })
      .catch((err) => {
        window.alert(err);
      });
    }
  </script>

</html>
