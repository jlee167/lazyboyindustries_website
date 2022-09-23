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
                <input id="otpSecret" name="email" type="text" class="form-control" />
            </div>
            <div class="form-group">
                <input class="btn btn-primary" type="submit" value="submit">
            </div>
        </form>
    </main>
    @include('includes.layouts.footer')
</body>

</html>
