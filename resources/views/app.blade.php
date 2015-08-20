<!DOCTYPE html>
<html>
    <head>
        <title>Find a DO</title>
        <link rel="stylesheet" href="css/app.css">
        <link rel="stylesheet" href="{{ asset('css/tooltipster.css') }}">
    </head>
    <body>
        <div class="container">
            @yield('content')
        </div>
    </body>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
    <script src="http://twitter.github.com/hogan.js/builds/3.0.1/hogan-3.0.1.js"></script>
    <script src="{{ asset('/js/typeahead.0.10.5.bundle.js') }}" type="text/javascript" charset="utf-8"></script>
    <script src="{{ asset('/js/jquery.tooltipster.js') }}" type="text/javascript" charset="utf-8"></script>
    <script src="{{ asset('/js/underscore.min.js') }}" type="text/javascript" charset="utf-8"></script>
    <script src="{{ asset('/js/app.js') }}" type="text/javascript" charset="utf-8"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            FindADo.init();
        });
    </script>
    

    @yield('script')

</html>

