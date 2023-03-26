<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Admin</title>
    <link rel="icon" type="image/png" sizes="32x32" href="/assets/images/logo.jpg" />
    <link rel="icon" type="image/png" sizes="16x16" href="/assets/images/logo.jpg" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ mix('css/bootstrap.min.css') }}" />
    <link rel="stylesheet" type="text/css" href="https://www.fontstatic.com/f=hanimation" />
    <style>
        *:not(.fa, i),
        h1,
        h2,
        h3,
        h4,
        h5,
        h6 {
            font-family: 'hanimation' !important;
            color: #373757;
        }

        .invalid-feedback * {
            color: #dc3545;
        }

        * {
            font-size: 15px !important;
        }

        body {
            margin: 0;
            background: rgba(88, 115, 254, 0.04);
        }

        /* width */
        ::-webkit-scrollbar {
            width: 7px;
            height: 7px;
        }

        /* Track */
        ::-webkit-scrollbar-track {
            background: #f1f1f1
        }

        /* Handle */
        ::-webkit-scrollbar-thumb {
            background: #888;
            border-radius: 5px;

        }

        /* Handle on hover */
        ::-webkit-scrollbar-thumb:hover {
            background: #555;
        }
   </style>
</head>

<body id="body" dir="ltr">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500&display=swap" rel="stylesheet">
    <div id="app">
        <app></app>
    </div>
    <script src="{{ mix('js/app.js') }}"></script>
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
</body>

</html>