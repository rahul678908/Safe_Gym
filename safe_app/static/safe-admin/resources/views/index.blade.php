<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
            integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
            crossorigin="anonymous"></script>

        <link rel="stylesheet" href="{{ asset('css/style.css') }}">
        <title>Admin Panel</title>
    </head>
    <body>
        <section>
            <div class="login_form">
                <div class="container">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="welcome_content">
                                <img src="{{asset('images/gym_white.svg')}}" class="img-fluid" width="340px" alt="">
                                <!-- <h1>Welcome To</h1> -->
                                <h1>Safe Gym Admin Panel</h1>     
                                <a href="{{ route('login') }}">Log In</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </body>
</html>