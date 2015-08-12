<!DOCTYPE html>
<html>
    <head>
        <title>Find a DO</title>
        <link rel="stylesheet" href="css/app.css">
    </head>
    <body>
        <div class="container">
            @yield('content')
        </div>
    </body>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
    <script src="{{ asset('/js/typeahead.bundle.js') }}" type="text/javascript" charset="utf-8"></script>
    <script src="http://twitter.github.com/hogan.js/builds/3.0.1/hogan-3.0.1.js"></script>
    <script src="{{ asset('/js/app.js') }}" type="text/javascript" charset="utf-8"></script>

    @yield('script')

</html>

